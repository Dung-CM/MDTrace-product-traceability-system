<?php

namespace App\Http\Controllers\Enterprise;

use App\Http\Controllers\Controller;
use App\Models\ScanHistory;
use Illuminate\Support\Facades\Auth;

class ScanHistoryController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // 1. Tổng số lượt quét của tất cả sản phẩm thuộc Doanh nghiệp này
        $totalScans = ScanHistory::whereHas('batch.product', function($q) use ($userId) {
            $q->where('user_id', $userId);
        })->count();

        // 2. Lấy danh sách chi tiết (Mới nhất xếp trên)
        $histories = ScanHistory::with(['batch.product'])
            ->whereHas('batch.product', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->orderBy('scanned_at', 'desc')
            ->paginate(15);

        return view('enterprise.scan-history.index', compact('totalScans', 'histories'));
    }
}