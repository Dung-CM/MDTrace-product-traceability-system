<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Hiển thị giao diện Dashboard Admin
    public function dashboard()
    {
        // Lấy danh sách doanh nghiệp có trạng thái 'pending' kèm theo thông tin profile
        $pendingEnterprises = User::where('role', 'enterprise')
                                  ->where('status', 'pending')
                                  ->with('profile') 
                                  ->orderBy('created_at', 'desc')
                                  ->get();

        return view('admin.dashboard', compact('pendingEnterprises'));
    }

    // Xử lý nút "Duyệt" doanh nghiệp
    public function approveEnterprise($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->role === 'enterprise' && $user->status === 'pending') {
            $user->status = 'active'; 
            $user->save();
            
            return back()->with('success', 'Đã duyệt thành công tài khoản doanh nghiệp: ' . ($user->profile->company_name ?? $user->email));
        }

        return back()->with('error', 'Tài khoản không hợp lệ hoặc đã được duyệt!');
    }
    // Xử lý nút "Từ chối" doanh nghiệp
    public function rejectEnterprise(Request $request, $id)
    {
        // Yêu cầu bắt buộc phải nhập lý do
        $request->validate([
            'reason' => 'required|string|max:1000'
        ], [
            'reason.required' => 'Vui lòng nhập lý do từ chối để doanh nghiệp biết.'
        ]);

        $user = User::findOrFail($id);
        
        if ($user->role === 'enterprise' && $user->status === 'pending') {
            $user->status = 'rejected'; // Đổi trạng thái thành Từ chối
            $user->rejection_reason = $request->reason; // Lưu lý do vào DB
            $user->save();
            
            return back()->with('success', 'Đã từ chối doanh nghiệp: ' . ($user->profile->company_name ?? $user->email));
        }

        return back()->with('error', 'Tài khoản không hợp lệ hoặc đã được xử lý!');
    }
}