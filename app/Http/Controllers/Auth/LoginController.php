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

        // CHỈNH SỬA Ở ĐÂY: Thêm điều kiện status phải là 'active' 
        // Nếu status là 'locked', 'deleted', 'pending' hay 'rejected' -> attempt sẽ trả về false
        $credentialsWithStatus = array_merge($credentials, ['status' => 'active']);

        // Đối với Admin, có thể họ không có status 'active' hoặc status khác, 
        // nên ta thử đăng nhập với status active trước, nếu không được thì kiểm tra xem có phải Admin không.
        if (Auth::attempt($credentialsWithStatus, $request->boolean('remember'))) {
            return $this->handleAuthenticatedRequest($request);
        }

        // Trường hợp đăng nhập thất bại: Có thể sai pass HOẶC tài khoản bị khóa/xóa/chờ duyệt
        // Ta kiểm tra xem email có tồn tại không để đưa ra thông báo chính xác
        $user = \App\Models\User::where('email', $request->email)->first();

        if ($user) {
            if ($user->status === 'pending') {
                return back()->with('error', 'Tài khoản đang chờ duyệt.')->withInput();
            }
            if ($user->status === 'locked') {
                return back()->with('error', 'Tài khoản bị khóa đến: ' . ($user->locked_until ?? 'vĩnh viễn') . '. Lý do: ' . $user->lock_reason)->withInput();
            }
            if ($user->status === 'rejected') {
                return back()->with('error', 'Tài khoản đã bị từ chối.')->withInput();
            }
            if ($user->status === 'deleted') {
                return back()->with('error', 'Tài khoản không tồn tại trên hệ thống.')->withInput();
            }
            
            // Nếu status là active mà vẫn sai thì là sai mật khẩu
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                return $this->handleAuthenticatedRequest($request);
            }
        }

        return back()->withErrors([
            'email' => 'Thông tin đăng nhập không chính xác.',
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
    private function handleAuthenticatedRequest(Request $request)
    {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Chào mừng Admin!');
        }

        return redirect()->route('enterprise.dashboard')->with('success', 'Đăng nhập thành công!');
    }
}