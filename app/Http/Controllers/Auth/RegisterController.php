<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /**
     * Hiển thị form đăng ký doanh nghiệp
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Xử lý đăng ký doanh nghiệp
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'           => 'required|string|email|max:255|unique:users',
            'password'        => 'required|string|min:8|confirmed',
            'company_name'    => 'required|string|max:255',
            'tax_code'        => 'required|string|unique:user_profiles',
            'address'         => 'required|string|max:500',
            'phone'           => 'required|string|max:20',
            'contact_email'   => 'nullable|email|max:255',
            'website_url'     => 'nullable|url|max:255',
            'description'     => 'nullable|string|max:5000',
            'logo'            => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }

        // Tạo User
            $user = User::create([
            'name'      => $request->company_name,        
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'role'      => 'enterprise',
            'status'    => 'pending',
        ]);

        // Tạo User Profile
        $profileData = [
            'user_id'        => $user->id,
            'company_name'   => $request->company_name,
            'tax_code'       => $request->tax_code,
            'address'        => $request->address,
            'phone'          => $request->phone,
            'contact_email'  => $request->contact_email ?? $request->email,
            'website_url'    => $request->website_url,
            'description'    => $request->description,
        ];

        // Xử lý upload logo (nếu có)
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $profileData['logo_url'] = $logoPath;
        }

        UserProfile::create($profileData);

        event(new Registered($user));

        return redirect()->route('login')
                         ->with('success', 'Đăng ký thành công! Tài khoản của bạn đang chờ Admin duyệt.');
    }
    
}