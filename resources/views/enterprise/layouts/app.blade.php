<!--resources/views/enterprise/layouts/app.blade.php-->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Dashboard - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: '#0A2540', accent: '#10B981' }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-primary text-white flex flex-col shadow-xl z-20">
        <div class="h-16 flex items-center px-6 border-b border-gray-700">
            <div class="text-2xl font-bold flex items-center gap-2">
                <i class="fa-solid fa-qrcode text-accent"></i> MDTrace
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-4">Quản lý chính</p>
            <a href="{{ route('enterprise.dashboard') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors mb-2 
                { request()->routeIs('enterprise.dashboard') ? 'bg-emerald-500 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                <i class="fa-solid fa-house"></i> Tổng quan
            </a>

            <a href="{{ route('enterprise.products.index') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors mb-2 
                {{ request()->routeIs('enterprise.products.*') ? 'bg-emerald-500 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                <i class="fa-solid fa-box"></i> Quản lý Sản phẩm
            </a>
            <a href="{{ route('enterprise.batches.index') }}"  class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors mb-2 
                {{ request()->routeIs('enterprise.batches.*') ? 'bg-emerald-500 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                <i class="fa-solid fa-layer-group w-5 text-center"></i> Quản lý Lô hàng
            </a>
            <a href="{{ route('enterprise.scan-history.index') }}"  class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors mb-2 
                {{ request()->routeIs('enterprise.scan-history.*') ? 'bg-emerald-500 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i> Lịch sử Quét mã
            </a>

            <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2 mt-6">Cài đặt</p>
            <a href="{{ route('enterprise.profile.index') }}" 
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition-colors mb-2 
                {{ request()->routeIs('enterprise.profile.*') ? 'bg-emerald-500 text-white' : 'text-gray-300 hover:bg-slate-700 hover:text-white' }}">
                <i class="fa-solid fa-building w-5 text-center"></i> Hồ sơ doanh nghiệp
            </a>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10">
            <div class="flex items-center text-xl font-semibold text-gray-800">
                @yield('title', 'Dashboard')
            </div>
            
            <div class="flex items-center gap-4">
                <button class="text-gray-400 hover:text-gray-600 transition"><i class="fa-regular fa-bell text-xl"></i></button>
                
                <div class="flex items-center gap-3 border-l pl-4 border-gray-200">
                @php
                    // Tự động tìm Profile của người đang đăng nhập ngay tại Layout
                    $userProfile = \App\Models\UserProfile::where('user_id', Auth::id())->first();
                @endphp

                @if($userProfile && $userProfile->logo_url)
                    <img src="{{ asset('storage/' . $userProfile->logo_url) }}" alt="Logo" class="w-8 h-8 rounded-full shadow-sm object-cover border border-gray-200">
                @else
                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold shadow-sm border border-emerald-200">
                        {{ substr(Auth::user()->name ?? 'D', 0, 1) }}
                    </div>
                @endif
                </div>

                <form method="POST" action="{{ route('logout') }}" class="ml-2 border-l pl-4 border-gray-200">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm font-semibold text-gray-500 hover:text-red-600 hover:bg-red-50 rounded-xl transition duration-200">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i> Đăng xuất
                    </button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
            @yield('content')
        </main>
    </div>

    @stack('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
</body>
</html>