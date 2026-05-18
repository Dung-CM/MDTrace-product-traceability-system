<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin; 
use App\Http\Middleware\CheckEnterprise;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Enterprise\ProfileController;
use App\Http\Controllers\Enterprise\BatchController;
use App\Http\Controllers\Enterprise\ScanHistoryController;
use App\Http\Controllers\Enterprise\ProductController;
use App\Http\Controllers\PublicEnterpriseController;
use App\Http\Controllers\PublicProductController;
use App\Http\Controllers\TraceController;

/* =========================================================
   1. KHU VỰC CÔNG KHAI (DÀNH CHO KHÁCH HÀNG)
   ========================================================= */
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/gioi-thieu', function () {
    return view('public.about');
})->name('about');

// Danh mục và chi tiết Sản phẩm / Doanh nghiệp
Route::get('/san-pham', [PublicProductController::class, 'index'])->name('public.products.index');
Route::get('/san-pham/{id}', [PublicProductController::class, 'show'])->name('public.products.show');
Route::get('/doanh-nghiep', [PublicEnterpriseController::class, 'index'])->name('public.enterprises.index');
Route::get('/doanh-nghiep/{id}', [PublicEnterpriseController::class, 'show'])->name('public.enterprises.show');

// Tra cứu thủ công và Quét QR chuẩn GS1
Route::get('/tra-cuu', [PublicProductController::class, 'search'])->name('public.search');
Route::get('/txng/01/{gtin}/10/{batch_code}', [PublicProductController::class, 'scanQr'])->name('public.qr.scan');
Route::get('/txng/01/{gtin}/10/{batchCode}', [TraceController::class, 'verify'])->name('trace.verify');

/* =========================================================
   2. KHU VỰC XÁC THỰC (AUTHENTICATION)
   ========================================================= */
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    /* =========================================================
       3. KHU VỰC QUẢN TRỊ DOANH NGHIỆP
       ========================================================= */
    Route::middleware([CheckEnterprise::class])->prefix('enterprise')->group(function () {
        
        // Bảng điều khiển (Dashboard)
        Route::get('/dashboard', [\App\Http\Controllers\Enterprise\DashboardController::class, 'index'])->name('enterprise.dashboard');
        Route::get('/dashboard/chart-data', [\App\Http\Controllers\Enterprise\DashboardController::class, 'getChartData'])->name('enterprise.dashboard.chart');

        // Hồ sơ Doanh nghiệp
        Route::get('/profile', [ProfileController::class, 'index'])->name('enterprise.profile.index');
        Route::post('/profile', [ProfileController::class, 'update'])->name('enterprise.profile.update');
        
        // Quản lý Sản phẩm
        Route::get('/products', [ProductController::class, 'index'])->name('enterprise.products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('enterprise.products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('enterprise.products.store');
        Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('enterprise.products.edit');
        Route::put('/products/{id}', [ProductController::class, 'update'])->name('enterprise.products.update');
        Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('enterprise.products.destroy');
        
        // Quản lý Lô hàng & Ghi nhận Blockchain
        Route::resource('batches', BatchController::class)->names([
            'index' => 'enterprise.batches.index',
            'create' => 'enterprise.batches.create',
            'store' => 'enterprise.batches.store',
            'show' => 'enterprise.batches.show',
            'edit' => 'enterprise.batches.edit',
            'update' => 'enterprise.batches.update',
            'destroy' => 'enterprise.batches.destroy',
        ]);
        Route::get('/batches/latest-data/{product_id}', [BatchController::class, 'getLatestBatchData'])->name('enterprise.batches.latest_data');
        Route::get('batches/{id}/download-qr', [BatchController::class, 'downloadQr'])->name('enterprise.batches.download_qr');
        Route::post('batches/{id}/mint', [BatchController::class, 'mintToBlockchain'])->name('enterprise.batches.mint');
        
        // Lịch sử Quét mã
        Route::get('scan-history', [ScanHistoryController::class, 'index'])->name('enterprise.scan-history.index');
    });

    /* =========================================================
       4. KHU VỰC QUẢN TRỊ VIÊN (ADMIN)
       ========================================================= */
    Route::middleware([CheckAdmin::class])->prefix('admin')->group(function () {
        
        // Bảng điều khiển & Thống kê
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/stats', [AdminController::class, 'stats'])->name('admin.dashboard.stats');
        
        // Xét duyệt và Quản lý Doanh nghiệp
        Route::get('/enterprises/active', [AdminController::class, 'activeEnterprises'])->name('admin.enterprises.active');
        Route::post('/enterprise/{id}/approve', [AdminController::class, 'approveEnterprise'])->name('admin.enterprise.approve');
        Route::post('/enterprise/{id}/reject', [AdminController::class, 'rejectEnterprise'])->name('admin.enterprise.reject');
        Route::post('/enterprise/{id}/lock', [AdminController::class, 'lockEnterprise'])->name('admin.enterprise.lock');
        Route::post('/enterprise/{id}/delete', [AdminController::class, 'destroyEnterprise'])->name('admin.enterprise.delete');

        // Thông tin Cá nhân
        Route::get('/profile', [AdminController::class, 'profile'])->name('admin.profile');
        Route::post('/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

        // Danh mục Hệ thống
        Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
        
        // Giám sát Blockchain
        Route::get('/scans', [AdminController::class, 'scanHistory'])->name('admin.scans.index');
        Route::get('/block-explorer', [AdminController::class, 'blockExplorer'])->name('admin.block_explorer');
    });
});

/* =========================================================
   5. XỬ LÝ LỖI (FALLBACK ROUTE)
   ========================================================= */
Route::fallback(function () {
    return redirect()->route('home');
});