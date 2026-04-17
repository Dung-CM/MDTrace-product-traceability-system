<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lịch sử truy xuất - MDTrace</title>
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
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition shadow-sm text-gray-400 hover:text-white hover:bg-white/5">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span class="font-semibold">Tổng quan hệ thống</span>
            </a>
            
            <div x-data="{ open: false }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-building-shield w-5 text-center group-hover:text-emerald-400"></i>
                        <span class="font-semibold">Quản lý Doanh nghiệp</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition.origin.top class="mt-2 ml-6 space-y-1 border-l border-emerald-500/30 pl-4" style="display: none;">
                   <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition text-gray-400 hover:text-white">
                        <i class="fa-solid fa-clipboard-question w-4 text-center text-amber-500/70"></i>
                        <span>Duyệt hồ sơ mới</span>
                    </a>

                    <a href="{{ route('admin.enterprises.active') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition text-gray-400 hover:text-white">
                        <i class="fa-solid fa-building-circle-check w-4 text-center text-blue-400/70"></i>
                        <span>Đang hoạt động</span>
                    </a>
                </div>
            </div>
            
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition shadow-sm mt-4">
                <i class="fa-solid fa-layer-group w-5 text-center"></i><span class="font-semibold">Danh mục sản phẩm</span>
            </a>
            
            <a href="{{ route('admin.scans.index') }}" class="flex items-center gap-3 bg-emerald-600 text-white shadow-sm px-4 py-3 rounded-xl transition mt-4">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                <span class="font-semibold">Lịch sử quét mã</span>
            </a>

            <a href="{{ route('admin.profile') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition shadow-sm mt-4 text-gray-400 hover:text-white hover:bg-white/5">
                <i class="fa-solid fa-user-gear w-5 text-center"></i><span class="font-semibold">Hồ sơ cá nhân</span>
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-3 text-gray-400 hover:text-red-400 hover:bg-red-400/10 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    <span class="font-medium">Đăng xuất</span>
                </button>
            </form>
        </div>
    </aside>
    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 z-10">
            <div class="text-xl font-bold text-gray-800">Lịch sử truy xuất hệ thống</div>
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
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                   <div>
                        <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3">
                            Lịch sử truy xuất hệ thống
                            <span class="bg-emerald-100 text-emerald-700 text-sm font-bold px-3 py-1 rounded-full border border-emerald-200">
                                {{ $scanHistories->total() }} lượt quét
                            </span>
                        </h2>
                        <p class="text-sm text-gray-500 mt-1">Giám sát các lượt quét mã QR từ người tiêu dùng.</p>
                    </div>
                    
                    <form action="{{ route('admin.scans.index') }}" method="GET" class="flex flex-wrap items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                        
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-building"></i>
                            </div>
                            <select name="enterprise_id" class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 outline-none w-48 text-gray-600 bg-white appearance-none cursor-pointer">
                                <option value="">Tất cả doanh nghiệp</option>
                                @foreach($enterprises as $ent)
                                    <option value="{{ $ent->id }}" {{ request('enterprise_id') == $ent->id ? 'selected' : '' }}>
                                        {{ $ent->profile->company_name ?? $ent->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                                <i class="fa-solid fa-mobile-screen"></i>
                            </div>
                            <select name="device_type" class="border border-gray-300 rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 outline-none w-40 text-gray-600 bg-white appearance-none cursor-pointer">
                                <option value="">Mọi thiết bị</option>
                                <option value="Android" {{ request('device_type') == 'Android' ? 'selected' : '' }}>Android</option>
                                <option value="iPhone" {{ request('device_type') == 'iPhone' ? 'selected' : '' }}>Apple iOS</option>
                                <option value="Windows" {{ request('device_type') == 'Windows' ? 'selected' : '' }}>Windows PC</option>
                                <option value="Macintosh" {{ request('device_type') == 'Macintosh' ? 'selected' : '' }}>Mac OS</option>
                            </select>
                        </div>

                        <div class="relative flex-1 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Nhập IP hoặc tên sản phẩm..." class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-emerald-500 focus:border-emerald-500 outline-none shadow-sm">
                        </div>

                        <button type="submit" class="bg-emerald-500 text-white px-5 py-2 rounded-lg text-sm font-semibold hover:bg-emerald-600 transition shadow-sm flex items-center gap-2">
                            <i class="fa-solid fa-filter"></i> Lọc
                        </button>

                        @if(request()->anyFilled(['enterprise_id', 'device_type', 'search']))
                            <a href="{{ route('admin.scans.index') }}" class="text-sm text-gray-500 hover:text-red-500 font-medium underline transition">Xóa bộ lọc</a>
                        @endif
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gray-50 text-gray-600">
                            <tr>
                                <th class="px-4 py-3 font-semibold rounded-tl-lg text-center w-16">STT</th>
                                
                                <th class="px-4 py-3 font-semibold">Thời gian</th>
                                <th class="px-4 py-3 font-semibold">Sản phẩm quét</th>
                                <th class="px-4 py-3 font-semibold">Doanh nghiệp</th>
                                <th class="px-4 py-3 font-semibold">Thiết bị (User Agent)</th>
                                <th class="px-4 py-3 font-semibold rounded-tr-lg">Địa chỉ IP</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($scanHistories as $log)
                            <tr class="hover:bg-gray-50 transition">
                                
                                <td class="px-4 py-3 text-center text-gray-500 font-medium">
                                    {{ $scanHistories->firstItem() + $loop->index }}
                                </td>

                                <td class="px-4 py-3 text-gray-600 font-medium">
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s d/m/Y') }}
                                </td>
                                <td class="px-4 py-3 font-bold text-emerald-700">
                                    {{ $log->batch->product->name ?? 'Sản phẩm đã xóa' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    {{ $log->batch->product->user->profile->company_name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500 max-w-[250px] truncate" title="{{ $log->device_info }}">
                                    <i class="fa-solid fa-mobile-screen mr-1"></i> {{ $log->device_info ?? 'Không xác định' }}
                                </td>
                                <td class="px-4 py-3 font-mono text-xs text-gray-500">
                                    <span class="bg-gray-100 px-2 py-1 rounded border border-gray-200">{{ $log->ip_address }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-gray-500">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fa-solid fa-qrcode text-4xl opacity-20"></i>
                                        <p class="italic">Hệ thống chưa ghi nhận lượt quét nào.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($scanHistories->hasPages())
                <div class="mt-6 pt-4 border-t border-gray-100">
                    {{ $scanHistories->links() }}
                </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>