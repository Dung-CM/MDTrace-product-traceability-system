<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
    {
        // 2. Thêm logic: Nếu APP_URL trong file .env có chứa chữ https thì ép toàn bộ hệ thống dùng https
        if (str_contains(env('APP_URL'), 'https')) {
            URL::forceScheme('https');
        }
    }
}