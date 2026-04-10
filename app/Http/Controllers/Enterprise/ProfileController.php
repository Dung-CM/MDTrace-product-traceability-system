<?php

namespace App\Http\Controllers\Enterprise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Hiển thị trang Hồ sơ
    public function index()
    {
        // Lấy thông tin profile của user đang đăng nhập
        $profile = UserProfile::where('user_id', Auth::id())->first();
        return view('enterprise.profile.index', compact('profile'));
    }

    // Cập nhật Hồ sơ
   public function update(Request $request)
    {
        $profile = UserProfile::where('user_id', Auth::id())->first();

        // 1. Thêm rule validate cho TẤT CẢ các trường (Bổ sung company_certificates)
        $request->validate([
            'company_name'           => 'required|string|max:255',
            'tax_code'               => 'required|string|max:50',
            'phone'                  => 'required|string|max:20',
            'address'                => 'required|string|max:500',
            'logo'                   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'map_link'               => 'nullable|string',
            'company_images.*'       => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Tối đa 2MB/ảnh
            'company_certificates.*' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120' // Hỗ trợ cả PDF, Tối đa 5MB/file
        ]);
      
        // 2. Lọc bỏ các trường file ra khỏi data text
        $data = $request->except(['_token', 'logo', 'company_images', 'company_certificates']);

        // 3. XỬ LÝ TỪNG LOẠI FILE
        
        // --- Xử lý Logo ---
        if ($request->hasFile('logo')) {
            if ($profile->logo_url) {
                Storage::disk('public')->delete($profile->logo_url);
            }
            $data['logo_url'] = $request->file('logo')->store('logos', 'public');
        }

        // --- XỬ LÝ CHỨNG NHẬN CÔNG TY (Hỗ trợ PDF & Ảnh) ---
        if ($request->hasFile('company_certificates')) {
            $certPaths = []; 
            if (!empty($profile->company_certificates)) {
                foreach ($profile->company_certificates as $oldCert) {
                    Storage::disk('public')->delete($oldCert);
                }
            }
            foreach ($request->file('company_certificates') as $cert) {
                $certPaths[] = $cert->store('company_certificates', 'public');
            }
            $data['company_certificates'] = $certPaths;
        }

        // --- XỬ LÝ NHIỀU ẢNH CÔNG TY ---
        if ($request->hasFile('company_images')) {
            $imagePaths = []; 
            if (!empty($profile->company_images)) {
                foreach ($profile->company_images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            foreach ($request->file('company_images') as $image) {
                $imagePaths[] = $image->store('company_images', 'public');
            }
            $data['company_images'] = $imagePaths;
        }

        // 4. Lưu vào Database
        $profile->update($data);

        return back()->with('success', 'Đã cập nhật Hồ sơ doanh nghiệp thành công!');
    }
}