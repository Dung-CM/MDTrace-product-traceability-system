<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->string('batch_code', 50)->unique();
            $table->date('manufacturing_date');
            $table->date('expiry_date');
            $table->integer('quantity');
            $table->string('qr_code_url');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');

            // --- THÊM 3 DÒNG NÀY VÀO ĐÂY NÈ DŨNG ---
            $table->json('trace_logs')->nullable();
            $table->json('origin_info')->nullable();
            $table->json('distributor_info')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};
