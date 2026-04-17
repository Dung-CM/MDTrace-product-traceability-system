<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sổ cái Blockchain - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#0A2540] text-white flex flex-col shadow-xl z-20">
        <div class="h-20 flex items-center px-6 border-b border-white/10">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center font-bold text-lg mr-3">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <span class="text-2xl font-bold tracking-tight">MD<span class="text-emerald-400">Trace</span></span>
        </div>

        <nav class="flex-1 py-6 px-4 space-y-2">
            <a href="{{ route('admin.dashboard.stats') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition shadow-sm {{ Route::is('admin.dashboard.stats') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span class="font-semibold">Tổng quan hệ thống</span>
            </a>
            
            <div x-data="{ open: {{ (Route::is('admin.dashboard') || Route::is('admin.enterprises.active')) ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-building-shield w-5 text-center {{ (Route::is('admin.dashboard') || Route::is('admin.enterprises.active')) ? 'text-emerald-400' : 'group-hover:text-emerald-400' }}"></i>
                        <span class="font-semibold {{ (Route::is('admin.dashboard') || Route::is('admin.enterprises.active')) ? 'text-white' : '' }}">Quản lý Doanh nghiệp</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition.origin.top class="mt-2 ml-6 space-y-1 border-l border-emerald-500/30 pl-4" style="display: {{ (Route::is('admin.dashboard') || Route::is('admin.enterprises.active')) ? 'block' : 'none' }};">
                   <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition {{ Route::is('admin.dashboard') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-solid fa-clipboard-question w-4 text-center {{ Route::is('admin.dashboard') ? 'text-emerald-400' : 'text-amber-500/70' }}"></i>
                        <span>Duyệt hồ sơ mới</span>
                    </a>

                    <a href="{{ route('admin.enterprises.active') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition {{ Route::is('admin.enterprises.active') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-solid fa-building-circle-check w-4 text-center {{ Route::is('admin.enterprises.active') ? 'text-emerald-400' : 'text-blue-400/70' }}"></i>
                        <span>Đang hoạt động</span>
                    </a>
                </div>
            </div>
            
            <div x-data="{ open: {{ (Route::is('admin.categories.*') || Route::is('admin.block_explorer')) ? 'true' : 'false' }} }" class="mt-4">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center group-hover:text-emerald-400 {{ (Route::is('admin.categories.*') || Route::is('admin.block_explorer')) ? 'text-emerald-400' : '' }}"></i>
                        <span class="font-semibold {{ (Route::is('admin.categories.*') || Route::is('admin.block_explorer')) ? 'text-white' : '' }}">Quản lý Sản phẩm</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition.origin.top class="mt-2 ml-6 space-y-1 border-l border-emerald-500/30 pl-4" style="display: {{ (Route::is('admin.categories.*') || Route::is('admin.block_explorer')) ? 'block' : 'none' }};">
                   <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition {{ Route::is('admin.categories.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-solid fa-layer-group w-4 text-center {{ Route::is('admin.categories.*') ? 'text-emerald-400' : 'text-indigo-400/70' }}"></i>
                        <span>Danh mục sản phẩm</span>
                    </a>

                    <a href="{{ route('admin.block_explorer') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition {{ Route::is('admin.block_explorer') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-brands fa-hive w-4 text-center {{ Route::is('admin.block_explorer') ? 'text-emerald-400' : 'text-amber-400/70' }}"></i>
                        <span>Sổ cái Blockchain</span>
                    </a>
                </div>
            </div>
            
            <a href="{{ route('admin.scans.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition mt-4 {{ Route::is('admin.scans.index') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                <span class="font-semibold">Lịch sử quét mã</span>
            </a>

            <a href="{{ route('admin.profile') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition shadow-sm mt-4 {{ Route::is('admin.profile') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-user-gear w-5 text-center"></i>
                <span class="font-semibold">Hồ sơ cá nhân</span>
            </a>
        </nav>
        <div class="p-4 border-t border-white/10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-3 text-gray-400 hover:text-red-400 hover:bg-red-400/10 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-right-from-bracket w-5"></i><span class="font-medium">Đăng xuất</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 z-10">
            <div class="text-xl font-bold text-gray-800">Sổ cái Blockchain</div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-900">Quản trị viên</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@mdtrace.com' }}</div>
                </div>
               <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-emerald-500/20 shadow-sm flex items-center justify-center bg-gray-100">
                    @if(Auth::user()->profile && Auth::user()->profile->logo_url)
                        {{-- Laravel sẽ lấy ảnh từ thư mục storage/app/public --}}
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->profile->logo_url) }}" 
                             alt="Admin Avatar" 
                             class="w-full h-full object-cover">
                    @else
                        {{-- Nếu chưa có ảnh thì hiện icon mặc định --}}
                        <i class="fa-solid fa-user-shield text-gray-500"></i>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-5 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-xl">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                            <i class="fa-solid fa-link text-emerald-600"></i> MDTrace Block Explorer
                            <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-1 rounded-full border border-emerald-200 ml-2">
                                Tổng: {{ $blocks->total() }} khối
                            </span>
                        </h3>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <form action="" method="GET" class="flex gap-2">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm mã Txn Hash..." class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-emerald-500 outline-none w-64 shadow-sm">
                            <button type="submit" class="bg-slate-800 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-slate-700 transition shadow-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </form>
                        <div class="flex items-center gap-2 border-l border-gray-300 pl-4">
                            <span class="flex h-3 w-3 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                            </span>
                            <span class="text-xs text-emerald-700 font-bold uppercase tracking-wider">Live Network</span>
                        </div>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-500 text-[11px] uppercase tracking-wider border-b border-gray-100">
                                <th class="p-4 font-bold text-center w-12">STT</th>
                                <th class="p-4 font-bold">Mã Giao dịch (Txn Hash)</th>
                                <th class="p-4 font-bold">Mã Lô hàng</th>
                                <th class="p-4 font-bold">Sản phẩm</th>
                                <th class="p-4 font-bold">Thời gian lên chuỗi</th>
                                <th class="p-4 font-bold text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse($blocks as $block)
                            <tr class="hover:bg-slate-50 transition cursor-default">
                                <td class="p-4 text-center text-gray-500 font-medium">{{ $blocks->firstItem() + $loop->index }}</td>
                                <td class="p-4 font-mono text-emerald-600 font-bold text-xs">
                                    <div class="flex items-center gap-2" title="{{ $block->transaction_hash }}">
                                        <i class="fa-brands fa-hive text-gray-300"></i> 
                                        {{ substr($block->transaction_hash, 0, 16) }}...
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-slate-700 text-xs">{{ $block->batch->batch_code ?? 'N/A' }}</td>
                                <td class="p-4 text-gray-600 text-xs truncate max-w-[200px]" title="{{ $block->batch->product->name ?? '' }}">
                                    {{ $block->batch->product->name ?? 'Dữ liệu trống' }}
                                </td>
                                <td class="p-4 text-gray-500 text-[11px] font-medium">{{ $block->created_at->format('H:i:s d/m/Y') }}</td>
                                <td class="p-4 text-center">
                                    <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-3 py-1 rounded-full border border-emerald-200">ĐÃ MINT</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="p-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fa-solid fa-boxes-packing text-4xl opacity-20"></i>
                                        <p class="italic text-sm">Sổ cái hiện đang trống. Chưa có lô hàng nào được đóng gói lên chuỗi.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($blocks->hasPages())
                <div class="p-4 border-t border-gray-100">
                    {{ $blocks->links() }}
                </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>