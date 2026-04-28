<?php

namespace App\Http\Controllers\Enterprise;

use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;
use kornrunner\Keccak;
use Web3p\EthereumTx\Transaction;
use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
class BatchController extends Controller
{
    // 1. Hiển thị danh sách Lô hàng
    public function index(Request $request)
    {
        // Lấy các lô hàng thuộc về những sản phẩm do Doanh nghiệp này tạo ra
        $query = Batch::whereHas('product', function($q) {
            $q->where('user_id', Auth::id());
        })->with('product');

        if ($request->has('search') && $request->search != '') {
            $query->where('batch_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('product', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        $batches = $query->latest()->paginate(10)->withQueryString();

        return view('enterprise.batches.index', compact('batches'));
    }

    // 2. Hiển thị Form tạo Lô hàng mới
    public function create()
    {
        // Chỉ lấy sản phẩm của doanh nghiệp đang đăng nhập để đưa vào dropdown
        $products = Product::where('user_id', Auth::id())->latest()->get();
        
        if($products->isEmpty()) {
            return redirect()->route('enterprise.products.create')
                ->with('error', 'Bạn cần tạo ít nhất 1 Sản phẩm trước khi tạo Lô hàng!');
        }

        return view('enterprise.batches.create', compact('products'));
    }

    // 3. Xử lý Lưu Lô hàng & Tự động tạo QR Code
   public function store(Request $request)
    {
        $request->validate([
            'product_id'         => 'required|exists:products,id',
            'batch_code'         => 'required|string|max:50|unique:batches,batch_code',
            'quantity'           => 'required|integer|min:1',
            'manufacturing_date' => 'required|date',
            'expiry_date'        => 'required|date|after_or_equal:manufacturing_date',
        ],[
            // --- NƠI ĐỔI THÔNG BÁO LỖI ---
            'batch_code.unique' => 'Mã lô này đã được tạo. Vui lòng nhập mã khác!',
            
            // (Bạn có thể Việt hóa luôn các lỗi khác cho chuyên nghiệp)
            'product_id.required' => 'Vui lòng chọn sản phẩm xuất lô.',
            'batch_code.required' => 'Vui lòng nhập mã lô hàng.',
            'quantity.required' => 'Vui lòng nhập số lượng.',
            'quantity.min'        => 'Số lượng phải lớn hơn 0.',
            'expiry_date.after_or_equal' => 'Hạn sử dụng phải bằng hoặc sau ngày sản xuất.'
        ]);

        $product = Product::where('user_id', Auth::id())->findOrFail($request->product_id);
        
        // --- 1. LOGIC XỬ LÝ CHUẨN GS1 TOÀN CẦU ---
        $gtin = $product->gtin_code ?? '0000000000000'; // Lấy GTIN của Sản phẩm
        $batchCode = $request->batch_code;
        
        // Ép kiểu Hạn sử dụng thành định dạng YYMMDD (VD: 2027-01-10 -> 270110)
        $expDateObj = \Carbon\Carbon::parse($request->expiry_date);
        $yymmdd = $expDateObj->format('ymd');

        // Lấy chính xác tên miền Ngrok từ file .env (đảm bảo luôn có https://)
        $baseUrl = rtrim(env('APP_URL'), '/');
       $scanUrl = $baseUrl . "/txng/01/{$gtin}/10/{$batchCode}";

        // --- 2. TẠO MÃ QR TỪ URL CHUẨN (Dùng thư viện Endroid) ---
        $qrFileName = 'qr_' . $batchCode . '_' . time() . '.png'; // Bắt buộc dùng PNG
        
       $writer = new PngWriter();

       $qrCode = QrCode::create($scanUrl)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High) 
            ->setSize(300)
            ->setMargin(10)
            ->setForegroundColor(new Color(5, 150, 105));

        // Lấy thông tin Profile Doanh nghiệp
        $userProfile = \App\Models\UserProfile::where('user_id', Auth::id())->first();
        $logo = null;

        // Nếu có Logo: Nạp logo vào
        if ($userProfile && $userProfile->logo_url && Storage::disk('public')->exists($userProfile->logo_url)) {
            $logo = Logo::create(storage_path('app/public/' . $userProfile->logo_url))
                ->setResizeToWidth(60); // Logo chiếm khoảng 20% chiều rộng QR (60/300)
        }

        // Tiến hành vẽ mã QR (Có logo hoặc không có logo)
        $result = $writer->write($qrCode, $logo);
                    
        // Lưu file vào ổ cứng
        Storage::disk('public')->put('qrcodes/' . $qrFileName, $result->getString());
        // --- 3. LƯU VÀO DATABASE ---
        Batch::create([
            'product_id'         => $product->id,
            'batch_code'         => $batchCode,
            'manufacturing_date' => $request->manufacturing_date,
            'expiry_date'        => $request->expiry_date,
            'quantity'           => $request->quantity,
            'qr_code_url'        => 'qrcodes/' . $qrFileName,
            'created_by'         => Auth::id(),
        ]);

        return redirect()->route('enterprise.batches.index')->with('success', 'Đã tạo Lô hàng và Mã QR (chuẩn GS1) thành công!');
    }

    // 4. Hàm Tải mã QR về máy (Dành cho Nút Download)
    public function downloadQr($id)
    {
        $batch = Batch::whereHas('product', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        $filePath = storage_path('app/public/' . $batch->qr_code_url);
        
        if (file_exists($filePath)) {
            return response()->download($filePath, 'QR_' . $batch->batch_code . '.png');
        }

        return back()->with('error', 'Không tìm thấy file QR Code!');
    }

    // 5. Xóa Lô hàng
    public function destroy($id)
    {
        $batch = Batch::whereHas('product', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

        // Xóa luôn file ảnh QR cho sạch ổ cứng
        if ($batch->qr_code_url) {
            Storage::disk('public')->delete($batch->qr_code_url);
        }

        $batch->delete();
        return redirect()->route('enterprise.batches.index')->with('success', 'Đã xóa lô hàng thành công!');
    }
   public function mintToBlockchain($id)
        {
            $batch = \App\Models\Batch::with('product')->findOrFail($id);

            // 1. Kiểm tra tồn tại
            $exists = \App\Models\BlockchainTransaction::where('batch_id', $id)->first();
            if ($exists) {
                return back()->with('error', 'Lô hàng này đã tồn tại trên Blockchain!');
            }

            // 2. Hash Dữ liệu
            $payload = [
                'batch_code' => $batch->batch_code,
                'product_name' => $batch->product->name,
                'mfg' => $batch->manufacturing_date,
                'exp' => $batch->expiry_date,
            ];
            $dataHash = '0x' . hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE));

            try {
                // --- BẮT ĐẦU PHẦN KÝ OFFLINE ---
                $contractAddress = env('BLOCKCHAIN_CONTRACT_ADDRESS');
                $privateKey = env('BLOCKCHAIN_PRIVATE_KEY'); // Đảm bảo trong .env ĐÃ CÓ biến này
                $fromAddress = '0x8991C8C5271976CEBF3A082A971e166BB4f3A160'; // Ví dụ: 0x899...

                // Khởi tạo Web3
               // Dùng Guzzle Client để ÉP Wampserver bỏ qua xác thực SSL (Chỉ dùng cho Localhost)
                    $client = new \GuzzleHttp\Client(['verify' => false]); 
                    $requestManager = new \Web3\RequestManagers\HttpRequestManager(env('BLOCKCHAIN_RPC_URL'), 10, $client);
                    $web3 = new \Web3\Web3(new \Web3\Providers\HttpProvider($requestManager));
                $eth = $web3->eth;

             // 3. Lấy Nonce (số thứ tự giao dịch của ví)
                $nonceStr = '';
                $eth->getTransactionCount($fromAddress, 'pending', function ($err, $result) use (&$nonceStr) {
                    if ($err !== null) {
                        throw new \Exception('Lỗi kết nối Alchemy (Nonce): ' . $err->getMessage());
                    }
                    $nonceStr = $result->toString(); // Trả về chuỗi số nguyên (vd: "15")
                });

                if ($nonceStr === '') {
                    throw new \Exception('Không lấy được số Nonce từ mạng lưới.');
                }
                $nonceHex = '0x' . dechex((int)$nonceStr);
                // 4. Lấy dữ liệu Data Hex của hàm mintBatch từ Contract ABI
              $contract = new Contract($web3->provider, '[
                    {
                        "inputs": [
                            { "internalType": "string", "name": "_batchCode", "type": "string" },
                            { "internalType": "string", "name": "_dataHash", "type": "string" },
                            { "internalType": "uint256", "name": "timestamp", "type": "uint256" }
                        ],
                        "name": "mintBatch",
                        "outputs": [],
                        "stateMutability": "nonpayable",
                        "type": "function"
                    }
                ]');
                              // KHÔNG DÙNG CALLBACK CHO GETDATA
              $timestamp = (int) time(); // Tạo tham số thứ 3: Thời gian hiện tại bằng số nguyên

                $data = $contract->at($contractAddress)->getData(
                    'mintBatch', 
                    $batch->batch_code, // Tham số 1: Mã lô (string)
                    $dataHash,          // Tham số 2: Mã băm dữ liệu (string)
                    $timestamp          // Tham số 3: Thời gian mint (uint256)
                );

                if (!$data) {
                    throw new \Exception('Không thể tạo dữ liệu giao dịch (Data rỗng).');
                }

                // Đảm bảo data luôn có chữ 0x ở đầu
                $cleanData = str_starts_with($data, '0x') ? $data : '0x' . $data;

               // 5. Khởi tạo Giao dịch (Transaction)
                $txParams = [
                    'nonce'    => $nonceHex,
                    'from'     => $fromAddress,
                    'to'       => $contractAddress,
                    'gas'      => '0x' . dechex(300000), // 300,000 gas cho an toàn
                    'gasPrice' => '0x' . dechex(15000000000), // 15 Gwei
                    'value'    => '0x0', 
                    'data'     => $cleanData,
                    'chainId'  => 11155111 // Sepolia ID
                ];

                $transaction = new Transaction($txParams);
                
                // 6. KÝ GIAO DỊCH BẰNG PRIVATE KEY TẠI SERVER
                // Cắt bỏ an toàn chữ '0x' ở đầu Private Key
                $cleanPrivateKey = str_starts_with($privateKey, '0x') ? substr($privateKey, 2) : $privateKey;
                $signedTx = $transaction->sign($cleanPrivateKey);

                // 7. GỬI GIAO DỊCH ĐÃ KÝ LÊN ALCHEMY (Hàm này bắt buộc có callback)
                $txHash = null;
                $finalTx = str_starts_with($signedTx, '0x') ? $signedTx : '0x' . $signedTx;

                $eth->sendRawTransaction($finalTx, function ($err, $result) use (&$txHash) {
                    if ($err !== null) {
                        throw new \Exception('Lỗi gửi giao dịch: ' . $err->getMessage());
                    }
                    $txHash = $result;
                });

                if (!$txHash) {
                    throw new \Exception('Không nhận được Transaction Hash trả về.');
                }

                // 8. Lưu Transaction Hash vào database
                \App\Models\BlockchainTransaction::create([
                    'batch_id'         => $batch->id,
                    'transaction_hash' => $txHash,
                    'network'          => 'Sepolia Testnet',
                    'status'           => 1
                ]);

                return back()->with('success', 'Thành công! Đã đẩy dữ liệu lên Blockchain Sepolia. Mã Hash: ' . $txHash);

            } catch (\Exception $e) {
                return back()->with('error', 'Lỗi Blockchain: ' . $e->getMessage());
            }
        }
}