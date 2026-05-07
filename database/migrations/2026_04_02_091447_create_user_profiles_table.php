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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('company_name');
            $table->string('tax_code', 50);
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('contact_email')->nullable();
            $table->string('website_url')->nullable();
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->text('map_link')->nullable(); // Lưu link iframe Google Map
            $table->json('company_images')->nullable(); // Lưu mảng nhiều ảnh công ty
            $table->json('company_certificates')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
