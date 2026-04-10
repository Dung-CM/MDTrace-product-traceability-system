<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'tax_code',
        'address',
        'phone',
        'contact_email',
        'website_url',
        'description',
        'logo_url',
        'map_link', 
        'company_images',
        'company_certificates'
    ];

    // Relationship với User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // xử lí nhiều ảnh
    protected $casts = [
        'company_images' => 'array',
        'company_certificates' => 'array',
    ];

}