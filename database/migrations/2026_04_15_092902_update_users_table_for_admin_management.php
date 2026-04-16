<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        // Lưu thời gian truy cập cuối cùng
        $table->timestamp('last_seen_at')->nullable();
        
        // Lưu lý do khóa/cấm và thời gian hết hạn (nếu có)
        $table->text('lock_reason')->nullable();
        $table->timestamp('locked_at')->nullable();
        
        // Đảm bảo cột status của bạn có thể nhận giá trị mới
        // (Nếu bạn đã có cột status dạng string thì không cần dòng này)
        // $table->string('status')->default('pending')->change(); 
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['last_seen_at', 'lock_reason', 'locked_at']);
    });
}
};
