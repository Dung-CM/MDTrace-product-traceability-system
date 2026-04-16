<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockchainTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_id', 
        'transaction_hash', 
        'network', 
        'status'
    ];

    // Mối quan hệ: Một giao dịch thuộc về một lô hàng
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }
}