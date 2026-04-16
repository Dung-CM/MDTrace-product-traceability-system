<!-- resources/views/layouts/header.blade.php -->
<header class="bg-primary text-white py-5 px-6 fixed w-full top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-90 transition">
            <div class="w-10 h-10 bg-accent rounded-2xl flex items-center justify-center text-white text-2xl font-bold">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <span class="text-2xl font-bold tracking-tighter">MD<span class="text-accent">Trace</span></span>
        </a> </div>

        <nav class="hidden md:flex gap-8 text-sm font-medium">
            <a href="#how-it-works" class="hover:text-accent transition">Quy trình</a>
            <a href="#benefits" class="hover:text-accent transition">Lợi ích</a>
             <a href="#testimonials" class="hover:text-accent transition">Đánh giá</a>
             <a href="{{ route('public.products.index') }}" class="hover:text-accent transition">Sản Phẩm</a>
             <a href="{{ route('public.enterprises.index') }}" class="hover:text-accent transition">Doanh nghiệp</a>
        </nav>
<div class="flex items-center gap-4">
            {{-- Kiểm tra: Phải có Session đăng nhập VÀ User phải thực sự tồn tại trong CSDL --}}
            @if(Auth::check() && Auth::user())
                
                {{-- Nút Dashboard theo Role --}}
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 text-sm font-medium hover:text-accent transition flex items-center gap-2">
                        <i class="fa-solid fa-gauge"></i> Quản trị
                    </a>
                @else
                    <a href="{{ route('enterprise.dashboard') }}" class="px-5 py-2 text-sm font-medium hover:text-accent transition flex items-center gap-2">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                @endif

                {{-- Nút Đăng xuất --}}
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="bg-gray-800 hover:bg-red-600 px-5 py-2.5 rounded-xl text-white font-semibold text-sm transition shadow-md flex items-center gap-2">
                        Đăng xuất <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>

            @else
                {{-- Chưa đăng nhập hoặc Database trống --}}
                <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium hover:text-accent transition">Đăng nhập</a>
                <a href="{{ route('register') }}" class="bg-accent hover:bg-accent-dark px-6 py-3 rounded-2xl font-semibold text-sm transition shadow-lg text-white">
                    Đăng ký doanh nghiệp
                </a>
            @endif
        </div>
        </div>
    </div>
</header>