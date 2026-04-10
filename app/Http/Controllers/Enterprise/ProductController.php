<?php

namespace App\Http\Controllers\Enterprise;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Storage;
class ProductController extends Controller
{
    // Hiển thị form tạo sản phẩm
    public function create()
    {
        // Lấy danh sách danh mục để đổ vào thẻ <select>
        $categories = Category::all(); 
        $profile = UserProfile::where('user_id', Auth::id())->first();
        return view('enterprise.products.create', compact('categories','profile'));
    }

    //  Hàm hiển thị Form Sửa sản phẩm
    public function edit($id)
    {
        // Chỉ lấy sản phẩm của đúng doanh nghiệp đang đăng nhập
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::all();
        $profile = UserProfile::where('user_id', Auth::id())->first();

        return view('enterprise.products.edit', compact('product', 'categories', 'profile'));
    }

    //  Hàm xử lý cập nhật dữ liệu vào DB
   public function update(Request $request, $id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'trace_code'  => 'required|string|max:100',
        ]);

        $data = $request->except(['image', 'certificates', 'materials', 'traces', 'company_images', '_method', '_token']);
        $data['is_authentic'] = $request->has('is_authentic') ? true : false;

        if ($request->hasFile('image')) {
            if ($product->image_url) { Storage::disk('public')->delete($product->image_url); }
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        // --- FIX MODAL 1: NGUỒN GỐC (GIỮ ẢNH CŨ) ---
        $materialsData = $request->input('materials', []);
        $oldMaterials = $product->origin_info['materials'] ?? [];
        
        foreach ($materialsData as $index => &$mat) {
            // Nếu không up ảnh mới, lấy lại ảnh cũ từ database
            if (!$request->hasFile("materials.$index.image")) {
                $mat['image'] = $oldMaterials[$index]['image'] ?? null;
            } else {
                $mat['image'] = $request->file("materials.$index.image")->store('materials', 'public');
            }
        }
        $data['origin_info'] = [
            'supplier_name'    => $request->input('supplier_name'),
            'supplier_address' => $request->input('supplier_address'),
            'materials'        => $materialsData
        ];

        // --- FIX MODAL 4: NHẬT KÝ (GIỮ ẢNH CŨ) ---
        $tracesData = $request->input('traces', []);
        $oldTraces = $product->trace_logs ?? [];

        foreach ($tracesData as $index => &$trace) {
            // Nếu không up ảnh mới, lấy lại ảnh cũ từ database
            if (!$request->hasFile("traces.$index.image")) {
                $trace['image'] = $oldTraces[$index]['image'] ?? null;
            } else {
                $trace['image'] = $request->file("traces.$index.image")->store('traces', 'public');
            }
        }
        $data['trace_logs'] = $tracesData;

        // Modal 5: Phân phối
        $data['distributor_info'] = [
            'name'     => $request->input('distributor_name'),
            'address'  => $request->input('distributor_address'),
            'date'     => $request->input('distributor_date'),
            'country'  => $request->input('distributor_country'),
            'province' => $request->input('distributor_province'),
            'storage'  => $request->input('distributor_storage'),
        ];

        $product->update($data);

        return redirect()->route('enterprise.products.index')->with('success', 'Đã cập nhật sản phẩm thành công!');
    }

    // Hàm Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::where('user_id', Auth::id())->findOrFail($id);
        
        // Xóa ảnh chính trên ổ cứng cho sạch server
        if ($product->image_url) {
            Storage::disk('public')->delete($product->image_url);
        }
        
        $product->delete();

        return redirect()->route('enterprise.products.index')->with('success', 'Đã xóa sản phẩm thành công!');
    }
    // Hiển thị danh sách sản phẩm (Có tìm kiếm & Phân trang)
    public function index(Request $request)
    {
        // Khởi tạo query: Lấy sản phẩm của ĐÚNG doanh nghiệp đang đăng nhập + Kèm theo tên Danh mục
        $query = Product::with('category')->where('user_id', Auth::id());

        // Xử lý ô Tìm kiếm (Search)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('gtin_code', 'like', '%' . $searchTerm . '%');
            });
        }

        // Sắp xếp mới nhất lên đầu và Phân trang (10 sản phẩm/trang)
        $products = $query->latest()->paginate(10);

       
        $products->appends(['search' => $request->search]);

        return view('enterprise.products.index', compact('products'));
    }

    // Xử lý lưu sản phẩm
   public function store(Request $request)
    {
        // 1. Validate thông tin cơ bản
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'trace_code'  => 'required|string|max:100',
        ]);

        $data = $request->except(['image', 'certificates', 'materials', 'traces', 'company_images']);
        $data['user_id'] = Auth::id();
        $data['is_authentic'] = $request->has('is_authentic') ? true : false;

        // 2. Xử lý Ảnh chính của sản phẩm
        if ($request->hasFile('image')) {
            $data['image_url'] = $request->file('image')->store('products', 'public');
        }

        // 3. Xử lý Chứng nhận Sản phẩm (Nhiều file)
        if ($request->hasFile('certificates')) {
            $certPaths = [];
            foreach ($request->file('certificates') as $file) {
                $certPaths[] = $file->store('product_certificates', 'public');
            }
            $data['certificates'] = $certPaths;
        }

        // 4. Xử lý Modal 1: Nguồn gốc nguyên liệu (Gom thành mảng origin_info)
        $materialsData = $request->input('materials', []);
        // Xử lý upload ảnh cho từng nguyên liệu (nếu có)
        if ($request->hasFile('materials')) {
            foreach ($request->file('materials') as $index => $materialFile) {
                if (isset($materialFile['image'])) {
                    $materialsData[$index]['image'] = $materialFile['image']->store('materials', 'public');
                }
            }
        }
        $data['origin_info'] = [
            'supplier_name'    => $request->input('supplier_name'),
            'supplier_address' => $request->input('supplier_address'),
            'materials'        => $materialsData
        ];

        // 5. Xử lý Modal 2: Thông tin chi tiết (Gom thành mảng product_details)
        $data['product_details'] = [
            'product_type'          => $request->input('product_type'),
            'origin_country'        => $request->input('origin_country'),
            'brand_name'            => $request->input('brand_name'),
            'weight'                => $request->input('weight'),
            'quality_criteria'      => $request->input('quality_criteria'),
            'storage_instructions'  => $request->input('storage_instructions'),
            'usage_instructions'    => $request->input('usage_instructions'),
            'detailed_introduction' => $request->input('detailed_introduction'),
            'company_info_html'     => $request->input('company_info')
        ];

        // 6. Xử lý Modal 3: Thông tin công ty (Gom thành mảng company_info)
        $companyImages = [];
        if ($request->hasFile('company_images')) {
            foreach ($request->file('company_images') as $img) {
                $companyImages[] = $img->store('company_images', 'public');
            }
        }
        $data['company_info'] = [
            'company_name'        => $request->input('company_name_detail'),
            'phone'               => $request->input('company_phone'),
            'email'               => $request->input('company_email'),
            'website'             => $request->input('company_website'),
            'address'             => $request->input('company_address'),
            'description'         => $request->input('company_description'),
            'map_link'            => $request->input('company_map_link'),
            'company_images'      => $companyImages // Ảnh up riêng ở Modal 3
        ];

        // 7. Xử lý Modal 4: Nhật ký sản xuất (Gom thành mảng trace_logs)
        $tracesData = $request->input('traces', []);
        // Xử lý upload ảnh cho từng công đoạn
        if ($request->hasFile('traces')) {
            foreach ($request->file('traces') as $index => $traceFile) {
                if (isset($traceFile['image'])) {
                    $tracesData[$index]['image'] = $traceFile['image']->store('traces', 'public');
                }
            }
        }
        $data['trace_logs'] = $tracesData;

        // 8. Xử lý Modal 5: Đơn vị phân phối (Gom thành mảng distributor_info)
        $data['distributor_info'] = [
            'name'     => $request->input('distributor_name'),
            'address'  => $request->input('distributor_address'),
            'date'     => $request->input('distributor_date'),
            'country'  => $request->input('distributor_country'),
            'province' => $request->input('distributor_province'),
            'storage'  => $request->input('distributor_storage'),
        ];

        // LƯU VÀO DATABASE
        Product::create($data);

        return redirect()->route('enterprise.products.index')->with('success', 'Đã tạo sản phẩm truy xuất thành công!');
    }
}