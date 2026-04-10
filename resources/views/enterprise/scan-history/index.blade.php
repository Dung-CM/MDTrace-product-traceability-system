@extends('enterprise.layouts.app')

@section('title', 'Lịch sử Quét mã QR')

@section('content')
@php
    // Hàm siêu nhỏ giúp nhận diện Hệ điều hành & Bọ dò tìm
    function detectOS($userAgent) {
        $ua = strtolower($userAgent);
        
        // 1. Lọc bọn Bot/Crawler của mạng xã hội (Zalo, Facebook, Google)
        if (str_contains($ua, 'zalo') || str_contains($ua, 'bot') || str_contains($ua, 'facebookexternalhit')) {
            return ['name' => 'Bot Quét tự động (Zalo/FB)', 'icon' => 'fa-solid fa-robot', 'color' => 'text-purple-500'];
        }
        
        // 2. Lọc thiết bị người dùng thật
        if (str_contains($ua, 'iphone') || str_contains($ua, 'ipad')) return ['name' => 'Apple iOS', 'icon' => 'fa-brands fa-apple', 'color' => 'text-gray-800'];
        if (str_contains($ua, 'android')) return ['name' => 'Android', 'icon' => 'fa-brands fa-android', 'color' => 'text-green-500'];
        if (str_contains($ua, 'windows')) return ['name' => 'Windows PC', 'icon' => 'fa-brands fa-windows', 'color' => 'text-blue-500'];
        if (str_contains($ua, 'mac os')) return ['name' => 'MacBook', 'icon' => 'fa-brands fa-apple', 'color' => 'text-gray-800'];
        
        return ['name' => 'Thiết bị khác', 'icon' => 'fa-solid fa-mobile-screen', 'color' => 'text-gray-400'];
    }
@endphp

<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Lịch sử Quét mã</h2>
            <p class="text-sm text-gray-500 mt-1">Theo dõi tương tác của người tiêu dùng với sản phẩm</p>
        </div>
        
        <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-2xl p-4 text-white shadow-lg flex items-center gap-4 min-w-[250px]">
            <div class="bg-white/20 p-3 rounded-xl">
                <i class="fa-solid fa-qrcode text-2xl"></i>
            </div>
            <div>
                <p class="text-emerald-50 text-sm font-medium">Tổng lượt quét</p>
                <h3 class="text-3xl font-bold">{{ number_format($totalScans) }} <span class="text-base font-normal">lượt</span></h3>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Thời gian quét</th>
                        <th class="px-6 py-4 font-semibold">Sản phẩm / Lô hàng</th>
                        <th class="px-6 py-4 font-semibold">Hệ điều hành</th>
                        <th class="px-6 py-4 font-semibold">Địa chỉ IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($histories as $history)
                    @php $os = detectOS($history->device_info); @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="block font-bold text-gray-800">{{ \Carbon\Carbon::parse($history->scanned_at)->format('H:i:s') }}</span>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($history->scanned_at)->format('d/m/Y') }}</span>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="block font-medium text-gray-800">{{ $history->batch->product->name ?? 'N/A' }}</span>
                            <span class="text-xs font-bold text-emerald-600">Lô: {{ $history->batch->batch_code }}</span>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2 font-medium {{ $os['color'] }}">
                                <i class="{{ $os['icon'] }} text-lg"></i>
                                {{ $os['name'] }}
                            </div>
                        </td>

                        <td class="px-6 py-4 font-mono text-gray-600 text-xs">
                            {{ $history->ip_address ?? 'Không xác định' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                            <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 text-gray-300 block"></i>
                            Chưa có dữ liệu quét mã nào.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($histories->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $histories->links() }}
        </div>
        @endif
    </div>
</div>
@endsection