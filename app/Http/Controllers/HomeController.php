<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;   // Model chứa thông tin doanh nghiệp
use App\Models\Batch;  // Model chứa thông tin lô hàng (Mã QR)

class HomeController extends Controller
{
    public function index()
    {
        // 1. Đếm tổng số Doanh nghiệp (Lọc những user có role là 'enterprise')
        // Có thể thêm ->where('status', 'active') nếu bạn chỉ muốn đếm doanh nghiệp đã duyệt
        $enterpriseCount = User::where('role', 'enterprise')->count();

        // 2. Đếm tổng số Mã QR đã tạo (Bằng tổng số lô hàng trong hệ thống)
        $qrCount = Batch::count(); 

        // 3. Ném 2 con số này ra trang welcome
        return view('welcome', compact('enterpriseCount', 'qrCount'));
    }
}