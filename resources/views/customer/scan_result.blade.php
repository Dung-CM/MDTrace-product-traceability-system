<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả Truy xuất - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans antialiased text-gray-800">
    
    <header class="bg-[#0A2540] text-white p-4 shadow-md sticky top-0 z-50">
        <div class="max-w-md mx-auto flex items-center justify-center gap-2">
            <i class="fa-solid fa-qrcode text-emerald-400 text-xl"></i>
            <h1 class="text-xl font-bold tracking-tight">MD<span class="text-emerald-400">Trace</span></h1>
        </div>
    </header>

    <main class="max-w-md mx-auto p-4 space-y-5 pb-10 mt-2">
        
        @if($verifyStatus === 'success')
            <div class="bg-emerald-50 border-2 border-emerald-500 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-16 h-16 bg-emerald-500 text-white rounded-full flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg shadow-emerald-500/30">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <h2 class="text-emerald-800 font-bold text-xl mb-1">Xác thực Thành công</h2>
                <p class="text-emerald-700 text-sm font-medium">{{ $message }}</p>
            </div>
        @elseif($verifyStatus === 'warning')
            <div class="bg-amber-50 border-2 border-amber-500 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-16 h-16 bg-amber-500 text-white rounded-full flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg shadow-amber-500/30">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                </div>
                <h2 class="text-amber-800 font-bold text-xl mb-1">Cảnh báo Dữ liệu</h2>
                <p class="text-amber-700 text-sm font-medium">{{ $message }}</p>
            </div>
        @else
            <div class="bg-red-50 border-2 border-red-500 rounded-2xl p-6 text-center shadow-sm">
                <div class="w-16 h-16 bg-red-500 text-white rounded-full flex items-center justify-center text-3xl mx-auto mb-3 shadow-lg shadow-red-500/30">
                    <i class="fa-solid fa-wifi"></i>
                </div>
                <h2 class="text-red-800 font-bold text-xl mb-1">Lỗi Tra cứu</h2>
                <p class="text-red-700 text-sm font-medium">{{ $message }}</p>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            @if($product->image_url)
                <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->name }}" class="w-full h-56 object-cover">
            @else
                <div class="w-full h-56 bg-gray-100 flex flex-col items-center justify-center text-gray-400">
                    <i class="fa-solid fa-box-open text-5xl mb-2"></i>
                    <span class="text-sm">Không có hình ảnh</span>
                </div>
            @endif
            
            <div class="p-5">
                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $product->name }}</h3>
                <p class="text-sm text-gray-500 mb-4"><i class="fa-solid fa-building-circle-check text-emerald-500 mr-1"></i> {{ $product->company_name ?? 'Đang cập nhật' }}</p>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500 text-sm">Mã GTIN</span>
                        <span class="font-semibold text-gray-800">{{ $product->gtin_code }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500 text-sm">Mã Lô hàng</span>
                        <span class="font-semibold text-gray-800">{{ $batch->batch_code }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-50">
                        <span class="text-gray-500 text-sm">Ngày sản xuất</span>
                        <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($batch->manufacturing_date)->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-500 text-sm">Hạn sử dụng</span>
                        <span class="font-bold text-red-500">{{ \Carbon\Carbon::parse($batch->expiry_date)->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-brands fa-hive text-emerald-500"></i> Bằng chứng On-chain
            </h4>
            
            <div class="mb-4">
                <label class="text-xs text-gray-500 uppercase font-bold tracking-wider">Mã băm hiện tại (Hệ thống)</label>
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 mt-1 font-mono text-[10px] sm:text-xs text-gray-600 break-all">
                    {{ $mysqlHash }}
                </div>
            </div>

            <div>
                <label class="text-xs text-gray-500 uppercase font-bold tracking-wider">Mã băm gốc (Blockchain)</label>
                <div class="bg-emerald-50 p-3 rounded-lg border border-emerald-200 mt-1 font-mono text-[10px] sm:text-xs text-emerald-700 break-all font-semibold">
                    {{ $blockchainHash ?? 'Không tìm thấy dữ liệu trên Blockchain' }}
                </div>
            </div>
        </div>
        
    </main>
</body>
</html>