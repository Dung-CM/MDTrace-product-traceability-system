<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $casts = [
    'trace_logs' => 'array',
    'origin_info' => 'array',      
    'distributor_info' => 'array', 
    'manufacturing_date' => 'date',
    'expiry_date' => 'date',
];

    // Lô hàng thuộc về 1 Sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Một Lô hàng có nhiều Lịch sử quét mã
    public function scanHistories()
    {
        return $this->hasMany(ScanHistory::class);
    }
}