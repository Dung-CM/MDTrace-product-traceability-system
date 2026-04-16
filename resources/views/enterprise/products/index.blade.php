@extends('enterprise.layouts.app')

@section('title', 'Quản lý Sản phẩm')

@section('content')
<div class="space-y-6">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <form action="{{ route('enterprise.products.index') }}" method="GET" class="w-full md:w-1/3 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên hoặc mã GTIN..." 
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </form>

        <a href="{{ route('enterprise.products.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold transition flex items-center gap-2 shadow-sm whitespace-nowrap">
            <i class="fa-solid fa-plus"></i> Thêm Sản Phẩm
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Hình ảnh</th>
                        <th class="px-6 py-4 font-semibold">Tên Sản phẩm</th>
                        <th class="px-6 py-4 font-semibold">Mã GTIN</th>
                        <th class="px-6 py-4 font-semibold">Danh mục</th>
                        <th class="px-6 py-4 font-semibold">Ngày tạo</th>
                        <th class="px-6 py-4 font-semibold text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-3">
                            @if($product->image_url)
                                <img src="{{ getSecureImageUrl($product->image_url) }}" alt="{{ $product->name }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200">
                            @else
                                <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200">
                                    <i class="fa-solid fa-box text-gray-400"></i>
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-3 font-medium text-gray-800">{{ $product->name }}</td>
                        <td class="px-6 py-3 font-mono text-gray-500">{{ $product->gtin_code ?? '---' }}</td>
                        <td class="px-6 py-3 text-gray-600">
                            <span class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-lg text-xs font-medium">
                                {{ $product->category->name ?? 'Không phân loại' }}
                            </span>
                        </td>
                        <td class="px-6 py-3 text-gray-500">{{ $product->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('enterprise.products.edit', $product->id) }}" 
                                class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition shadow-sm" 
                                title="Sửa sản phẩm">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form action="{{ route('enterprise.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('⚠️ Chú ý: Bạn có chắc chắn muốn xóa sản phẩm này không? Dữ liệu truy xuất sẽ mất vĩnh viễn!');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition shadow-sm" 
                                            title="Xóa sản phẩm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <i class="fa-solid fa-box-open text-4xl mb-3 text-gray-300 block"></i>
                            Chưa có sản phẩm nào. Hãy thêm sản phẩm đầu tiên!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection