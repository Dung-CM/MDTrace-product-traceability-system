<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Các cột được phép thêm dữ liệu
    protected $fillable = [
        'name',
        'description',
    ];

    // Mối quan hệ 1-Nhiều (1 Danh mục có nhiều Sản phẩm)
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}