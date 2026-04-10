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

        // --- 2. TẠO MÃ QR TỪ URL CHUẨN ---
        $qrFileName = 'qr_' . $batchCode . '_' . time() . '.svg';
        $qrContent = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(300)
                    ->color(5, 150, 105) 
                    ->margin(10)
                    ->generate($scanUrl);
                    
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
}