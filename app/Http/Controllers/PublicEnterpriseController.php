<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PublicEnterpriseController extends Controller
{
    /**
     * Hiển thị danh sách doanh nghiệp đối tác (Trang Index)
     */
   public function index()
    {
        // Lấy danh sách DN, nhớ kèm theo 'profile' để có logo
        $enterprises = \App\Models\User::where('role', 'enterprise')
                                       ->where('status', 'active')
                                       ->with('profile') // Bắt buộc có dòng này
                                       ->paginate(12);
        
        return view('public.enterprise.index', compact('enterprises'));
    }

    public function show($id)
    {
        // Kéo toàn bộ dữ liệu: profile và danh sách products
        $enterprise = \App\Models\User::where('role', 'enterprise')
                                      ->where('status', 'active')
                                      ->with(['profile', 'products']) // Kéo thêm products
                                      ->findOrFail($id);

        return view('public.enterprise.show', compact('enterprise'));
    }
}