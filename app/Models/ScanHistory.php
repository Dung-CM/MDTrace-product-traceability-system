<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Lịch sử quét này thuộc về Lô hàng nào
    public function batch()
    {
        return $this->belongsTo(Batch::class);
    }
}