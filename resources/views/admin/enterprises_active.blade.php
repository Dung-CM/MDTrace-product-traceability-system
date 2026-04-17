<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý hoạt động - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
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
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    <span class="font-medium">Đăng xuất</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 z-10">
            <div class="text-xl font-bold text-gray-800">Quản lý doanh nghiệp hoạt động</div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-900">Quản trị viên</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 text-gray-600 font-bold overflow-hidden border-2 border-emerald-500 shadow-sm">
                    @if(Auth::user()->profile && Auth::user()->profile->logo_url)
                        {{-- NẾU CÓ ẢNH: Đảm bảo đường dẫn asset() khớp với nơi bạn lưu ảnh (storage hoặc public) --}}
                        <img src="{{ asset('storage/' . Auth::user()->profile->logo_url) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        {{-- NẾU KHÔNG CÓ ẢNH: Hiện chữ cái đầu tiên của Tên hoặc Email --}}
                        {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 1)) }}
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            
            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h2 class="font-bold text-gray-800 text-lg"><i class="fa-solid fa-users-gear mr-2 text-blue-500"></i> Danh sách hoạt động</h2>
                    <span class="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $enterprises->count() }} tài khoản
                    </span>
                </div>
                
                    <!-- <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                                    <th class="px-6 py-4 font-semibold">Doanh nghiệp</th>
                                    <th class="px-6 py-4 font-semibold">Tài khoản Email</th>
                                    <th class="px-6 py-4 font-semibold">Trạng thái</th>
                                    <th class="px-6 py-4 font-semibold">Hoạt động gần nhất</th>
                                    <th class="px-6 py-4 font-semibold text-center">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($enterprises as $enterprise)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-800">{{ $enterprise->profile->company_name ?? 'Chưa cập nhật' }}</div>
                                            <div class="text-xs text-gray-500 mt-1">MST: {{ $enterprise->profile->tax_code ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600 font-medium">{{ $enterprise->email }}</td>
                                        <td class="px-6 py-4">
                                            @if($enterprise->status === 'active')
                                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold flex items-center w-max gap-1"><i class="fa-solid fa-circle text-[8px]"></i> Active</span>
                                            @else
                                                <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold flex items-center w-max gap-1"><i class="fa-solid fa-lock text-[10px]"></i> Locked</span>
                                            @endif
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-sm">
                                        {{ $enterprise->last_seen_at ? \Carbon\Carbon::parse($enterprise->last_seen_at)->diffForHumans() : 'Chưa ghi nhận' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            <button type="button" onclick="openLockModal({{ $enterprise->id }}, '{{ $enterprise->profile->company_name ?? $enterprise->email }}')" 
                                                    class="bg-orange-500 hover:bg-orange-600 text-white px-3 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                                                <i class="fa-solid fa-user-lock"></i> Khóa
                                            </button>

                                            <button type="button" onclick="openDeleteModal({{ $enterprise->id }}, '{{ $enterprise->profile->company_name ?? $enterprise->email }}')" 
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-semibold transition shadow-sm">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">Chưa có doanh nghiệp nào đang hoạt động.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div> -->
                <div class="p-6 bg-slate-50">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @forelse($enterprises as $enterprise)
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 overflow-hidden flex flex-col group relative">
                            
                            <div class="h-1.5 w-full {{ $enterprise->status === 'active' ? 'bg-emerald-500' : 'bg-orange-500' }}"></div>

                            <div class="p-6 flex-1 flex flex-col">
                                <div class="flex gap-4 items-start mb-5">
                                    <div class="w-14 h-14 rounded-2xl bg-slate-100 shadow-inner flex items-center justify-center overflow-hidden shrink-0 border border-gray-100">
                                            @if($enterprise->profile && $enterprise->profile->logo_url)
                                                {{-- NẾU CÓ ẢNH: Lấy đúng logo của Doanh nghiệp đang xét trong vòng lặp --}}
                                                <img src="{{ asset('storage/' . $enterprise->profile->logo_url) }}" alt="Logo" class="w-full h-full object-cover">
                                            @else
                                                {{-- Fallback: Lấy chữ cái đầu tiên --}}
                                                <span class="text-xl font-black text-slate-400 uppercase">
                                                    {{ substr($enterprise->profile->company_name ?? $enterprise->email ?? 'A', 0, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                    
                                    <div class="flex-1 overflow-hidden">
                                        <h3 class="font-bold text-gray-900 text-base line-clamp-2 leading-tight mb-1" title="{{ $enterprise->profile->company_name ?? 'Chưa cập nhật' }}">
                                            {{ $enterprise->profile->company_name ?? 'Chưa cập nhật' }}
                                        </h3>
                                        <span class="inline-block bg-gray-100 text-gray-600 px-2 py-0.5 rounded text-[10px] font-bold tracking-wider">
                                            MST: {{ $enterprise->profile->tax_code ?? 'N/A' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-3 mb-6 flex-1">
                                    <div class="flex items-center gap-3 text-sm text-gray-600 bg-slate-50 p-3 rounded-xl border border-gray-100">
                                        <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-blue-500 shrink-0">
                                            <i class="fa-solid fa-envelope"></i>
                                        </div>
                                        <span class="truncate font-medium" title="{{ $enterprise->email }}">{{ $enterprise->email }}</span>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mt-2 px-1">
                                        @if($enterprise->status === 'active')
                                            <span class="text-emerald-600 text-xs font-bold flex items-center gap-1.5">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Hoạt động
                                            </span>
                                        @else
                                            <span class="text-orange-600 text-xs font-bold flex items-center gap-1.5">
                                                <i class="fa-solid fa-lock text-[10px]"></i> Bị khóa
                                            </span>
                                        @endif
                                        
                                        <span class="text-[11px] text-gray-400 flex items-center gap-1 font-medium" title="Truy cập gần nhất">
                                            <i class="fa-regular fa-clock"></i> 
                                            {{ $enterprise->last_seen_at ? \Carbon\Carbon::parse($enterprise->last_seen_at)->diffForHumans() : 'Chưa ghi nhận' }}
                                        </span>
                                    </div>
                                </div>

                                    <div class="grid grid-cols-2 gap-3 pt-4 border-t border-gray-100 mt-auto">
                                        <button type="button" onclick="openLockModal({{ $enterprise->id }}, '{{ $enterprise->profile->company_name ?? $enterprise->email }}')" 
                                                class="flex items-center justify-center gap-2 bg-orange-50 hover:bg-orange-500 text-orange-600 hover:text-white border border-orange-100 hover:border-orange-500 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">
                                            <i class="fa-solid fa-user-lock"></i> Khóa
                                        </button>

                                        <button type="button" onclick="openDeleteModal({{ $enterprise->id }}, '{{ $enterprise->profile->company_name ?? $enterprise->email }}')" 
                                                class="flex items-center justify-center gap-2 bg-red-50 hover:bg-red-500 text-red-600 hover:text-white border border-red-100 hover:border-red-500 py-2.5 rounded-xl text-sm font-bold transition-all duration-300">
                                            <i class="fa-solid fa-trash-can"></i> Xóa
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-20 text-center bg-white rounded-3xl border-2 border-dashed border-gray-200">
                                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5 text-4xl text-gray-300">
                                    <i class="fa-regular fa-folder-open"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-800 mb-2">Chưa có dữ liệu</h3>
                                <p class="text-gray-500">Hiện tại chưa có doanh nghiệp nào đang hoạt động trên hệ thống.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="lockModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900"><i class="fa-solid fa-user-lock text-orange-500 mr-2"></i>Thiết lập hình phạt Khóa</h3>
                <button onclick="closeLockModal()" class="text-gray-400 hover:text-red-500 text-xl"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <p class="text-sm text-gray-500 mb-5">Đang xử lý tài khoản: <strong id="lockCompanyName" class="text-gray-800 text-base"></strong></p>

            <form id="lockForm" method="POST" action="">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Thời gian khóa</label>
                        <select name="duration" class="w-full px-4 py-2 border rounded-xl focus:ring-orange-500 focus:outline-none bg-gray-50">
                            <option value="3">3 ngày (Cảnh cáo nhẹ)</option>
                            <option value="7">7 ngày (Vi phạm quy định)</option>
                            <option value="30">30 ngày (Vi phạm nghiêm trọng)</option>
                            <option value="permanent">Vĩnh viễn (Trục xuất khỏi hệ thống)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Lý do khóa <span class="text-red-500">*</span></label>
                        <textarea name="lock_reason" rows="3" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500" placeholder="VD: Vi phạm quy định về nguồn gốc sản phẩm..."></textarea>
                    </div>
                </div>
                <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeLockModal()" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-xl transition">Hủy bỏ</button>
                    <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-xl font-semibold transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-lock"></i> Xác nhận Khóa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 border-t-4 border-red-500">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-xl font-bold text-red-600">Xác nhận xóa tài khoản</h3>
                <button onclick="closeDeleteModal()" class="text-gray-400 hover:text-red-500 text-xl"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <p class="text-sm text-gray-500 mb-4">Lưu ý: Tài khoản <strong id="deleteCompanyName" class="text-gray-800"></strong> sẽ bị ẩn nhưng dữ liệu sản phẩm vẫn được giữ lại để đối soát Blockchain.</p>
            
            <form id="deleteForm" method="POST" action="">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lý do xóa vĩnh viễn <span class="text-red-500">*</span></label>
                    <textarea name="delete_reason" rows="3" required class="w-full px-4 py-3 border border-red-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-red-500" placeholder="Ghi chú lý do xóa để lưu hồ sơ..."></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeDeleteModal()" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-xl transition">Quay lại</button>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-red-200 transition flex items-center gap-2">
                        <i class="fa-solid fa-trash"></i> Đồng ý Xóa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Mở/Đóng Modal Khóa
        function openLockModal(id, companyName) {
            document.getElementById('lockModal').classList.remove('hidden');
            document.getElementById('lockModal').classList.add('flex');
            document.getElementById('lockCompanyName').innerText = companyName;
            document.getElementById('lockForm').action = '/admin/enterprise/' + id + '/lock';
        }

        function closeLockModal() {
            document.getElementById('lockModal').classList.add('hidden');
            document.getElementById('lockModal').classList.remove('flex');
        }

        // Mở/Đóng Modal Xóa
        function openDeleteModal(id, companyName) {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
            document.getElementById('deleteCompanyName').innerText = companyName;
            document.getElementById('deleteForm').action = '/admin/enterprise/' + id + '/delete';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }
    </script>
</body>
</html>