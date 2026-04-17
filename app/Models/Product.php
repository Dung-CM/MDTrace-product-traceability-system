<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'category_id', 'gtin_code', 'name', 'description', 'image_url',
        'company_name', 'is_authentic', 'trace_code', 'mfg_date', 'exp_date', 'batch_code',
        'certificates', 'origin_info', 'product_details', 'company_info', 'trace_logs', 'distributor_info'
    ];

    // Khai báo các trường JSON để Laravel tự động parse thành Array
    protected $casts = [
        'is_authentic' => 'boolean',
        'certificates' => 'array',
        'origin_info' => 'array',
        'product_details' => 'array',
        'company_info' => 'array',
        'trace_logs' => 'array',
        'distributor_info' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    // Một sản phẩm có thể xuất ra nhiều Lô hàng
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}