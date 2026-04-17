<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tổng quan hệ thống - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#0A2540] text-white flex flex-col shadow-xl z-20">
        <div class="h-20 flex items-center px-6 border-b border-white/10">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center font-bold text-lg mr-3"><i class="fa-solid fa-qrcode"></i></div>
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
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    <span class="font-medium">Đăng xuất</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
          <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 z-10">
            <div class="text-xl font-bold text-gray-800">Doanh nghiệp chờ duyệt</div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-900">Quản trị viên</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-emerald-500/20 shadow-sm flex items-center justify-center bg-gray-100">
                    @if(Auth::user()->profile && Auth::user()->profile->logo_url)
                        {{-- Dùng asset kết hợp với storage để đảm bảo đường dẫn tuyệt đối --}}
                        <img src="{{ asset('storage/' . Auth::user()->profile->logo_url) }}" 
                             alt="Admin Avatar" 
                             class="w-full h-full object-cover">
                    @else
                        {{-- Hiện icon nếu không có ảnh hoặc profile bị null --}}
                        <i class="fa-solid fa-user-shield text-gray-400"></i>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Tổng Doanh nghiệp</p>
                        <h3 class="text-3xl font-black text-gray-800">{{ $totalEnterprises }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center text-2xl"><i class="fa-solid fa-building"></i></div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Chờ xét duyệt</p>
                        <h3 class="text-3xl font-black text-amber-600">{{ $pendingCount }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center text-2xl"><i class="fa-solid fa-hourglass-half"></i></div>
                </div>
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-1">Đang bị khóa</p>
                        <h3 class="text-3xl font-black text-red-600">{{ $lockedCount }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-red-50 text-red-500 rounded-full flex items-center justify-center text-2xl"><i class="fa-solid fa-lock"></i></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2"><i class="fa-solid fa-chart-pie text-emerald-500"></i> Tỷ trọng Danh mục Sản phẩm</h3>
                    <div class="relative h-64 w-full flex items-center justify-center">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                    <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2"><i class="fa-solid fa-ranking-star text-amber-500"></i> Doanh nghiệp tương tác gần nhất</h3>
                    <div class="space-y-4">
                        @forelse($topEnterprises as $index => $ent)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-gray-50 border border-gray-100 hover:bg-emerald-50 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold {{ $index == 0 ? 'bg-amber-100 text-amber-600' : ($index == 1 ? 'bg-gray-200 text-gray-600' : 'bg-orange-50 text-orange-600') }}">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800">{{ $ent->profile->company_name ?? $ent->email }}</h4>
                                        <p class="text-xs text-gray-500">Truy cập: {{ $ent->last_seen_at ? \Carbon\Carbon::parse($ent->last_seen_at)->diffForHumans() : 'Chưa rõ' }}</p>
                                    </div>
                                </div>
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold">Active</span>
                            </div>
                        @empty
                            <p class="text-center text-gray-500 py-4">Chưa có dữ liệu doanh nghiệp.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <h3 class="font-bold text-gray-800 mb-6 flex items-center gap-2"><i class="fa-solid fa-clock-rotate-left text-blue-500"></i> Luồng sự kiện hệ thống</h3>
                <div class="space-y-6 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
                    @foreach($recentActivities as $activity)
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-slate-200 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                                <i class="fa-solid {{ $activity->status == 'active' ? 'fa-check text-emerald-500' : ($activity->status == 'locked' ? 'fa-lock text-orange-500' : 'fa-plus text-blue-500') }}"></i>
                            </div>
                            <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                <div class="flex items-center justify-between mb-1">
                                    <div class="font-bold text-gray-800">{{ $activity->profile->company_name ?? $activity->email }}</div>
                                    <time class="font-medium text-xs text-gray-500">{{ $activity->updated_at->diffForHumans() }}</time>
                                </div>
                                <div class="text-sm text-gray-600">Trạng thái hiện tại: <strong class="uppercase">{{ $activity->status }}</strong></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ctx = document.getElementById('statusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                   datasets: [{
                        data: {!! json_encode($chartData) !!},
                        backgroundColor: [
                            '#10b981', // Xanh Ngọc (Emerald)
                            '#f59e0b', // Cam Vàng (Amber)
                            '#ef4444', // Đỏ (Red)
                            '#3b82f6', // Xanh Dương (Blue)
                            '#8b5cf6', // Tím (Violet)
                            '#ec4899', // Hồng (Pink)
                            '#06b6d4', // Xanh Lơ (Cyan)
                            '#f97316', // Cam Đậm (Orange)
                            '#84cc16', // Xanh Đọt Chuối (Lime)
                            '#64748b'  // Xám (Slate)
                            // Nếu $chartLabels == 'Chưa có sản phẩm nào' thì nó lấy màu xám nhạt
                        ].slice(0, {!! json_encode(count($chartLabels)) !!}), // Cắt đúng số lượng màu cần thiết
                        borderWidth: 2, // Thêm viền trắng mỏng cho đẹp
                        borderColor: '#ffffff',
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</body>
</html>