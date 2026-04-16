<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Category; 
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
            // 1. Lưu DB trước
            $user->status = 'active'; 
            $user->save();
            
            // 2. Thử gửi mail và Bắt lỗi
            try {
                $companyName = $user->profile->company_name ?? $user->name;
                $data = ['enterpriseName' => $companyName];

                Mail::send('emails.enterprise_approved', $data, function($message) use ($user) {
                    $message->to($user->email, $user->name)
                            ->subject('Hệ thống MDTrace: Tài khoản đã được duyệt');
                });

                // Chạy qua được đoạn trên = Google đã gửi đi thành công
                return back()->with('success', 'Đã duyệt thành công VÀ GỬI MAIL ĐẾN: ' . $user->email);
                
            } catch (\Exception $e) {
                // Nếu rớt mạng hoặc Google chặn, in lỗi đỏ chót ra màn hình
                return back()->with('error', 'CẢNH BÁO: Đã duyệt nhưng LỖI GỬI MAIL: ' . $e->getMessage());
            }
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
   // Hàm hiển thị trang Dashboard Thống kê
  public function stats()
    {
        // 1. THẺ TỔNG QUAN (Đếm dữ liệu thật)
       $totalEnterprises = User::where('role', 'enterprise')->whereIn('status', ['active', 'locked'])->count();
       
        $pendingCount = User::where('role', 'enterprise')->where('status', 'pending')->count();
        $lockedCount = User::where('role', 'enterprise')->where('status', 'locked')->count();

       // 2. DỮ LIỆU BIỂU ĐỒ TRÒN (LẤY TỪ BẢNG CATEGORIES)
        $categories = \App\Models\Category::has('products')->withCount('products')->get(); 
        
        if ($categories->count() > 0) {
            $chartLabels = $categories->pluck('name')->toArray();
            $chartData = $categories->pluck('products_count')->toArray();
        } else {
            // Nếu hệ thống thật sự chưa có sản phẩm nào
            $chartLabels = ['Chưa có sản phẩm nào'];
            $chartData = [1]; // Vẽ 1 vòng tròn nguyên khối
        }

        // 3. BẢNG XẾP HẠNG (Lấy 5 doanh nghiệp mới nhất hoặc hoạt động gần nhất)
        $topEnterprises = User::where('role', 'enterprise')
                              ->where('status', 'active')
                              ->with('profile')
                              ->orderBy('last_seen_at', 'desc') // Giả định ai onl gần nhất là top
                              ->take(5)
                              ->get();

        // 4. DÒNG SỰ KIỆN (Activity Timeline - Lấy 5 tài khoản vừa có thay đổi)
        $recentActivities = User::where('role', 'enterprise')
                                ->orderBy('updated_at', 'desc')
                                ->take(5)
                                ->get();

        return view('admin.stats', compact(
            'totalEnterprises', 'pendingCount', 'lockedCount', 
            'chartLabels', 'chartData', 'topEnterprises', 'recentActivities'
        ));
    }

    public function activeEnterprises()
        {
            // Lấy các tài khoản có status là active hoặc locked (Tuyệt đối không lấy 'deleted' hoặc 'pending')
            $enterprises = User::where('role', 'enterprise')
                            ->whereIn('status', ['active', 'locked'])
                            ->with('profile') 
                            ->orderBy('created_at', 'desc')
                            ->get();

            return view('admin.enterprises_active', compact('enterprises'));
        }

       // Xử lý Khóa/Cấm có thời hạn
    public function lockEnterprise(Request $request, $id)
    {
        $request->validate([
            'lock_reason' => 'required|string',
            'duration' => 'required' // 3_days, 7_days, 30_days, permanent
        ]);

        $user = User::findOrFail($id);
        
        // Tính toán thời gian khóa
        $until = null;
        $durationText = 'Vĩnh viễn';
        
        if ($request->duration !== 'permanent') {
            $days = (int)$request->duration;
            $until = now()->addDays($days);
            $durationText = $days . ' ngày';
        }

        $user->status = 'locked'; 
        $user->lock_reason = $request->lock_reason;
        $user->locked_at = now();
        $user->locked_until = $until;
        $user->save();
        
        // --- BẮT ĐẦU GỬI MAIL KHÓA ---
        $emailData = [
            'company_name' => $user->profile->company_name ?? $user->name,
            'reason' => $request->lock_reason,
            'duration' => $durationText,
            'until' => $until ? $until->format('d/m/Y') : 'Không xác định'
        ];

        Mail::send('emails.enterprise_locked', $emailData, function($message) use ($user) {
            $message->to($user->email)->subject('Thông báo: Tài khoản MDTrace của bạn đã bị tạm khóa');
        });
    // --- KẾT THÚC GỬI MAIL ---
        

        return back()->with('success', "Đã KHÓA tài khoản doanh nghiệp $durationText.");
    }

    // Xử lý Xóa tài khoản kèm lý do
    public function destroyEnterprise(Request $request, $id)
    {
        $request->validate(['delete_reason' => 'required|string']);

        $user = User::findOrFail($id);
        $user->status = 'deleted';
        $user->delete_reason = $request->delete_reason;
        $user->save();

            // --- BẮT ĐẦU GỬI MAIL XÓA ---
        $emailData = [
            'company_name' => $user->profile->company_name ?? $user->name,
            'reason' => $request->delete_reason
        ];

        Mail::send('emails.enterprise_deleted', $emailData, function($message) use ($user) {
            $message->to($user->email)->subject('Thông báo: Tài khoản MDTrace của bạn đã bị xóa');
        });
        // --- KẾT THÚC GỬI MAIL ---
        return back()->with('success', 'Đã XÓA doanh nghiệp và lưu lý do vào hệ thống.');
    }
    // Hiển thị form Profile
    public function profile()
    {
        $admin = Auth::user();
        return view('admin.profile', compact('admin'));
    }

    // Xử lý cập nhật
    public function updateProfile(Request $request)
    {
        $admin = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'current_password' => 'nullable|required_with:new_password',
            'new_password' => 'nullable|min:6|confirmed', 
        ]);

        $admin->name = $request->name;

        if ($request->filled('new_password')) {
            if (!Hash::check($request->current_password, $admin->password)) {
                return back()->with('error', 'Mật khẩu hiện tại không chính xác!');
            }
            $admin->password = Hash::make($request->new_password);
        }

        if ($request->hasFile('avatar')) {
            $profile = $admin->profile()->firstOrCreate(['user_id' => $admin->id]);
            
            if ($profile->logo_url && Storage::disk('public')->exists($profile->logo_url)) {
                Storage::disk('public')->delete($profile->logo_url);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $profile->logo_url = $path;
            $profile->save();
        }

        $admin->save();
        return back()->with('success', 'Đã cập nhật thông tin cá nhân thành công!');
    }
}