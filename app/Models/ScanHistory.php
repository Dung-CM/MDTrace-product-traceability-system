<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScanHistory extends Model
{
    use HasFactory;

    // Trỏ đúng vào tên bảng trong ảnh của bạn
    protected $table = 'scan_histories'; 

    // Các cột có trong ảnh phpMyAdmin
    protected $fillable = [
        'batch_id',
        'scanned_at',
        'device_info',
        'ip_address',
    ];

    // Mối quan hệ: Vì bảng chỉ có batch_id, nên ta liên kết với bảng Batch
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}