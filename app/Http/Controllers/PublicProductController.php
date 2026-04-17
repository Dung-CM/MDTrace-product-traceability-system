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
    // Hàm xử lý khi khách hàng Quét Mã QR
   public function scanQr(Request $request, $gtin, $batch_code)
    {
        // 1. Tìm Sản phẩm
        $product = Product::with('category')->where('gtin_code', $gtin)->firstOrFail();
        
        // 2. Tìm Lô hàng
        $batch = \App\Models\Batch::where('product_id', $product->id)
                                  ->where('batch_code', $batch_code)
                                  ->firstOrFail();

        // 3. LOGIC MỚI: BẮT DỮ LIỆU QUÉT MÃ (IP & THIẾT BỊ)
        // Lấy danh sách IP từ Ngrok truyền vào
        $forwardedIp = $request->header('x-forwarded-for'); 
        // Nếu có nhiều IP (do qua nhiều trạm), lấy cái đầu tiên. Nếu không có Ngrok thì lấy IP gốc.
        $realIp = $forwardedIp ? explode(',', $forwardedIp)[0] : $request->ip();

       ScanHistory::create([
            'batch_id'    => $batch->id,
            'ip_address'  => trim($realIp), // Lưu IP Thật vào Database
            'device_info' => $request->header('User-Agent'),
            'scanned_at'  => now(),
        ]);

        return view('public.products.show', compact('product', 'batch'));
    }
}