<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdmin
{
    /**
     * Xử lý yêu cầu truy cập.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra xem: 1. Đã đăng nhập chưa? VÀ 2. Có đúng là role 'admin' không?
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request); // Hợp lệ -> Mở cổng cho đi tiếp vào Dashboard
        }

        // Nếu không hợp lệ -> Đuổi về trang chủ (hoặc có thể đuổi về trang báo lỗi 403)
        return redirect('/')->with('error', 'Bạn không có quyền truy cập khu vực Quản trị!');
    }
}