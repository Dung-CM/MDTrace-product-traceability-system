<!-- resources/views/public/products/index.blade.php -->
 @extends('layouts.app') 

@section('title', 'Sản phẩm truy xuất nguồn gốc')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <div class="mb-8">
        <nav class="text-sm font-medium text-gray-500 mb-3">
            <a href="/" class="hover:text-emerald-600 transition">Trang chủ</a>
            <span class="mx-2">/</span>
            <span class="text-emerald-700">Sản phẩm</span>
        </nav>
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Sản Phẩm Truy Xuất</h1>
                <p class="text-gray-500">Hiển thị {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }} trên tổng số {{ $products->total() }} sản phẩm</p>
            </div>
            
            <form action="{{ route('public.products.index') }}" method="GET" class="w-full md:w-auto flex flex-col sm:flex-row gap-3">
                <select name="category" onchange="this.form.submit()" class="bg-white border border-emerald-200 text-gray-700 py-2.5 px-4 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm transition font-medium cursor-pointer hover:border-emerald-400">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                
                <div class="relative w-full sm:w-72">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo tên hoặc mã lô..." class="w-full pl-4 pr-10 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none shadow-sm transition">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-emerald-600 transition">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($products as $product)
            <a href="{{ route('public.products.show', $product->id) }}" class="group bg-white border border-gray-100 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col h-full hover:-translate-y-1">
                <div class="relative aspect-square sm:aspect-[4/3] overflow-hidden bg-gray-50 flex-shrink-0 border-b border-gray-100">
                    @if($product->image_url)
                        <img src="{{ str_starts_with($product->image_url, 'http') ? $product->image_url : asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fa-solid fa-image text-gray-300 text-5xl"></i>
                        </div>
                    @endif
                    @if($product->is_authentic)
                        <div class="absolute top-3 right-3 bg-white/90 backdrop-blur text-emerald-600 text-xs font-bold px-2 py-1 rounded-lg shadow-sm flex items-center gap-1">
                            <i class="fa-solid fa-shield-check"></i> Chính hãng
                        </div>
                    @endif
                </div>

                <div class="p-5 flex flex-col flex-1">
                    <h3 class="text-lg font-bold text-emerald-700 mb-2 line-clamp-2 group-hover:text-emerald-600 transition">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-600 mb-4 line-clamp-2 uppercase font-medium">{{ $product->company_name }}</p>
                    
                    <div class="mt-auto pt-4 border-t border-gray-100 flex justify-between items-center text-xs text-gray-500">
                        <span><i class="fa-solid fa-barcode mr-1"></i> {{ $product->batch_code ?? 'N/A' }}</span>
                        <span class="bg-emerald-50 text-emerald-800 border border-emerald-100 px-2.5 py-1.5 rounded-md font-bold">
                            {{ $product->category->name ?? 'Khác' }}
                        </span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full py-20 flex flex-col items-center justify-center text-center bg-gray-50 rounded-2xl border border-dashed border-gray-200">
                <i class="fa-solid fa-box-open text-gray-300 text-6xl mb-4"></i>
                <h3 class="text-xl font-bold text-gray-700 mb-2">Không tìm thấy sản phẩm nào</h3>
                <p class="text-gray-500">Vui lòng thử lại với từ khóa hoặc danh mục khác.</p>
                <a href="{{ route('public.products.index') }}" class="mt-4 px-6 py-2 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition">Xóa bộ lọc</a>
            </div>
        @endforelse
    </div>

    <div class="mt-10 flex justify-center">
        {{ $products->links() }}
    </div>

</div>
@endsection