<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ Admin - MDTrace</title>
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
            class="flex items-center gap-3 {{ Route::is('admin.dashboard.stats') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }} px-4 py-3 rounded-xl transition shadow-sm">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span class="font-semibold">Tổng quan hệ thống</span>
            </a>
            <div x-data="{ open: {{ (Route::is('admin.dashboard') || Route::is('admin.enterprises.active')) ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-building-shield w-5 text-center group-hover:text-emerald-400"></i>
                        <span class="font-semibold">Quản lý Doanh nghiệp</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition.origin.top class="mt-2 ml-6 space-y-1 border-l border-emerald-500/30 pl-4">
                   <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-2 py-2 text-sm {{ Route::is('admin.dashboard') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }} transition">
                        <i class="fa-solid fa-clipboard-question w-4 text-center {{ Route::is('admin.dashboard') ? 'text-emerald-400' : 'text-amber-500/70' }}"></i>
                        <span>Duyệt hồ sơ mới</span>
                    </a>

                    <a href="{{ route('admin.enterprises.active') }}" 
                       class="flex items-center gap-2 py-2 text-sm {{ Route::is('admin.enterprises.active') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }} transition">
                        <i class="fa-solid fa-building-circle-check w-4 text-center {{ Route::is('admin.enterprises.active') ? 'text-emerald-400' : 'text-blue-400/70' }}"></i>
                        <span>Đang hoạt động</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition">
                <i class="fa-solid fa-layer-group w-5 text-center"></i>
                <span class="font-semibold">Danh mục sản phẩm</span>
            </a>
             <a href="{{ route('admin.profile') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition shadow-sm mt-4 {{ Route::is('admin.profile') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
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
            <div class="text-xl font-bold text-gray-800">Cài đặt tài khoản</div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">Quản trị viên</div>
                </div>
                <div class="w-10 h-10 rounded-full flex items-center justify-center bg-gray-200 text-gray-600 font-bold overflow-hidden border-2 border-emerald-500 shadow-sm">
                    @if(Auth::user()->profile && Auth::user()->profile->logo_url)
                        <img src="{{ asset('storage/' . Auth::user()->profile->logo_url) }}" alt="Avatar" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 1)) }}
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            
            <div class="max-w-4xl mx-auto">
                @if(session('success'))
                    <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                        <i class="fa-solid fa-circle-check text-xl"></i><span class="font-medium">{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error') || $errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                        <span class="font-medium">{{ session('error') ?? $errors->first() }}</span>
                    </div>
                @endif

                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-gray-800 text-xl"><i class="fa-solid fa-address-card text-emerald-500 mr-2"></i> Thông tin quản trị</h2>
                    </div>

                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                            
                            <div class="flex flex-col items-center space-y-4">
                                <div class="relative group cursor-pointer">
                                    <div class="w-40 h-40 rounded-full border-4 border-gray-100 shadow-lg overflow-hidden flex items-center justify-center bg-gray-50 relative z-10">
                                        <img id="avatarPreview" src="{{ (Auth::user()->profile && Auth::user()->profile->logo_url) ? asset('storage/' . Auth::user()->profile->logo_url) : '' }}" class="w-full h-full object-cover {{ (Auth::user()->profile && Auth::user()->profile->logo_url) ? '' : 'hidden' }}">
                                        <span id="avatarPlaceholder" class="text-5xl text-gray-300 font-bold {{ (Auth::user()->profile && Auth::user()->profile->logo_url) ? 'hidden' : '' }}">
                                            {{ strtoupper(substr(Auth::user()->name ?? Auth::user()->email, 0, 1)) }}
                                        </span>
                                    </div>
                                    <label for="avatarUpload" class="absolute inset-0 bg-black/50 rounded-full flex flex-col items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer z-20">
                                        <i class="fa-solid fa-camera text-2xl mb-1"></i>
                                        <span class="text-sm font-semibold">Đổi ảnh</span>
                                    </label>
                                    <input type="file" id="avatarUpload" name="avatar" class="hidden" accept="image/*" onchange="previewImage(event)">
                                </div>
                                <p class="text-xs text-gray-400 text-center">Định dạng hỗ trợ: JPG, PNG, GIF.<br>Dung lượng tối đa: 2MB.</p>
                            </div>

                            <div class="md:col-span-2 space-y-6">
                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email đăng nhập</label>
                                    <input type="text" value="{{ Auth::user()->email }}" disabled class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-gray-500 cursor-not-allowed">
                                    <p class="text-xs text-orange-500 mt-1"><i class="fa-solid fa-circle-info"></i> Email quản trị viên không thể thay đổi.</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên hiển thị <span class="text-red-500">*</span></label>
                                    <input type="text" name="name" value="{{ Auth::user()->name }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                                </div>

                                <div class="pt-6 border-t border-gray-100">
                                    <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2"><i class="fa-solid fa-shield-halved text-blue-500"></i> Đổi mật khẩu (Bỏ trống nếu không đổi)</h3>
                                    
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu hiện tại</label>
                                            <input type="password" name="current_password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Mật khẩu mới</label>
                                                <input type="password" name="new_password" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nhập lại mật khẩu mới</label>
                                                <input type="password" name="new_password_confirmation" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-6 flex justify-end gap-3">
                                    <button type="reset" class="px-6 py-3 text-gray-600 font-semibold hover:bg-gray-100 rounded-xl transition">Hủy thay đổi</button>
                                    <button type="submit" class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-lg shadow-emerald-200 transition flex items-center gap-2">
                                        <i class="fa-solid fa-floppy-disk"></i> Lưu hồ sơ
                                    </button>
                                </div>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

        </main>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById('avatarPreview');
                const placeholder = document.getElementById('avatarPlaceholder');
                preview.src = reader.result;
                preview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            }
            if(event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }
    </script>
</body>
</html>