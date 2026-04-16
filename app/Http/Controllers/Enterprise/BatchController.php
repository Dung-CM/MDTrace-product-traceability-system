<?php

namespace App\Http\Controllers\Enterprise;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        // Ghép nối thành URL chuẩn GS1 Digital Link
       $scanUrl = url("/txng/01/{$gtin}/10/{$batchCode}");

        // --- 2. TẠO MÃ QR TỪ URL CHUẨN (CÓ TÍCH HỢP LOGO DOANH NGHIỆP) ---
        // Đổi đuôi file sang .png vì định dạng PNG hỗ trợ chèn ảnh đè lên tốt nhất
        $qrFileName = 'qr_' . $batchCode . '_' . time() . '.svg'; 
        
        // Cấu hình QR Code nền tảng (Size 300, Màu xanh ngọc, Mức chịu lỗi H cao nhất)
        $qrBuilder = \SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')
                    ->size(300)
                    ->color(5, 150, 105) 
                    ->margin(2) 
                    ->errorCorrection('H'); // Mức H: Cho phép QR bị che 30% vẫn quét được

        // Lấy logo của doanh nghiệp đang đăng nhập
        $userProfile = \App\Models\UserProfile::where('user_id', Auth::id())->first();
        
        if ($userProfile && $userProfile->logo_url && Storage::disk('public')->exists($userProfile->logo_url)) {
            // Nếu có Logo: Chèn logo vào giữa (chiếm 20% diện tích)
            $logoPath = storage_path('app/public/' . $userProfile->logo_url);
            $qrContent = $qrBuilder->merge($logoPath, 0.2, true)->generate($scanUrl);
        } else {
            // Nếu chưa có Logo: Tạo mã QR nguyên bản
            $qrContent = $qrBuilder->generate($scanUrl);
        }
                    
        Storage::disk('public')->put('qrcodes/' . $qrFileName, $qrContent);
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
            return response()->download($filePath, 'QR_' . $batch->batch_code . '.svg');
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
    // Băm dữ liệu và lưu lên Private Ledger / IPFS Node
    public function mintToBlockchain($id)
    {
        $batch = \App\Models\Batch::with('product')->findOrFail($id);

        // 1. Kiểm tra xem lô hàng này đã lên chuỗi chưa
        $exists = \App\Models\BlockchainTransaction::where('batch_id', $id)->first();
        if ($exists) {
            return back()->with('error', 'Lô hàng này đã được đưa lên chuỗi trước đó. Không thể ghi đè!');
        }

        // 2. GOM DỮ LIỆU ĐỂ BĂM (Data Payload)
        // Đây là những thông tin quan trọng nhất, sửa 1 dấu chấm hash cũng sẽ đổi
        $payload = [
            'batch_id' => $batch->id,
            'batch_code' => $batch->batch_code,
            'product_name' => $batch->product->name,
            'manufacturing_date' => $batch->manufacturing_date,
            'expiry_date' => $batch->expiry_date,
            // Nếu bạn có bảng logs (nhật ký), hãy kéo dữ liệu logs vào đây:
            // 'logs' => $batch->logs->toArray() 
        ];

        // 3. Ép thành chuỗi JSON chuẩn
        $jsonData = json_encode($payload, JSON_UNESCAPED_UNICODE);

        // 4. THUẬT TOÁN BĂM SHA-256 (Tạo ra chuỗi 64 ký tự bất biến)
        $hash = hash('sha256', $jsonData);

        // Thêm tiền tố 0x để trông giống chuẩn mã băm Blockchain / Smart Contract
        $finalHash = '0x' . $hash;

        // 5. LƯU VÀO SỔ CÁI (Database)
        \App\Models\BlockchainTransaction::create([
            'batch_id' => $batch->id,
            'transaction_hash' => $finalHash,
            'network' => 'MDTrace IPFS-Simulated Network', // Phù hợp với định hướng IPFS
            'status' => 1
        ]);

        return back()->with('success', 'Đã đóng gói và mã hóa (Hash) dữ liệu lô hàng thành công!');
    }
}