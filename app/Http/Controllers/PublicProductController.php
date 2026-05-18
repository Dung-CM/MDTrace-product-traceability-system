<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ScanHistory;

class PublicProductController extends Controller
{
    // Hàm hiển thị trang Danh sách sản phẩm
    public function index(Request $request)
    {
        // Khởi tạo query lấy các sản phẩm mới nhất
        $query = Product::with('category')->latest();

        // 1. Tính năng Tìm kiếm (Theo tên hoặc mã)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('trace_code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('gtin_code', 'like', '%' . $searchTerm . '%')
                  ->orWhere('batch_code', 'like', '%' . $searchTerm . '%');
            });
        }

        // 2. Tính năng Lọc theo Danh mục
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Phân trang (12 sản phẩm trên 1 trang cho đẹp lưới 4 cột)
        $products = $query->paginate(12)->withQueryString();
        
        // Lấy danh sách danh mục để đổ ra bộ lọc
        $categories = Category::all();

        return view('public.products.index', compact('products', 'categories'));
    }

    // Hàm hiển thị trang Chi tiết 
    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return view('public.products.show', compact('product'));
    }

    public function search(Request $request)
    {
        $code = trim($request->input('code'));

        if (empty($code)) {
            // Trả về JSON nếu là gọi ngầm (AJAX), ngược lại trả về web bình thường
            if ($request->ajax()) { return response()->json(['success' => false, 'message' => 'Vui lòng nhập mã lô hàng cần truy xuất!']); }
            return redirect()->route('home')->with('error', 'Vui lòng nhập mã lô hàng cần truy xuất!');
        }

        $batch = \App\Models\Batch::with('product')->where('batch_code', $code)->first();

        if ($batch && $batch->product) {
            $gtin = $batch->product->gtin_code ?? '0000000000000';
            $batchCode = $batch->batch_code;
            $url = route('trace.verify', ['gtin' => $gtin, 'batchCode' => $batchCode]);
            
            // Nếu tìm thấy: Trả về link để Javascript tự động chuyển trang
            if ($request->ajax()) { return response()->json(['success' => true, 'redirect' => $url]); }
            return redirect($url);
        }

        // Nếu không tìm thấy
        if ($request->ajax()) { return response()->json(['success' => false, 'message' => 'Mã lô hàng không hợp lệ hoặc không tồn tại!']); }
        return redirect()->route('home')->with('error', 'Mã lô hàng không hợp lệ hoặc không tồn tại!');
    }
 // Hàm xử lý khi khách hàng Quét Mã QR
    public function scanQr(Request $request, $gtin, $batch_code)
    {
        // 1. Tìm Sản phẩm
        $product = Product::with('category')->where('gtin_code', $gtin)->firstOrFail();
        
        // 2. Tìm Lô hàng
        $batch = \App\Models\Batch::where('product_id', $product->id) 
                                  ->where('batch_code', $batch_code)
                                  ->firstOrFail();

        // 3. LOGIC MỚI: BẮT DỮ LIỆU QUÉT MÃ
        $forwardedIp = $request->header('x-forwarded-for'); 
        $realIp = $forwardedIp ? explode(',', $forwardedIp)[0] : $request->ip();

        ScanHistory::create([
            'batch_id'    => $batch->id,
            'ip_address'  => trim($realIp),
            'device_info' => $request->header('User-Agent'),
            'scanned_at'  => now(),
        ]);

        // ==========================================
        // 4. KIỂM TRA ĐỐI CHIẾU BLOCKCHAIN (REAL VERIFY)
        // ==========================================
        $transaction = \App\Models\BlockchainTransaction::where('batch_id', $batch->id)->first();
        $verifyStatus = 'none'; 
        
        $mysqlHash = '';
        $blockchainHash = '';

        if ($transaction) {
            // A. Tính lại mã băm của dữ liệu HIỆN TẠI trong database
            $payload = [
                'batch_code' => trim($batch->batch_code),
                'product_name' => trim($product->name),
                'mfg' => \Carbon\Carbon::parse($batch->manufacturing_date)->format('Y-m-d'),
                'exp' => \Carbon\Carbon::parse($batch->expiry_date)->format('Y-m-d'),
            ];
            $mysqlHash = '0x' . hash('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE));
            
            // B. Kéo mã băm GỐC đã niêm phong trên Smart Contract về
            try {
                $client = new \GuzzleHttp\Client(['verify' => false]);
                $requestManager = new \Web3\RequestManagers\HttpRequestManager(env('BLOCKCHAIN_RPC_URL'), 10, $client);
                $web3 = new \Web3\Web3(new \Web3\Providers\HttpProvider($requestManager));

                
              // BẢN DỊCH ABI CHUẨN 100% THEO SMART CONTRACT CỦA CHỊ
                $contractAbi = '[{"inputs":[{"internalType":"string","name":"_batchCode","type":"string"}],"name":"verifyBatch","outputs":[{"internalType":"string","name":"","type":"string"}],"stateMutability":"view","type":"function"}]';
                $contract = new \Web3\Contract($web3->provider, $contractAbi);
                
                $contract->at(env('BLOCKCHAIN_CONTRACT_ADDRESS'))->call('verifyBatch', $batch->batch_code, function ($err, $result) use (&$blockchainHash) {
                    if ($err === null && isset($result[0])) {
                        $blockchainHash = $result[0];
                    }
                });

                // C. SO SÁNH 
                if (empty($blockchainHash)) {
                    $verifyStatus = 'pending'; // Mới lên chuỗi, Blockchain chưa load kịp -> VÀNG
                } elseif (strtolower($blockchainHash) === strtolower($mysqlHash)) {
                    $verifyStatus = 'success'; // Trùng khớp -> XANH
                } else {
                    $verifyStatus = 'tampered'; // Lệch nhau -> ĐỎ
                }
            } catch (\Exception $e) {
                $verifyStatus = 'error'; // Mất mạng
            }
        }

       
        return view('public.products.show', compact('product', 'batch', 'transaction', 'verifyStatus', 'mysqlHash', 'blockchainHash'));
    }

}