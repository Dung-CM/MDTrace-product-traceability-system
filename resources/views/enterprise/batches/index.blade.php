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

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-xl flex items-center gap-2 shadow-sm mb-4">
            <i class="fa-solid fa-triangle-exclamation"></i> 
            <span class="font-medium">{{ session('error') }}</span>
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
                            <div class="flex items-center justify-center gap-3">
                                
                                @php
                                    $transaction = \App\Models\BlockchainTransaction::where('batch_id', $batch->id)->first();
                                @endphp

                                @if($transaction)
                                    <div class="flex flex-col items-center">
                                        <span class="bg-emerald-100 text-emerald-700 text-xs px-2 py-1 rounded font-bold mb-1 border border-emerald-200 shadow-sm cursor-help" title="Mã Hash: {{ $transaction->transaction_hash }}">
                                            <i class="fa-solid fa-shield-check"></i> Đã lên chuỗi
                                        </span>
                                        <code class="text-[10px] text-gray-500">{{ substr($transaction->transaction_hash, 0, 10) }}...</code>
                                    </div>
                                @else
                                    <div x-data="{
                                        showModal: false,
                                        isMinting: false,
                                        progress: 0,
                                        startMinting(formId) {
                                            this.isMinting = true;
                                            let interval = setInterval(() => {
                                                this.progress += Math.floor(Math.random() * 15) + 5;
                                                if (this.progress >= 100) {
                                                    this.progress = 100;
                                                    clearInterval(interval);
                                                    setTimeout(() => {
                                                        document.getElementById(formId).submit();
                                                    }, 500);
                                                }
                                            }, 300);
                                        }
                                    }">
                                        <button @click="showModal = true" class="w-8 h-8 flex items-center justify-center bg-[#0A2540] text-white hover:bg-emerald-500 rounded-lg transition shadow-sm" title="Đóng gói & Lên chuỗi">
                                            <i class="fa-solid fa-cube"></i>
                                        </button>

                                        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" style="display: none;" x-transition>
                                            <div class="bg-white rounded-3xl shadow-2xl w-[400px] overflow-hidden p-8 relative whitespace-normal text-left" @click.away="!isMinting && (showModal = false)">
                                                
                                                <div x-show="!isMinting" x-transition>
                                                    <div class="text-center mb-6">
                                                        <div class="w-20 h-20 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center text-4xl mx-auto mb-4 border-4 border-amber-100">
                                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                                        </div>
                                                        <h3 class="font-bold text-xl text-gray-800 mb-2">Cảnh báo Bất biến</h3>
                                                        <p class="text-gray-600 text-sm leading-relaxed">
                                                            Sau khi đưa lên chuỗi khối, toàn bộ dữ liệu lô <b>{{ $batch->batch_code }}</b> sẽ bị khóa vĩnh viễn. Bạn đã kiểm tra kỹ thông tin chưa?
                                                        </p>
                                                    </div>
                                                    <div class="flex justify-center gap-3">
                                                        <button type="button" @click="showModal = false" class="px-5 py-2.5 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition">Hủy bỏ</button>
                                                        <button type="button" @click="startMinting('mint-form-{{ $batch->id }}')" class="px-5 py-2.5 bg-[#0A2540] text-white font-bold rounded-xl shadow-lg hover:bg-emerald-600 transition flex items-center gap-2">
                                                            <i class="fa-solid fa-link"></i> Xác nhận Lên chuỗi
                                                        </button>
                                                    </div>
                                                </div>

                                                <div x-show="isMinting" style="display: none;" x-transition>
                                                    <div class="text-center mb-8">
                                                        <div class="w-20 h-20 bg-[#0A2540] text-emerald-400 rounded-full flex items-center justify-center text-4xl mx-auto mb-4 border-4 border-gray-100 shadow-inner">
                                                            <i class="fa-solid fa-gear fa-spin"></i>
                                                        </div>
                                                        <h3 class="font-bold text-lg text-gray-800 mb-1 animate-pulse">Đang đẩy lên IPFS...</h3>
                                                        <p class="text-gray-500 text-xs font-mono">Đang tạo hàm băm SHA-256</p>
                                                    </div>
                                                    <div class="w-full bg-gray-100 rounded-full h-3 mb-3 overflow-hidden shadow-inner">
                                                        <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-3 rounded-full transition-all duration-300 ease-out relative" :style="'width: ' + progress + '%'"></div>
                                                    </div>
                                                    <div class="flex justify-between items-center text-sm">
                                                        <span class="text-gray-400 font-mono text-xs">Mã hóa khối dữ liệu</span>
                                                        <span class="font-bold text-emerald-600 font-mono" x-text="progress + '%'"></span>
                                                    </div>
                                                </div>

                                                <form id="mint-form-{{ $batch->id }}" action="{{ route('enterprise.batches.mint', $batch->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <form action="{{ route('enterprise.batches.destroy', $batch->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa lô hàng này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-8 h-8 flex items-center justify-center bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition shadow-sm" title="Xóa Lô Hàng">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                <a href="{{ route('enterprise.batches.edit', $batch->id) }}" class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition shadow-sm" title="Chỉnh sửa Lô hàng">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
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