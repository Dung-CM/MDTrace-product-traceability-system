@extends('layouts.app')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-2xl w-full bg-white rounded-3xl shadow-xl p-10">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">Đăng ký doanh nghiệp</h2>
            <p class="text-gray-600 mt-3">Tạo tài khoản để bắt đầu quản lý mã truy xuất nguồn gốc</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf
            <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Dữ liệu bạn nhập chưa hợp lệ:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Email -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email doanh nghiệp</label>
                    <input type="email" name="email" value="{{ old('email') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500" required>
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mật khẩu</label>
                    <input type="password" name="password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500" required>
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500" required>
                </div>

                <!-- Thông tin doanh nghiệp -->
                <div class="md:col-span-2 mt-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Thông tin doanh nghiệp</h3>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tên doanh nghiệp</label>
                    <input type="text" name="company_name" value="{{ old('company_name') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mã số thuế</label>
                    <input type="text" name="tax_code" value="{{ old('tax_code') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Địa chỉ</label>
                    <input type="text" name="address" value="{{ old('address') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500" required>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email liên hệ</label>
                    <input type="email" name="contact_email" value="{{ old('contact_email') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Website (nếu có)</label>
                    <input type="url" name="website_url" value="{{ old('website_url') }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Giới thiệu về doanh nghiệp</label>
                    <textarea name="description" rows="4"
                              class="w-full px-4 py-3 border border-gray-300 rounded-3xl focus:outline-none focus:border-emerald-500">{{ old('description') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Logo doanh nghiệp</label>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full px-4 py-3 border border-gray-300 rounded-2xl focus:outline-none focus:border-emerald-500">
                </div>
            </div>

            <div class="mt-10">
                <button type="submit" 
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-4 rounded-2xl transition">
                    Đăng ký tài khoản doanh nghiệp
                </button>
            </div>
        </form>

        <p class="text-center text-sm text-gray-500 mt-8">
            Đã có tài khoản? 
            <a href="{{ route('login') }}" class="text-emerald-600 hover:underline">Đăng nhập</a>
        </p>
    </div>
</div>
@endsection