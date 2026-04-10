<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckEnterprise
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->role === 'enterprise') {
            
            // 1. Nếu đã duyệt -> Vào thẳng Dashboard
            if (Auth::user()->status === 'active') {
                return $next($request); 
            }
            
            // 2. Nếu bị từ chối -> Bắt đăng xuất và báo lý do
            if (Auth::user()->status === 'rejected') {
                $reason = Auth::user()->rejection_reason; // Lấy lý do từ CSDL
                Auth::logout();
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đã bị từ chối! Lý do: ' . $reason);
            }
            
            // 3. Nếu đang chờ duyệt -> Bắt đăng xuất và báo chờ
            if (Auth::user()->status === 'pending') {
                Auth::logout();
                return redirect()->route('login')->with('error', 'Tài khoản của bạn đang chờ Admin phê duyệt. Vui lòng quay lại sau!');
            }
        }

        return redirect()->route('login');
    }
}