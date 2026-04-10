<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckAdmin; 
use App\Http\Middleware\CheckEnterprise;
use App\Http\Controllers\Enterprise\ProfileController;
use App\Http\Controllers\PublicProductController;
use App\Http\Controllers\Enterprise\BatchController;


// route công khai
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route hiển thị danh sách sản phẩm cho khách hàng
Route::get('/san-pham', [PublicProductController::class, 'index'])->name('public.products.index');

// Route hiển thị chi tiết sản phẩm (Khi click vào hoặc Quét mã QR)
Route::get('/san-pham/{id}', [PublicProductController::class, 'show'])->name('public.products.show');
// Route Quét mã QR chuẩn GS1 (VD: /txng/01/893.../10/TNDH...)
Route::get('/txng/01/{gtin}/10/{batch_code}', [\App\Http\Controllers\PublicProductController::class, 'scanQr'])->name('public.qr.scan');

// ROUTE BÍ MẬT TẠO ADMIN (Chỉ dùng khi cần test)
Route::get('/create-admin', function () {
    \Illuminate\Support\Facades\Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    $admin = \App\Models\User::updateOrCreate(
        ['email' => 'admin@mdtrace.com'],
        ['password' => \Illuminate\Support\Facades\Hash::make('12345678'), 'role' => 'admin', 'status' => 'active']
    );
    \Illuminate\Support\Facades\Auth::login($admin);
    return redirect()->route('admin.dashboard');
});

// KHU VỰC CHƯA ĐĂNG NHẬP (GUEST)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

// KHU VỰC ĐÃ ĐĂNG NHẬP (AUTH)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Route Dashboard chung
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- KHU VỰC DOANH NGHIỆP ---
    // Bọc nhóm Route này bằng CheckEnterprise để lọc status
    Route::middleware([CheckEnterprise::class])->prefix('enterprise')->group(function () {
        
        // Route::get('/dashboard', function () {
        //     return view('enterprise.dashboard');
        // })->name('enterprise.dashboard');
        // Trang Tổng quan (Fix lỗi gọi nhầm Controller)
        Route::get('/dashboard', [\App\Http\Controllers\Enterprise\DashboardController::class, 'index'])->name('enterprise.dashboard');
        
        // API để lấy dữ liệu lọc Biểu đồ (AJAX)
        Route::get('/dashboard/chart-data', [\App\Http\Controllers\Enterprise\DashboardController::class, 'getChartData'])->name('enterprise.dashboard.chart');

       Route::get('/profile', [ProfileController::class, 'index'])->name('enterprise.profile.index');
         Route::post('/profile', [ProfileController::class, 'update'])->name('enterprise.profile.update');
        
       // QUẢN LÝ SẢN PHẨM
        // 1. Hiển thị danh sách sản phẩm (Dòng này đang bị thiếu nên sinh ra lỗi)
        Route::get('/products', [\App\Http\Controllers\Enterprise\ProductController::class, 'index'])->name('enterprise.products.index');
        
        // 2. Hiển thị form thêm mới
        Route::get('/products/create', [\App\Http\Controllers\Enterprise\ProductController::class, 'create'])->name('enterprise.products.create');
        
        // 3. Xử lý lưu dữ liệu thêm mới
        Route::post('/products', [\App\Http\Controllers\Enterprise\ProductController::class, 'store'])->name('enterprise.products.store');
        // 4. Hiển thị form sửa sản phẩm
        Route::get('/products/{id}/edit', [App\Http\Controllers\Enterprise\ProductController::class, 'edit'])->name('enterprise.products.edit');

        // Route xử lý việc Cập nhật (Lưu vào database)
        Route::put('/products/{id}', [App\Http\Controllers\Enterprise\ProductController::class, 'update'])->name('enterprise.products.update');

        // 5. xử lý việc Xóa sản phẩm
        Route::delete('/products/{id}', [App\Http\Controllers\Enterprise\ProductController::class, 'destroy'])->name('enterprise.products.destroy');
        // QUẢN LÝ LÔ HÀNG
         // Route Quản lý Lô hàng (Batches)
            Route::resource('batches', BatchController::class)->names([
                'index' => 'enterprise.batches.index',
                'create' => 'enterprise.batches.create',
                'store' => 'enterprise.batches.store',
                'show' => 'enterprise.batches.show',
                'edit' => 'enterprise.batches.edit',
                'update' => 'enterprise.batches.update',
                'destroy' => 'enterprise.batches.destroy',
            ]);
            // Route phụ để tải mã QR về máy
            Route::get('batches/{id}/download-qr', [BatchController::class, 'downloadQr'])->name('enterprise.batches.download_qr');
        // Route Quản lý Lịch sử quét mã
        Route::get('scan-history', [\App\Http\Controllers\Enterprise\ScanHistoryController::class, 'index'])->name('enterprise.scan-history.index');
        
    });
    // --- KHU VỰC ADMIN (BẢN AN TOÀN TUYỆT ĐỐI) ---
    Route::middleware([CheckAdmin::class])->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/enterprise/{id}/approve', [AdminController::class, 'approveEnterprise'])->name('admin.enterprise.approve');
        
        // DÒNG MỚI THÊM: Route xử lý từ chối
        Route::post('/enterprise/{id}/reject', [AdminController::class, 'rejectEnterprise'])->name('admin.enterprise.reject');
    });
});

// Bắt các đường dẫn lỗi khác trả về trang chủ
Route::fallback(function () {
    return redirect()->route('home');
});