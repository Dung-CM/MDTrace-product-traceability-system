@extends('layouts.app')

@section('title', $product->name)

@php
    // Giải mã dữ liệu JSON để sử dụng
    $origin = $product->origin_info ?? [];
    $details = $product->product_details ?? [];
    $comp = $product->company_info ?? [];
    $dist = $product->distributor_info ?? [];
    $logs = $product->trace_logs ?? [];
    $materials = $origin['materials'] ?? [];

    
    $profile = \App\Models\UserProfile::where('user_id', $product->user_id)->first();
    
    $cName = !empty($comp['company_name']) ? $comp['company_name'] : ($profile->company_name ?? $product->company_name);
    $cPhone = !empty($comp['phone']) ? $comp['phone'] : ($profile->phone ?? '');
    $cEmail = !empty($comp['email']) ? $comp['email'] : ($profile->contact_email ?? '');
    $cWeb = !empty($comp['website']) ? $comp['website'] : ($profile->website_url ?? '');
    $cAddress = !empty($comp['address']) ? $comp['address'] : ($profile->address ?? '');
    $cMap = !empty($comp['map_link']) ? $comp['map_link'] : ($profile->map_link ?? '');

    // ==========================================
    // LOGIC MỚI: XỬ LÝ DỮ LIỆU LÔ HÀNG (GS1)
    // ==========================================
    // Nếu khách quét QR, biến $batch sẽ tồn tại. Nếu khách xem chay, lấy dữ liệu gốc của Product.
    $displayMfg = isset($batch) ? $batch->manufacturing_date : $product->mfg_date;
    $displayExp = isset($batch) ? $batch->expiry_date : $product->exp_date;
    $displayBatchCode = isset($batch) ? $batch->batch_code : ($product->batch_code ?? '---');
    $displayGtin = $product->gtin_code ?? '0000000000000';
    
    // Tính toán chuỗi GS1 để hiển thị
    $expYYMMDD = $displayExp ? \Carbon\Carbon::parse($displayExp)->format('ymd') : '000000';
    $gs1String = "(01){$displayGtin}(10){$displayBatchCode}(17){$expYYMMDD}";

    if (!function_exists('getSecureImageUrl')) {
        function getSecureImageUrl($path) {
            if (empty($path)) {
                return ''; 
            }

            $baseUrl = rtrim(env('APP_URL'), '/');
            
            $cleanPath = str_replace(['public/', 'storage/'], '', $path);
            $cleanPath = ltrim($cleanPath, '/');
            
            $fullUrl = $baseUrl . '/storage/' . $cleanPath;
            $fullUrl = str_replace('http://', 'https://', $fullUrl);

            return $fullUrl;
        }
    }
@endphp

