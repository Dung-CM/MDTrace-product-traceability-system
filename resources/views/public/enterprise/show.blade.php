@extends('layouts.app')

@php
    $profile = $enterprise->profile;
    $companyName = $profile->company_name ?? $enterprise->name;
@endphp

@section('title', 'Hồ sơ: ' . $companyName)

@section('content')
<div class="bg-slate-50 min-h-screen pb-16">
    <div class="w-full h-48 md:h-64 bg-emerald-600 relative flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
        <i class="fa-solid fa-building text-9xl text-emerald-500 opacity-50 absolute -bottom-10 -right-10 transform -rotate-12"></i>
    </div>

    <div class="max-w-4xl mx-auto px-4 -mt-20 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl p-8 border border-gray-100 text-center md:text-left md:flex md:items-start md:gap-8">
            <div class="w-32 h-32 bg-white rounded-full flex items-center justify-center border-4 border-emerald-50 shadow-lg overflow-hidden relative z-10 mx-auto lg:mx-0">
                @if($enterprise->profile && $enterprise->profile->logo_url)
                    <img src="{{ getSecureImageUrl($enterprise->profile->logo_url) }}" alt="Logo" class="w-full h-full object-cover">
                @else
                    <span class="text-5xl font-black text-emerald-500">{{ strtoupper(substr($enterprise->name, 0, 1)) }}</span>
                @endif
            </div>
            <div class="mt-4 md:mt-0 flex-1">
                <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">{{ $companyName }}</h1>
                <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold mb-4">
                    <i class="fa-solid fa-shield-check"></i> Đối tác minh bạch MDTrace
                </div>
                
                @if(!empty($profile->tax_code))
                <p class="text-sm text-gray-500 font-mono font-bold">MST: {{ $profile->tax_code }}</p>
                @endif
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-1 space-y-6">
            
           <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-6 tracking-wide text-sm uppercase">Thông tin liên hệ</h3>
                <ul class="space-y-4">
                    <li class="flex items-start gap-3">
                        <i class="fa-solid fa-location-dot mt-1 text-emerald-500 w-5 text-center"></i>
                        <span class="text-gray-600 text-sm leading-relaxed">{{ $enterprise->profile->address ?? 'Đang cập nhật địa chỉ...' }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-phone text-emerald-500 w-5 text-center"></i>
                        <span class="text-gray-600 text-sm font-medium">{{ $enterprise->profile->phone ?? 'Đang cập nhật...' }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-envelope text-emerald-500 w-5 text-center"></i>
                        <span class="text-gray-600 text-sm">{{ $enterprise->profile->contact_email ?? $enterprise->email }}</span>
                    </li>
                    <li class="flex items-center gap-3">
                        <i class="fa-solid fa-globe text-emerald-500 w-5 text-center"></i>
                        <a href="{{ $enterprise->profile->website_url ?? '#' }}" class="text-blue-500 hover:underline text-sm truncate" target="_blank">
                            {{ $enterprise->profile->website_url ?? 'Chưa có website' }}
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 mb-4 tracking-wide text-sm uppercase">Vị trí doanh nghiệp</h3>
                <div class="w-full h-64 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                    @if($enterprise->profile && $enterprise->profile->map_link)
                        {!! $enterprise->profile->map_link !!}
                    @else
                        <div class="w-full h-full flex flex-col items-center justify-center text-gray-400">
                            <i class="fa-solid fa-map-location-dot text-3xl mb-2"></i>
                            <span class="text-xs">Chưa cập nhật bản đồ</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-bold text-gray-800 text-lg flex items-center gap-2">
                        <i class="fa-solid fa-box-open text-emerald-500"></i> Sản phẩm hiện có
                    </h3>
                    <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">{{ $enterprise->products->count() ?? 0 }} sản phẩm</span>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @forelse($enterprise->products as $product)
                        <a href="{{ route('public.products.show', $product->id) }}" class="group block border border-gray-100 rounded-xl overflow-hidden hover:shadow-md transition">
                            <div class="aspect-square bg-gray-100 relative">
                                <img src="{{ getSecureImageUrl($product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                            </div>
                            <div class="p-3">
                                <h4 class="font-bold text-gray-800 text-sm truncate group-hover:text-emerald-600 transition">{{ $product->name }}</h4>
                            </div>
                        </a>
                    @empty
                        <div class="col-span-full py-8 text-center text-gray-400 bg-gray-50 rounded-xl">
                            <p>Doanh nghiệp này chưa đăng tải sản phẩm nào.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-newspaper text-blue-500"></i> Giới thiệu doanh nghiệp
                </h3>
                <div class="prose max-w-none text-gray-600 text-sm leading-relaxed">
                    @if($enterprise->profile && $enterprise->profile->description)
                        {!! $enterprise->profile->description !!}
                    @else
                        <p class="italic text-gray-400">Doanh nghiệp chưa cập nhật bài viết giới thiệu.</p>
                    @endif
                </div>
            </div>

           <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-certificate text-amber-500"></i> Hồ sơ Pháp lý & Chứng nhận
                </h3>
                @if(!empty($enterprise->profile->company_certificates) && is_array($enterprise->profile->company_certificates))
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @foreach($enterprise->profile->company_certificates as $cert)
                            <a href="{{ getSecureImageUrl($cert) }}" target="_blank" class="block aspect-square bg-gray-50 rounded-lg overflow-hidden border border-gray-200 hover:shadow-md transition">
                                <img src="{{ getSecureImageUrl($cert) }}" alt="Chứng nhận" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="italic text-gray-400 text-sm">Chưa có tài liệu chứng nhận nào được tải lên.</p>
                @endif
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-building text-blue-500"></i> Hình ảnh Cơ sở hoạt động
                </h3>
                @if(!empty($enterprise->profile->company_images) && is_array($enterprise->profile->company_images))
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                        @foreach($enterprise->profile->company_images as $img)
                            <div class="aspect-[4/3] bg-gray-50 rounded-lg overflow-hidden border border-gray-200 shadow-sm group">
                                <img src="{{ getSecureImageUrl($img) }}" alt="Cơ sở hoạt động" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="italic text-gray-400 text-sm">Chưa có hình ảnh cơ sở hoạt động.</p>
                @endif
            </div>

        </div>

    </div>
</div>

<style>
    /* Ép iframe bản đồ tự động tràn viền đẹp mắt */
    iframe {
        width: 100% !important;
        height: 100% !important;
        border: none !important;
    }
</style>
@endsection