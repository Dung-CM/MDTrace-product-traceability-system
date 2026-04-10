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
    Schema::table('products', function (Blueprint $table) {
        $table->string('company_name')->nullable(); // Tên cty phân phối/sản xuất
        $table->boolean('is_authentic')->default(true); // Tích xanh chính hãng
        $table->string('trace_code')->nullable(); // Mã truy xuất hệ thống
        $table->date('mfg_date')->nullable(); // Ngày SX
        $table->date('exp_date')->nullable(); // HSD
        $table->string('batch_code')->nullable(); // Số lô
        
        // CÁC CỘT DẠNG JSON ĐỂ LƯU DỮ LIỆU TỪ CÁC MODAL
        $table->json('certificates')->nullable(); // Chứng nhận sản phẩm
        $table->json('origin_info')->nullable(); // Modal 1: Nguồn gốc nguyên liệu
        $table->json('product_details')->nullable(); // Modal 2: Thông tin chi tiết SP
        $table->json('company_info')->nullable(); // Modal 3: Thông tin công ty
        $table->json('trace_logs')->nullable(); // Modal 4: Nhật ký sản xuất
        $table->json('distributor_info')->nullable(); // Modal 5: Đơn vị phân phối
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            //
        });
    }
};
