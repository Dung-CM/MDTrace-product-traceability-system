@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-10">
        
        <div class="text-center mb-10">
            <div class="mx-auto w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-4">
                <i class="fa-solid fa-right-to-bracket text-3xl text-emerald-600"></i>
            </div>
            <h2 class="text-3xl font-bold text-gray-900">Đăng nhập</h2>
            <p class="text-gray-600 mt-2">Chào mừng bạn trở lại với MDTrace</p>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <p class="font-medium text-sm">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded-xl mb-6 shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                <p class="font-medium text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500"
                           required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu</label>
                    <input type="password" 
                           name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500"
                           required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="mr-2">
                        Ghi nhớ đăng nhập
                    </label>
                    <a href="#" class="text-emerald-600 hover:underline">Quên mật khẩu?</a>
                </div>

                <button type="submit" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-4 rounded-2xl transition duration-200">
                    Đăng nhập
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-500 mt-8">
            Chưa có tài khoản doanh nghiệp? 
            <a href="{{ route('register') }}" class="text-emerald-600 font-medium hover:underline">Đăng ký ngay</a>
        </p>
    </div>
</div>
@endsection