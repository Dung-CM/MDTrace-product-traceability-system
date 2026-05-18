<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<header x-data="{ mobileMenuOpen: false }" class="bg-primary text-white py-5 px-6 fixed w-full top-0 z-50 shadow-md">
    <div class="max-w-7xl mx-auto flex justify-between items-center">
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="flex items-center gap-3 hover:opacity-90 transition">
            <div class="w-10 h-10 bg-accent rounded-2xl flex items-center justify-center text-white text-2xl font-bold">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <span class="text-2xl font-bold tracking-tighter">MD<span class="text-accent">Trace</span></span>
        </a> </div>

        <nav class="hidden md:flex gap-8 text-sm font-medium">
            <a href="{{ route('about') }}" class="text-white hover:text-emerald-400 font-medium transition">Giới thiệu</a>
            <a href="#trace-search" class="hover:text-accent transition ">Tra cứu</a>
            <a href="#how-it-works" class="hover:text-accent transition">Quy trình</a>
            <a href="#benefits" class="hover:text-accent transition">Lợi ích</a>
             <a href="#testimonials" class="hover:text-accent transition">Đánh giá</a>
             <a href="{{ route('public.products.index') }}" class="hover:text-accent transition">Sản Phẩm</a>
             <a href="{{ route('public.enterprises.index') }}" class="hover:text-accent transition">Doanh nghiệp</a>
        </nav>
        
        <div class="hidden md:flex items-center gap-4">
           
            @if(Auth::check() && Auth::user())
                
               
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 text-sm font-medium hover:text-accent transition flex items-center gap-2">
                        <i class="fa-solid fa-gauge"></i> Quản trị
                    </a>
                @else
                    <a href="{{ route('enterprise.dashboard') }}" class="px-5 py-2 text-sm font-medium hover:text-accent transition flex items-center gap-2">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                @endif

                
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="bg-gray-800 hover:bg-red-600 px-5 py-2.5 rounded-xl text-white font-semibold text-sm transition shadow-md flex items-center gap-2">
                        Đăng xuất <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>

            @else
                
                <a href="{{ route('login') }}" class="px-5 py-2 text-sm font-medium hover:text-accent transition">Đăng nhập</a>
                <a href="{{ route('register') }}" class="bg-accent hover:bg-accent-dark px-6 py-3 rounded-2xl font-semibold text-sm transition shadow-lg text-white">
                    Đăng ký doanh nghiệp
                </a>
            @endif
        </div>

        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-white focus:outline-none">
            <i class="fa-solid fa-bars text-2xl" x-show="!mobileMenuOpen"></i>
            <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak style="display: none;"></i>
        </button>
    </div>

    <div x-show="mobileMenuOpen" 
         x-transition
         @click.away="mobileMenuOpen = false"
         class="md:hidden absolute top-full left-0 w-full bg-primary border-t border-white/10 shadow-2xl pb-4" 
         x-cloak style="display: none;">
        
        <div class="flex flex-col px-6 pt-4 space-y-4">
            <a href="{{ route('about') }}" class="text-white hover:text-emerald-400 font-medium transition">Giới thiệu</a>
            <a href="#how-it-works" @click="mobileMenuOpen = false" class="text-white hover:text-accent font-medium">Quy trình</a>
            <a href="#trace-search" @click="mobileMenuOpen = false" class="text-white hover:text-accent font-medium">Tra cứu</a>
            <a href="#benefits" @click="mobileMenuOpen = false" class="text-white hover:text-accent font-medium">Lợi ích</a>
            <a href="#testimonials" @click="mobileMenuOpen = false" class="text-white hover:text-accent font-medium">Đánh giá</a>
            <a href="{{ route('public.products.index') }}" class="text-accent font-bold">Sản Phẩm</a>
            <a href="{{ route('public.enterprises.index') }}" class="text-accent font-bold">Doanh nghiệp</a>
            
            <hr class="border-white/10 my-2">

            @if(Auth::check() && Auth::user())
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-white font-medium flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-gauge"></i> Quản trị
                    </a>
                @else
                    <a href="{{ route('enterprise.dashboard') }}" class="text-white font-medium flex items-center gap-2 mb-2">
                        <i class="fa-solid fa-chart-line"></i> Dashboard
                    </a>
                @endif
                <form action="{{ route('logout') }}" method="POST" class="m-0 p-0">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 px-5 py-3 rounded-xl text-white font-semibold text-sm transition shadow-md flex items-center justify-center gap-2">
                        Đăng xuất <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-center text-white border border-white/20 py-3 rounded-xl font-medium hover:bg-white/10 transition">Đăng nhập</a>
                <a href="{{ route('register') }}" class="text-center bg-accent hover:bg-accent-dark py-3 rounded-xl font-semibold text-sm transition shadow-lg text-white mt-3">
                    Đăng ký doanh nghiệp
                </a>
            @endif
        </div>
    </div>
</header>