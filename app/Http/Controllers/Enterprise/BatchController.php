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
       // --- 3. HỨNG DỮ LIỆU TỪ FORM (NGUỒN GỐC, NHẬT KÝ, PHÂN PHỐI) ---
        // Xử lý Nhật ký (Traces)
        $tracesData = $request->input('traces', []);
        if ($request->hasFile('traces')) {
            foreach ($request->file('traces') as $index => $traceFile) {
                if (isset($traceFile['image_url'])) {
                    $tracesData[$index]['image_url'] = $traceFile['image_url']->store('traces', 'public');
                }
            }
        }

        // Xử lý Nguồn gốc (Materials)
        $materialsData = $request->input('materials', []);
        if ($request->hasFile('materials')) {
            foreach ($request->file('materials') as $index => $materialFile) {
                if (isset($materialFile['image_url'])) {
                    $materialsData[$index]['image_url'] = $materialFile['image_url']->store('materials', 'public');
                }
            }
        }
        $originInfo = [
            'supplier_name'    => $request->input('supplier_name'),
            'supplier_address' => $request->input('supplier_address'),
            'materials'        => $materialsData
        ];

        // Xử lý Phân phối
        $distributorInfo = [
            'name'     => $request->input('distributor_name'),
            'address'  => $request->input('distributor_address'),
            'date'     => $request->input('distributor_date'),
            'country'  => $request->input('distributor_country'),
            'province' => $request->input('distributor_province'),
            'storage'  => $request->input('distributor_storage'),
        ];

        // --- 4. LƯU VÀO DATABASE ---
        Batch::create([
            'product_id'         => $product->id,
            'batch_code'         => $batchCode,
            'manufacturing_date' => $request->manufacturing_date,
            'expiry_date'        => $request->expiry_date,
            'quantity'           => $request->quantity,
            'qr_code_url'        => 'qrcodes/' . $qrFileName,
            'created_by'         => Auth::id(),
            'trace_logs'         => $tracesData,
            'origin_info'        => $originInfo,
            'distributor_info'   => $distributorInfo,
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

        // 2. Hash Dữ liệu chuẩn
        $payload = [
            'batch_code' => trim($batch->batch_code),
            'product_name' => trim($batch->product->name),
            'mfg' => \Carbon\Carbon::parse($batch->manufacturing_date)->format('Y-m-d'),
            'exp' => \Carbon\Carbon::parse($batch->expiry_date)->format('Y-m-d'),
        ];
        $dataHash = '0x' . hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE));

        try {
            $contractAddress = env('BLOCKCHAIN_CONTRACT_ADDRESS');
            $privateKey = env('BLOCKCHAIN_PRIVATE_KEY'); 
            $fromAddress = '0x8991C8C5271976CEBF3A082A971e166BB4f3A160'; 

            $client = new \GuzzleHttp\Client(['verify' => false]); 
            $requestManager = new \Web3\RequestManagers\HttpRequestManager(env('BLOCKCHAIN_RPC_URL'), 10, $client);
            $web3 = new \Web3\Web3(new \Web3\Providers\HttpProvider($requestManager));
            $eth = $web3->eth;

            $nonceStr = '';
            // Lấy Nonce
            $eth->getTransactionCount($fromAddress, 'pending', function ($err, $result) use (&$nonceStr) {
                if ($err !== null) throw new \Exception('Lỗi Nonce: ' . $err->getMessage());
                $nonceStr = $result->toString(); 
            });

            if ($nonceStr === '') throw new \Exception('Không lấy được số Nonce.');
            $nonceHex = '0x' . dechex((int)$nonceStr);

            // =========================================================================
            // GIẢI PHÁP TỐI THƯỢNG: TỰ VIẾT MÃ MÁY EVM (BYPASS HOÀN TOÀN LỖI THƯ VIỆN)
            // =========================================================================
            $methodId = substr(\kornrunner\Keccak::hash('mintBatch(string,string)', 256), 0, 8);

            // Xử lý tham số 1
            $str1 = trim($batch->batch_code);
            $str1Hex = bin2hex($str1);
            $str1LenDec = strlen($str1);
            $str1LenHex = str_pad(dechex($str1LenDec), 64, '0', STR_PAD_LEFT);
            $str1DataHex = str_pad($str1Hex, ceil($str1LenDec / 32) * 64, '0', STR_PAD_RIGHT);

            // Xử lý tham số 2
            $str2 = $dataHash;
            $str2Hex = bin2hex($str2);
            $str2LenDec = strlen($str2);
            $str2LenHex = str_pad(dechex($str2LenDec), 64, '0', STR_PAD_LEFT);
            $str2DataHex = str_pad($str2Hex, ceil($str2LenDec / 32) * 64, '0', STR_PAD_RIGHT);

            // Tính toán con trỏ
            $offset1Hex = str_pad(dechex(64), 64, '0', STR_PAD_LEFT);
            $str1BlockBytes = 32 + (ceil($str1LenDec / 32) * 32);
            $offset2Dec = 64 + $str1BlockBytes;
            $offset2Hex = str_pad(dechex($offset2Dec), 64, '0', STR_PAD_LEFT);

            // Nối toàn bộ thành mã Hex hoàn chỉnh gửi thẳng vào tim Blockchain
            $cleanData = '0x' . $methodId . $offset1Hex . $offset2Hex . $str1LenHex . $str1DataHex . $str2LenHex . $str2DataHex;
            // =========================================================================

            // Cấu hình giao dịch
            $txParams = [
                'nonce'    => $nonceHex,
                'from'     => $fromAddress,
                'to'       => $contractAddress,
                'gasLimit' => '0x493E0', // 300,000 gas
                'gasPrice' => '0x37E11D600', // 15 Gwei
                'value'    => '0x0', 
                'data'     => $cleanData,
                'chainId'  => 11155111
            ];

            $transaction = new \Web3p\EthereumTx\Transaction($txParams);
            
            $cleanPrivateKey = str_starts_with($privateKey, '0x') ? substr($privateKey, 2) : $privateKey;
            $signedTx = $transaction->sign($cleanPrivateKey);

            $txHash = null;
            $finalTx = str_starts_with($signedTx, '0x') ? $signedTx : '0x' . $signedTx;

            $eth->sendRawTransaction($finalTx, function ($err, $result) use (&$txHash) {
                if ($err !== null) throw new \Exception('Lỗi gửi GD: ' . $err->getMessage());
                $txHash = $result;
            });

            if (!$txHash) throw new \Exception('Không có Tx Hash.');

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

        public function edit($id)
    {
        $batch = Batch::whereHas('product', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);
        
        $products = Product::where('user_id', Auth::id())->get();
        return view('enterprise.batches.edit', compact('batch', 'products'));
    }

    public function update(Request $request, $id)
    {
        $batch = Batch::whereHas('product', function($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($id);

       $data = [
            'product_id'         => $request->product_id,
            'batch_code'         => $request->batch_code,
            'quantity'           => $request->quantity,
            'manufacturing_date' => $request->manufacturing_date,
            'expiry_date'        => $request->expiry_date,
        ];

        // 1. Xử lý Nhật ký (Giữ ảnh cũ nếu không chọn ảnh mới)
        $tracesData = $request->input('traces', []);
        $oldTraces = $batch->trace_logs ?? [];
        foreach ($tracesData as $index => &$trace) {
            if (!$request->hasFile("traces.$index.image_url")) {
                $trace['image_url'] = $oldTraces[$index]['image_url'] ?? null;
            } else {
                $trace['image_url'] = $request->file("traces.$index.image_url")->store('traces', 'public');
            }
        }
        $data['trace_logs'] = $tracesData;

        // 2. Xử lý Nguồn gốc
        $materialsData = $request->input('materials', []);
        $oldMaterials = $batch->origin_info['materials'] ?? [];
        foreach ($materialsData as $index => &$mat) {
            if (!$request->hasFile("materials.$index.image_url")) {
                $mat['image_url'] = $oldMaterials[$index]['image_url'] ?? null;
            } else {
                $mat['image_url'] = $request->file("materials.$index.image_url")->store('materials', 'public');
            }
        }
        $data['origin_info'] = [
            'supplier_name' => $request->input('supplier_name'),
            'supplier_address' => $request->input('supplier_address'),
            'materials' => $materialsData
        ];

        // 3. Xử lý Phân phối
        $data['distributor_info'] = [
            'name'     => $request->input('distributor_name'),
            'address'  => $request->input('distributor_address'),
            'date'     => $request->input('distributor_date'),
            'country'  => $request->input('distributor_country'),
            'province' => $request->input('distributor_province'),
            'storage'  => $request->input('distributor_storage'),
        ];

        $batch->update($data);
        return redirect()->route('enterprise.batches.index')->with('success', 'Cập nhật lô hàng thành công!');
    }
}