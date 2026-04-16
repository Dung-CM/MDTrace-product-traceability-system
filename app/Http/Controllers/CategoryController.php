<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Hiển thị danh sách
    public function index()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return view('admin.categories', compact('categories'));
    }

    // Lưu danh mục mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ], [
            'name.unique' => 'Tên danh mục này đã tồn tại trong hệ thống!'
        ]);

        Category::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return back()->with('success', 'Đã thêm danh mục mới thành công!');
    }

    // Cập nhật danh mục
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string'
        ]);

        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return back()->with('success', 'Đã cập nhật danh mục thành công!');
    }

    // Xóa danh mục
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // (Tùy chọn) Kiểm tra nếu có sản phẩm đang dùng danh mục này thì không cho xóa
        // if ($category->products()->count() > 0) {
        //     return back()->with('error', 'Không thể xóa danh mục đang có sản phẩm!');
        // }

        $category->delete();
        return back()->with('success', 'Đã xóa danh mục thành công!');
    }
    public function products()
    {
        // Giả định bảng products của bạn có cột category_id
        return $this->hasMany(Product::class);
    }
}