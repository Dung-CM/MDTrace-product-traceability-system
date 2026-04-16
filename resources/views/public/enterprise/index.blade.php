@extends('layouts.app') @section('title', 'Đối tác Doanh nghiệp')

@section('content')
<div class="bg-slate-50 py-16 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-16">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight mb-4">
                Danh sách <span class="text-emerald-600">Doanh nghiệp Đối tác</span>
            </h1>
            <p class="text-lg text-slate-500 max-w-2xl mx-auto">
                Những đơn vị tiên phong ứng dụng công nghệ Blockchain của MDTrace để minh bạch hóa nguồn gốc, mang sản phẩm an toàn đến tay người tiêu dùng.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($enterprises as $enterprise)
                @php
                    $profile = $enterprise->profile;
                    // Lấy tên công ty từ profile, nếu không có thì lấy tên user
                    $companyName = $profile->company_name ?? $enterprise->name;
                    $address = $profile->address ?? 'Đang cập nhật địa chỉ';
                @endphp
                
                <a href="{{ route('public.enterprises.show', $enterprise->id) }}" class="block bg-white rounded-3xl shadow-sm hover:shadow-xl transition-shadow duration-300 border border-gray-100 overflow-hidden group">
                    <div class="h-32 bg-emerald-50 relative flex items-center justify-center">
                        <i class="fa-solid fa-building text-5xl text-emerald-200 group-hover:scale-110 transition-transform"></i>
                        <div class="w-16 h-16 mx-auto -mt-8 bg-white border-4 border-emerald-50 rounded-full flex items-center justify-center overflow-hidden shadow-sm">
                            @if($enterprise->profile && $enterprise->profile->logo_url)
                                <img src="{{ getSecureImageUrl($enterprise->profile->logo_url) }}" alt="{{ $enterprise->profile->company_name ?? $enterprise->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-2xl font-black text-emerald-500">{{ strtoupper(substr($enterprise->name, 0, 1)) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="p-6 pt-10 text-center">
                        <h3 class="text-xl font-bold text-slate-800 mb-2 line-clamp-1" title="{{ $companyName }}">
                            {{ $companyName }}
                        </h3>
                        <p class="text-sm text-gray-500 mb-4 h-10 line-clamp-2">
                            <i class="fa-solid fa-location-dot text-emerald-500 mr-1"></i> {{ $address }}
                        </p>
                        
                        <div class="pt-4 border-t border-gray-50 flex justify-center">
                            <span class="inline-flex items-center gap-1.5 bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full text-xs font-bold">
                                <i class="fa-solid fa-circle-check text-emerald-500"></i> Đối tác chính thức
                            </span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-20 text-center text-gray-400">
                    <i class="fa-solid fa-box-open text-6xl mb-4 text-gray-200"></i>
                    <h3 class="text-xl font-medium text-gray-500">Chưa có doanh nghiệp nào hoạt động</h3>
                    <p class="mt-2 text-sm">Các đối tác đang trong quá trình xét duyệt hồ sơ.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-12 flex justify-center">
            {{ $enterprises->links() }}
        </div>

    </div>
</div>
@endsection