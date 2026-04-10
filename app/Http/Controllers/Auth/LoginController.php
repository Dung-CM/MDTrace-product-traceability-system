<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // 1. Kiểm tra nếu đang chờ duyệt
            if ($user->status === 'pending') {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Tài khoản của bạn đang chờ Admin duyệt. Vui lòng chờ thông báo!');
            }

            // 2. Kiểm tra nếu BỊ TỪ CHỐI (Thêm đoạn này vào)
            if ($user->status === 'rejected') {
                $reason = $user->rejection_reason ?? 'Không có lý do cụ thể.';
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Tài khoản của bạn đã bị từ chối! Lý do: ' . $reason);
            }

            // 3. Kiểm tra nếu bị khóa
            if ($user->status === 'inactive') {
                Auth::logout();
                return redirect()->route('login')
                    ->with('error', 'Tài khoản của bạn đã bị khóa!');
            }

            // 4. Nếu mọi thứ OK -> Điều hướng theo vai trò
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'Chào mừng Admin!');
            }

            // Đối với Enterprise đã được duyệt (active)
            return redirect()->route('dashboard')->with('success', 'Đăng nhập thành công!');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Bạn đã đăng xuất thành công.');
    }
}