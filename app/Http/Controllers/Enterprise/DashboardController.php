<?php

namespace App\Http\Controllers\Enterprise;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Batch;
use App\Models\ScanHistory;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Kiểm tra xem Hồ sơ doanh nghiệp đã hoàn thiện chưa
        $profile = \App\Models\UserProfile::where('user_id', $userId)->first();
        $isProfileComplete = true; // Mặc định là đã xong
        
        // Nếu chưa tạo profile, hoặc thiếu MST, hoặc thiếu Tên công ty thì đánh dấu là chưa hoàn thiện
        if (!$profile || empty($profile->company_name) || empty($profile->tax_code)) {
            $isProfileComplete = false;
        }
        // 1. LẤY SỐ LIỆU TỔNG QUAN (KPI CARDS)
        $totalProducts = Product::where('user_id', $userId)->count();
        $totalBatches = Batch::whereHas('product', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        // Đếm lô hàng sắp hết hạn
        $expiringBatches = Batch::whereHas('product', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereBetween('expiry_date', [Carbon::now(), Carbon::now()->addDays(30)])->count();

        // Đếm lượt quét tháng này
        $scansThisMonth = ScanHistory::whereHas('batch.product', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->whereMonth('scanned_at', Carbon::now()->month)
          ->whereYear('scanned_at', Carbon::now()->year)
          ->count();

        // 2. DỮ LIỆU TOP THIẾT BỊ QUÉT
        $allScans = ScanHistory::whereHas('batch.product', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->get();
        
        $deviceStats = ['Android' => 0, 'iOS' => 0, 'Khác' => 0];
        foreach($allScans as $scan) {
            $ua = strtolower($scan->device_info);
            if(str_contains($ua, 'android')) $deviceStats['Android']++;
            elseif(str_contains($ua, 'iphone') || str_contains($ua, 'ipad')) $deviceStats['iOS']++;
            else $deviceStats['Khác']++;
        }
        $totalDeviceScans = $allScans->count() > 0 ? $allScans->count() : 1;

        // 3. DANH SÁCH LÔ HÀNG GẦN ĐÂY NHẤT (Fix lỗi Undefined variable ở đây)
        $recentBatches = Batch::with('product')
            ->whereHas('product', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->orderBy('created_at', 'desc')->take(5)->get();

     // 4. NHẬT KÝ HOẠT ĐỘNG (Bao gồm Tạo SP, Tạo Lô và Khách quét mã)
        $recentProducts = Product::where('user_id', $userId)->latest()->take(3)->get();
        
        $activities = collect();
        // Lấy SP mới tạo
        foreach($recentProducts as $p) {
            $activities->push([
                'sort_time' => strtotime($p->created_at), // Thêm số giây để dễ sắp xếp
                'time' => $p->created_at, 
                'text' => 'Thêm sản phẩm: ' . $p->name, 
                'color' => 'bg-blue-500'
            ]);
        }
        // Lấy Lô hàng mới tạo
        foreach($recentBatches as $b) {
            $activities->push([
                'sort_time' => strtotime($b->created_at), 
                'time' => $b->created_at, 
                'text' => 'Tạo lô hàng mới: ' . $b->batch_code, 
                'color' => 'bg-emerald-500'
            ]);
        }
        
        // Lấy 3 lượt khách quét QR gần nhất
        $recentScansLog = ScanHistory::with('batch')->whereHas('batch.product', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->orderBy('scanned_at', 'desc')->take(3)->get();
        
        foreach($recentScansLog as $s) {
            $activities->push([
                'sort_time' => strtotime($s->scanned_at), 
                'time' => $s->scanned_at, 
                'text' => 'Có khách vừa quét mã QR (Lô: ' . $s->batch->batch_code . ')', 
                'color' => 'bg-purple-500'
            ]);
        }

        // Sắp xếp bằng số giây (sort_time), đảm bảo chính xác 100% không bị lộn xộn
        $activities = $activities->sortByDesc('sort_time')->take(5);

        // Biến chart để trống vì chúng ta đã dùng AJAX load ngầm
        $chartLabels = [];
        $chartData = [];

        return view('enterprise.dashboard', compact(
            'totalProducts', 'totalBatches', 'expiringBatches', 'scansThisMonth',
            'chartLabels', 'chartData', 'recentBatches', 'deviceStats', 'totalDeviceScans', 'activities','isProfileComplete'
        ));
    }
    // Thêm hàm này vào dưới hàm index()
    public function getChartData(Request $request)
    {
        $userId = Auth::id();
        $filter = $request->get('filter', 'month'); // Lấy bộ lọc (Mặc định là theo Tháng)
        
        $labels = [];
        $data = [];
        $totalScans = 0;

        // Câu lệnh gốc: Chỉ lấy dữ liệu của doanh nghiệp hiện tại
        $baseQuery = ScanHistory::whereHas('batch.product', function($q) use ($userId) {
            $q->where('user_id', $userId);
        });

        if ($filter === 'week') {
            // Lọc theo 7 ngày gần nhất
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $labels[] = $date->format('d/m'); // VD: 10/04
                
                $count = (clone $baseQuery)->whereDate('scanned_at', $date->toDateString())->count();
                $data[] = $count;
                $totalScans += $count;
            }
        } elseif ($filter === 'month') {
            // Lọc theo 6 tháng gần nhất
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $labels[] = 'T' . $month->format('n'); // VD: T1, T2
                
                $count = (clone $baseQuery)->whereMonth('scanned_at', $month->month)
                                           ->whereYear('scanned_at', $month->year)->count();
                $data[] = $count;
                $totalScans += $count;
            }
        } elseif ($filter === 'year') {
            // Lọc theo 5 năm gần nhất
            for ($i = 4; $i >= 0; $i--) {
                $year = Carbon::now()->subYears($i);
                $labels[] = $year->format('Y'); // VD: 2024, 2025
                
                $count = (clone $baseQuery)->whereYear('scanned_at', $year->year)->count();
                $data[] = $count;
                $totalScans += $count;
            }
        }

        // Trả về dữ liệu chuẩn JSON cho JavaScript xử lý
        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'total_scans' => number_format($totalScans)
        ]);
    }
}