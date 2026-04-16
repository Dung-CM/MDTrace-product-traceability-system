<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckAdmin; 
use App\Http\Middleware\CheckEnterprise;
use App\Http\Controllers\CheckAdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Enterprise\ProfileController;
use App\Http\Controllers\Enterprise\BatchController;
use App\Http\Controllers\Enterprise\ScanHistoryController;
use App\Http\Controllers\Enterprise\ProductController;
use App\Http\Controllers\PublicEnterpriseController;
use App\Http\Controllers\PublicProductController;

// route công khai
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route hiển thị danh sách sản phẩm cho khách hàng
Route::get('/san-pham', [PublicProductController::class, 'index'])->name('public.products.index');
// Route hiển thị chi tiết sản phẩm (Khi click vào hoặc Quét mã QR)
Route::get('/san-pham/{id}', [PublicProductController::class, 'show'])->name('public.products.show');

// Route hiển thị danh sách Doanh nghiệp cho khách hàng
Route::get('/doanh-nghiep', [\App\Http\Controllers\PublicEnterpriseController::class, 'index'])->name('public.enterprises.index');

// Route hiển thị chi tiết 1 Doanh nghiệp
Route::get('/doanh-nghiep/{id}', [\App\Http\Controllers\PublicEnterpriseController::class, 'show'])->name('public.enterprises.show');
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
        // Nút bấm kích hoạt Hashing dữ liệu lô hàng
        Route::post('batches/{id}/mint', [\App\Http\Controllers\Enterprise\BatchController::class, 'mintToBlockchain'])->name('enterprise.batches.mint');
    });
    // --- KHU VỰC ADMIN (BẢN AN TOÀN TUYỆT ĐỐI) ---
    Route::middleware([CheckAdmin::class])->prefix('admin')->group(function () {
        

        Route::get('/stats', [AdminController::class, 'stats'])->name('admin.dashboard.stats');
        Route::get('/enterprises/active', [AdminController::class, 'activeEnterprises'])->name('admin.enterprises.active');

        Route::post('/enterprise/{id}/lock', [AdminController::class, 'lockEnterprise'])->name('admin.enterprise.lock');
        Route::post('/enterprise/{id}/delete', [AdminController::class, 'destroyEnterprise'])->name('admin.enterprise.delete');

        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/enterprise/{id}/approve', [AdminController::class, 'approveEnterprise'])->name('admin.enterprise.approve');
        Route::post('/enterprise/{id}/reject', [AdminController::class, 'rejectEnterprise'])->name('admin.enterprise.reject');

        Route::get('/profile', [App\Http\Controllers\AdminController::class, 'profile'])->name('admin.profile');
        Route::post('/profile/update', [App\Http\Controllers\AdminController::class, 'updateProfile'])->name('admin.profile.update');

        // QUẢN LÝ DANH MỤC SẢN PHẨM
        Route::get('/categories', [App\Http\Controllers\CategoryController::class, 'index'])->name('admin.categories.index');
        Route::post('/categories', [App\Http\Controllers\CategoryController::class, 'store'])->name('admin.categories.store');
        Route::put('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/categories/{id}', [App\Http\Controllers\CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    });

   
});

 Route::get('/test-mail', function () {
    // Ép Laravel lấy cấu hình ra cho chúng ta kiểm tra
    $username = config('mail.mailers.smtp.username');
    $pass = (string) config('mail.mailers.smtp.password');
    $maskedPass = strlen($pass) > 0 ? substr($pass, 0, 4) . '........' . substr($pass, -4) : 'TRỐNG (NULL)';

    echo "<div style='font-family: Arial; padding: 20px; background: #f8f9fa; border-radius: 8px;'>";
    echo "<h2 style='color: #0A2540;'>🔎 BÁO CÁO CẤU HÌNH LARAVEL ĐANG ĐỌC:</h2>";
    echo "<ul>";
    echo "<li>Email đang dùng: <b style='color: blue;'>" . $username . "</b></li>";
    echo "<li>Mật khẩu đang dùng: <b style='color: red;'>" . $maskedPass . "</b></li>";
    echo "<li>Độ dài mật khẩu: <b>" . strlen($pass) . " ký tự</b> <i>(Đúng chuẩn Google là 16 ký tự)</i></li>";
    echo "</ul>";
    echo "<hr>";
    echo "</div>";

    try {
        \Illuminate\Support\Facades\Mail::raw('MDTrace Test!', function ($message) {
            $message->to('admin@mdtrace.com')->subject('Test');
        });
        echo '<h1 style="color:green; padding: 20px;">✅ GỬI THÀNH CÔNG! Google đã mở cửa!</h1>';
    } catch (\Exception $e) {
        echo '<h1 style="color:red; padding: 20px;">❌ GOOGLE ĐÁP TRẢ:</h1><p style="padding: 0 20px;">' . $e->getMessage() . '</p>';
    }
});
// Bắt các đường dẫn lỗi khác trả về trang chủ
Route::fallback(function () {
    return redirect()->route('home');
});