@section('content')
<div class="bg-gray-50 min-h-screen pb-12">
    <div class="relative w-full h-80 md:h-[500px] bg-white overflow-hidden shadow-sm">
        @if($product->image_url)
            <img src="{{ getSecureImageUrl($product->image_url) }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
        @else
            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                <i class="fa-solid fa-image text-gray-300 text-8xl"></i>
            </div>
        @endif
        
        <a href="{{ route('public.products.index') }}" class="absolute top-4 left-4 bg-white/80 backdrop-blur p-2 rounded-full shadow-md text-gray-700 hover:text-emerald-600 transition">
            <i class="fa-solid fa-chevron-left text-xl"></i>
        </a>
    </div>

   <div class="max-w-4xl mx-auto px-4 -mt-16 relative z-10">
        <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8 border border-gray-100">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-slate-900 mb-2">{{ $product->name }}</h1>
                    <p class="text-emerald-600 font-bold uppercase tracking-wide flex items-center gap-2">
                        <i class="fa-solid fa-building"></i> {{ $cName }}
                    </p>
                </div>
                @if($product->is_authentic)
                <div class="inline-flex items-center gap-2 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-full border border-emerald-100 font-bold text-sm self-start">
                    <i class="fa-solid fa-shield-check text-lg"></i>
                    Sản phẩm chính hãng
                </div>
                @endif
            </div>

            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-6">
                <p class="text-xs text-gray-500 font-bold uppercase mb-1">Mã truy xuất hệ thống (GS1 Standard)</p>
                <p class="text-lg md:text-xl font-mono font-bold text-slate-800 break-all">{{ $gs1String }}</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 pt-4 border-t border-gray-100">
                <div class="text-center p-3">
                    <span class="block text-[10px] uppercase text-gray-500 font-bold mb-1">Ngày sản xuất</span>
                    <span class="text-sm font-bold text-slate-800">{{ $displayMfg ? date('d/m/Y', strtotime($displayMfg)) : '---' }}</span>
                </div>
                <div class="text-center p-3 border-l border-gray-100">
                    <span class="block text-[10px] uppercase text-gray-500 font-bold mb-1">Hạn sử dụng</span>
                    <span class="text-sm font-bold text-slate-800">{{ $displayExp ? date('d/m/Y', strtotime($displayExp)) : '---' }}</span>
                </div>
                <div class="text-center p-3 md:border-l border-gray-100">
                    <span class="block text-[10px] uppercase text-gray-500 font-bold mb-1">Mã GTIN</span>
                    <span class="text-sm font-bold text-slate-800">{{ $displayGtin }}</span>
                </div>
                <div class="text-center p-3 border-l border-gray-100">
                    <span class="block text-[10px] uppercase text-gray-500 font-bold mb-1">Số lô hàng</span>
                    <span class="text-sm font-bold text-slate-800">{{ $displayBatchCode }}</span>
                </div>
            </div>

            @if(isset($batch))
                @php
                    $transaction = \App\Models\BlockchainTransaction::where('batch_id', $batch->id)->first();
                @endphp

                @if($transaction)
                    <div class="mt-5 p-4 border-2 border-emerald-500 rounded-2xl bg-emerald-50 relative overflow-hidden shadow-sm">
                        <div class="absolute -right-6 -top-6 opacity-10 rotate-12">
                            <i class="fa-solid fa-shield-check text-9xl text-emerald-600"></i>
                        </div>

                        <div class="flex items-center gap-3 relative z-10">
                            <div class="w-12 h-12 bg-emerald-500 text-white rounded-full flex items-center justify-center text-2xl shadow-lg shadow-emerald-200 shrink-0">
                                <i class="fa-solid fa-link"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h4 class="font-bold text-emerald-800 uppercase text-sm tracking-wider">Dữ liệu đã xác thực</h4>
                                <p class="text-xs text-emerald-600 font-mono mt-0.5 truncate" title="{{ $transaction->transaction_hash }}">Hash: {{ $transaction->transaction_hash }}</p>
                            </div>
                        </div>
                        
                        <div class="mt-3 pt-3 border-t border-emerald-200/60 text-[11px] text-emerald-700 leading-relaxed italic relative z-10">
                            <i class="fa-solid fa-circle-check"></i> Dữ liệu lô hàng này đã được niêm phong bất biến tại mạng lưới MDTrace IPFS vào lúc {{ $transaction->created_at->format('H:i d/m/Y') }}.
                        </div>
                    </div>
                @endif
            @endif
            </div>

        <div class="mt-8">
            <div class="flex overflow-x-auto no-scrollbar gap-2 bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100 sticky top-4 z-20">
                <button onclick="switchTab('timeline')" id="btn-timeline" class="tab-btn active px-6 py-3 rounded-xl font-bold text-sm whitespace-nowrap transition-all flex items-center gap-2">
                    <i class="fa-solid fa-route"></i> Nhật ký
                </button>
                <button onclick="switchTab('product')" id="btn-product" class="tab-btn px-6 py-3 rounded-xl font-bold text-sm whitespace-nowrap transition-all flex items-center gap-2">
                    <i class="fa-solid fa-box-open"></i> Sản phẩm
                </button>
                <button onclick="switchTab('company')" id="btn-company" class="tab-btn px-6 py-3 rounded-xl font-bold text-sm whitespace-nowrap transition-all flex items-center gap-2">
                    <i class="fa-solid fa-building-circle-check"></i> Đơn vị
                </button>
                <button onclick="switchTab('cert')" id="btn-cert" class="tab-btn px-6 py-3 rounded-xl font-bold text-sm whitespace-nowrap transition-all flex items-center gap-2">
                    <i class="fa-solid fa-certificate"></i> Chứng nhận
                </button>
            </div>

            <div class="mt-6">
                <div id="tab-timeline" class="tab-content space-y-4">
                    @forelse($logs as $log)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center shadow-lg shadow-emerald-200 z-10">
                                <i class="fa-solid fa-check text-xs"></i>
                            </div>
                            @if(!$loop->last)
                                <div class="w-0.5 flex-1 bg-emerald-200 my-1"></div>
                            @endif
                        </div>
                        <div class="flex-1 bg-white rounded-2xl p-5 shadow-sm border border-gray-100 mb-4">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-bold text-slate-800 text-lg uppercase">{{ $log['name'] }}</h4>
                                <span class="text-[11px] font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">
                                    {{ isset($log['start_time']) ? date('H:i d/m/Y', strtotime($log['start_time'])) : '' }}
                                </span>
                            </div>
                            <p class="text-gray-600 text-sm mb-4">{{ $log['description'] ?? '' }}</p>
                            
                            @if(!empty($log['image']))
                            <div class="mb-4">
                               <img src="{{ getSecureImageUrl($log['image']) }}" onclick="viewImage(this.src)" class="rounded-xl w-full max-h-60 object-cover border border-gray-100 cursor-zoom-in hover:opacity-90 transition shadow-sm">
                            </div>
                            @endif

                            <div class="grid grid-cols-2 gap-3 text-[11px] text-gray-500 font-medium">
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-user-gear text-emerald-500"></i> {{ $log['person'] ?? 'N/A' }}
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <i class="fa-solid fa-location-dot text-emerald-500"></i> {{ $log['location'] ?? 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-center py-10 text-gray-400 italic">Dữ liệu nhật ký đang được cập nhật...</p>
                    @endforelse
                </div>

                <div id="tab-product" class="tab-content hidden space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2 border-b pb-3">
                            <i class="fa-solid fa-seedling text-emerald-600"></i> Nguồn gốc nguyên liệu
                        </h4>
                        <div class="space-y-4">
                            <p class="text-sm"><strong>Nhà cung cấp:</strong> {{ $origin['supplier_name'] ?? 'Đang cập nhật' }}</p>
                            <p class="text-sm"><strong>Vùng nguyên liệu:</strong> {{ $origin['supplier_address'] ?? 'Đang cập nhật' }}</p>
                            
                            <div class="overflow-x-auto">
                                <table class="w-full text-xs text-left">
                                    <thead class="bg-gray-50 text-gray-500 uppercase font-bold">
                                        <tr>
                                            <th class="px-3 py-2">Nguyên liệu</th>
                                            <th class="px-3 py-2">Mã lô/NSX</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @foreach($materials as $mat)
                                        <tr>
                                            <td class="px-3 py-3 font-bold text-slate-700">{{ $mat['name'] }}</td>
                                            <td class="px-3 py-3 text-gray-500">{{ $mat['batch'] ?? '' }} ({{ $mat['mfg'] ?? '' }})</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 ck-content">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2 border-b pb-3">
                            <i class="fa-solid fa-circle-info text-emerald-600"></i> Đặc điểm & Hướng dẫn
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm mb-6">
                            <p><strong>Loại sản phẩm:</strong> {{ $details['product_type'] ?? '' }}</p>
                            <p><strong>Thương hiệu:</strong> {{ $details['brand_name'] ?? '' }}</p>
                            <p><strong>Quy cách:</strong> {{ $details['weight'] ?? '' }}</p>
                            <p><strong>Chất lượng:</strong> {{ $details['quality_criteria'] ?? '' }}</p>
                        </div>
                        <div class="prose prose-emerald max-w-none">
                            {!! $details['detailed_introduction'] ?? '' !!}
                        </div>
                    </div>
                </div>

                <div id="tab-company" class="tab-content hidden space-y-6">
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h4 class="font-bold text-slate-800 mb-6 flex items-center gap-2 border-b pb-3 text-emerald-700">
                            <i class="fa-solid fa-building-circle-check"></i> Đơn vị sản xuất
                        </h4>
                       <div class="space-y-4">
                            <h5 class="text-xl font-bold text-slate-900">{{ $cName }}</h5>
                            <div class="grid grid-cols-1 gap-3 text-sm text-gray-600">
                                @if($cAddress)<p class="flex items-start gap-3"><i class="fa-solid fa-location-dot w-5 mt-1 text-emerald-500"></i> {{ $cAddress }}</p>@endif
                                @if($cPhone)<p class="flex items-center gap-3"><i class="fa-solid fa-phone w-5 text-emerald-500"></i> {{ $cPhone }}</p>@endif
                                @if($cEmail)<p class="flex items-center gap-3"><i class="fa-solid fa-envelope w-5 text-emerald-500"></i> {{ $cEmail }}</p>@endif
                                @if($cWeb)<p class="flex items-center gap-3"><i class="fa-solid fa-globe w-5 text-emerald-500"></i> <a href="{{ $cWeb }}" target="_blank" class="text-blue-600 underline hover:text-blue-800">{{ $cWeb }}</a></p>@endif
                            </div>
                            
                            @if(!empty($cMap))
                            <div class="rounded-2xl overflow-hidden mt-4 shadow-inner border border-gray-200 h-64">
                                {!! $cMap !!}
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-orange-100 bg-orange-50/30">
                        <h4 class="font-bold text-orange-800 mb-4 flex items-center gap-2 border-b border-orange-100 pb-3 uppercase text-xs">
                            <i class="fa-solid fa-truck-fast"></i> Đơn vị phân phối / Điểm bán
                        </h4>
                        <div class="space-y-2 text-sm text-slate-700">
                            <p class="font-bold text-base">{{ $dist['name'] ?? 'Đang cập nhật' }}</p>
                            <p><i class="fa-solid fa-map-pin mr-2 text-orange-400"></i>{{ $dist['address'] ?? '' }}</p>
                            <p><i class="fa-solid fa-calendar-day mr-2 text-orange-400"></i>Xuất kho: {{ $dist['date'] ?? '' }}</p>
                            <p><i class="fa-solid fa-snowflake mr-2 text-orange-400"></i>Bảo quản: {{ $dist['storage'] ?? '' }}</p>
                        </div>
                    </div>
                </div>

                <div id="tab-cert" class="tab-content hidden grid grid-cols-1 md:grid-cols-2 gap-4">
                    @php $certs = $product->certificates ?? []; @endphp
                    @forelse($certs as $cert)
                    <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex flex-col items-center">
                       <img src="{{ getSecureImageUrl($cert) }}" class="w-full h-auto rounded-lg mb-3 shadow-sm hover:scale-[1.02] transition cursor-zoom-in" onclick="window.open(this.src)">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter">Văn bản kiểm định / Chứng nhận</span>
                    </div>
                    @empty
                    <div class="col-span-full py-12 text-center text-gray-400">
                        <i class="fa-solid fa-stamp text-5xl mb-3 opacity-20"></i>
                        <p>Sản phẩm đang trong quá trình cập nhật hồ sơ chứng nhận.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="mt-12 py-8 border-t border-gray-200 text-center">
        <p class="text-xs text-gray-400 uppercase font-bold tracking-widest mb-1">Cung cấp bởi hệ thống truy xuất</p>
        <div class="flex items-center justify-center gap-2 font-bold text-emerald-700 text-lg">
            <i class="fa-solid fa-circle-nodes"></i> MDTrace Blockchain
        </div>
    </div>
</div>

<div id="imageViewer" class="hidden fixed inset-0 z-[100] bg-black/95 flex flex-col items-center justify-center p-4 backdrop-blur-md transition-opacity opacity-0" style="transition: opacity 0.3s ease;">
        <button onclick="closeImageViewer()" class="absolute top-4 right-4 md:top-8 md:right-8 text-white/50 hover:text-white text-5xl cursor-pointer z-[101] transition">&times;</button>
        <img id="viewerImage" src="" class="max-w-full max-h-[85vh] object-contain rounded-xl shadow-2xl transform scale-95 transition-transform duration-300">
        <p class="text-white/80 font-medium text-sm mt-4 bg-white/10 px-6 py-2 rounded-full border border-white/20">Xem ảnh minh chứng</p>
    </div>
<style>
    /* CSS Tối ưu cho giao diện App */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .tab-btn { color: #64748b; background: transparent; }
    .tab-btn.active { color: #ffffff; background: #059669; box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.3); }
    
    .ck-content ul { list-style-type: disc; padding-left: 1.5rem; margin-bottom: 1rem; }
    .ck-content ol { list-style-type: decimal; padding-left: 1.5rem; margin-bottom: 1rem; }
    .prose img { border-radius: 1rem; margin: 1rem 0; }
</style>

<script>
    // Hàm chuyển đổi Tab không load lại trang
    function switchTab(tabName) {
        // Ẩn tất cả content
        document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
        // Hiện content được chọn
        document.getElementById('tab-' + tabName).classList.remove('hidden');
        
        // Cập nhật trạng thái nút
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
        document.getElementById('btn-' + tabName).classList.add('active');

        // Tự động cuộn mượt đến đầu tab (nếu trên mobile)
        window.scrollTo({ top: document.querySelector('.sticky').offsetTop - 20, behavior: 'smooth' });
    }
    // === HÀM XỬ LÝ PHÓNG TO ẢNH ===
    function viewImage(src) {
        const viewer = document.getElementById('imageViewer');
        const img = document.getElementById('viewerImage');
        img.src = src;
        viewer.classList.remove('hidden');
        // Tạo độ trễ siêu nhỏ để hiệu ứng fade-in hoạt động mượt
        setTimeout(() => {
            viewer.classList.remove('opacity-0');
            img.classList.remove('scale-95');
            img.classList.add('scale-100');
        }, 10);
    }

    function closeImageViewer() {
        const viewer = document.getElementById('imageViewer');
        const img = document.getElementById('viewerImage');
        viewer.classList.add('opacity-0');
        img.classList.remove('scale-100');
        img.classList.add('scale-95');
        setTimeout(() => {
            viewer.classList.add('hidden');
            img.src = ""; // Xóa source để nhẹ RAM
        }, 300);
    }
</script>


@endsection