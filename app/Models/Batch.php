<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    use HasFactory;

    protected $guarded = [];

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