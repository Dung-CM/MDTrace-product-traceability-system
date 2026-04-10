@extends('enterprise.layouts.app')

@section('title', 'Quản lý Lô hàng')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <form action="{{ route('enterprise.batches.index') }}" method="GET" class="w-full md:w-1/3 relative">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm theo mã lô hoặc tên sản phẩm..." 
                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition shadow-sm">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
        </form>

        <a href="{{ route('enterprise.batches.create') }}" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-semibold transition flex items-center gap-2 shadow-sm whitespace-nowrap">
            <i class="fa-solid fa-layer-group"></i> Tạo Lô Hàng Mới
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-100 text-emerald-700 p-4 rounded-xl flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-600 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Mã Lô</th>
                        <th class="px-6 py-4 font-semibold">Sản Phẩm</th>
                        <th class="px-6 py-4 font-semibold">Ngày SX - Hạn SD</th>
                        <th class="px-6 py-4 font-semibold text-center">Số lượng</th>
                        <th class="px-6 py-4 font-semibold text-center">Mã QR</th>
                        <th class="px-6 py-4 font-semibold text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($batches as $batch)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-bold text-emerald-700">{{ $batch->batch_code }}</td>
                        <td class="px-6 py-4 font-medium text-gray-800">
                            {{ $batch->product->name ?? 'Sản phẩm đã bị xóa' }}
                        </td>
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            <span class="block">SX: {{ date('d/m/Y', strtotime($batch->manufacturing_date)) }}</span>
                            <span class="block text-red-500">SD: {{ date('d/m/Y', strtotime($batch->expiry_date)) }}</span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-700">{{ number_format($batch->quantity) }}</td>
                        
                        <td class="px-6 py-4 text-center">
                            @if($batch->qr_code_url)
                            <div class="flex flex-col items-center justify-center gap-2">
                                <img src="{{ asset('storage/' . $batch->qr_code_url) }}" class="w-10 h-10 border border-gray-200 rounded p-1 bg-white">
                                <a href="{{ route('enterprise.batches.download_qr', $batch->id) }}" class="text-[10px] bg-emerald-100 hover:bg-emerald-200 text-emerald-700 px-2 py-1 rounded font-bold transition">
                                    <i class="fa-solid fa-download"></i> Tải QR
                                </a>
                            </div>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <form action="{{ route('enterprise.batches.destroy', $batch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lô hàng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition shadow-sm" title="Xóa Lô Hàng">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <i class="fa-solid fa-qrcode text-4xl mb-3 text-gray-300 block"></i>
                            Chưa có Lô hàng nào. Hãy tạo lô hàng để sinh mã QR truy xuất!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($batches->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $batches->links() }}
        </div>
        @endif
    </div>
</div>
@endsection