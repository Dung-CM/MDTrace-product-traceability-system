@extends('enterprise.layouts.app')

@section('title', 'Tổng quan hệ thống')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="space-y-6">

   <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        
        @if(!$isProfileComplete)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm w-full md:w-auto">
            <i class="fa-solid fa-triangle-exclamation text-yellow-500 text-lg"></i>
            <div class="text-sm">
                <span class="font-semibold">Hồ sơ chưa hoàn thiện.</span> Vui lòng cập nhật MST và Tên doanh nghiệp.
                <a href="{{ route('enterprise.profile.index') }}" class="underline font-semibold ml-1">Cập nhật ngay</a>
            </div>
        </div>
        @else
        <div class="text-gray-600 font-medium">
            <i class="fa-solid fa-hand-wave text-emerald-500 mr-2"></i> Chào mừng trở lại trung tâm quản lý!
        </div>
        @endif

        <div class="flex gap-3 w-full md:w-auto">
            <a href="{{ route('enterprise.products.create') }}" class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition flex items-center">
                <i class="fa-solid fa-box-open mr-1"></i> Thêm Sản phẩm
            </a>
            <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm transition opacity-50 cursor-not-allowed" title="Vui lòng hoàn thiện hồ sơ trước">
                <i class="fa-solid fa-qrcode mr-1"></i> Tạo Lô hàng mới
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-gray-500 text-sm font-medium mb-1">Tổng sản phẩm</div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <div class="text-gray-500 text-sm font-medium mb-1">Tổng số lô hàng</div>
            <div class="text-3xl font-bold text-gray-900">{{ $totalBatches }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-red-100 shadow-sm">
            <div class="text-red-500 text-sm font-medium mb-1">Sắp hết hạn (< 30 ngày)</div>
            <div class="text-3xl font-bold text-red-600">{{ $expiringBatches }}</div>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm">
            <p id="cardTitle" class="text-gray-500 text-sm font-medium mb-1">Lượt quét (6 Tháng)</p>
            <h3 id="totalScansCard" class="text-3xl font-bold text-gray-800">{{ number_format($scansThisMonth) }}</h3>
        </div>
        <div class="bg-gray-900 p-5 rounded-2xl shadow-sm text-white relative overflow-hidden">
            <div class="relative z-10">
                <div class="text-gray-400 text-sm font-medium mb-1">Blockchain & IPFS</div>
                <div class="text-emerald-400 font-semibold flex items-center gap-2 mt-2">
                    <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse"></span> Đang hoạt động
                </div>
            </div>
            <i class="fa-brands fa-hive absolute -right-4 -bottom-4 text-6xl text-gray-800 opacity-50 z-0"></i>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-800">Biểu đồ tương tác</h3>
                <select id="timeFilter" class="border border-gray-200 rounded-lg text-sm px-3 py-1.5 focus:ring-emerald-500 focus:border-emerald-500 outline-none text-gray-600">
                    <option value="week">7 Ngày qua</option>
                    <option value="month" selected>6 Tháng qua</option>
                    <option value="year">5 Năm qua</option>
                </select>
            </div>
            <canvas id="scanChart" height="100"></canvas> 
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="font-bold text-gray-800 mb-6">Top nền tảng quét mã</h3>
            <div class="space-y-5">
                <div>
                    <div class="flex justify-between text-sm mb-1"><span class="font-medium"><i class="fa-brands fa-android text-green-500 mr-1"></i> Android</span> <span class="text-gray-500">{{ $deviceStats['Android'] }} lượt</span></div>
                    <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-emerald-500 h-2 rounded-full transition-all duration-1000" style="width: {{ ($deviceStats['Android'] / $totalDeviceScans) * 100 }}%"></div></div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1"><span class="font-medium"><i class="fa-brands fa-apple text-gray-800 mr-1"></i> Apple iOS</span> <span class="text-gray-500">{{ $deviceStats['iOS'] }} lượt</span></div>
                    <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-gray-800 h-2 rounded-full transition-all duration-1000" style="width: {{ ($deviceStats['iOS'] / $totalDeviceScans) * 100 }}%"></div></div>
                </div>
                <div>
                    <div class="flex justify-between text-sm mb-1"><span class="font-medium"><i class="fa-solid fa-mobile-screen text-indigo-500 mr-1"></i> Nền tảng khác</span> <span class="text-gray-500">{{ $deviceStats['Khác'] }} lượt</span></div>
                    <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-indigo-500 h-2 rounded-full transition-all duration-1000" style="width: {{ ($deviceStats['Khác'] / $totalDeviceScans) * 100 }}%"></div></div>
                </div>
            </div>
        </div>
    </div> 

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-2">
        
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Lô hàng gần đây</h3>
                <a href="{{ route('enterprise.batches.index') }}" class="text-sm text-emerald-600 hover:underline">Xem tất cả</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-6 py-3 font-medium">Mã lô (Mô phỏng Hash)</th>
                            <th class="px-6 py-3 font-medium">Sản phẩm</th>
                            <th class="px-6 py-3 font-medium">Ngày tạo</th>
                            <th class="px-6 py-3 font-medium">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentBatches as $batch)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500">0x{{ substr(md5($batch->id . $batch->batch_code), 0, 8) }}...</td>
                            <td class="px-6 py-4 font-medium text-gray-800">{{ $batch->product->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-gray-500">{{ \Carbon\Carbon::parse($batch->created_at)->format('d/m/Y') }}</td>
                            <td class="px-6 py-4"><span class="bg-emerald-100 text-emerald-700 px-2 py-1 rounded-md text-xs font-semibold">Đã tạo QR</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-6 py-4 text-center text-gray-500">Chưa có lô hàng nào.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
            <h3 class="font-bold text-gray-800 mb-6">Nhật ký hoạt động</h3>
            <div class="space-y-4 relative before:absolute before:inset-0 before:ml-2 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                
                @forelse($activities as $activity)
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                    <div class="flex items-center justify-center w-4 h-4 rounded-full border border-white {{ $activity['color'] }} text-gray-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2"></div>
                    <div class="w-[calc(100%-2rem)] md:w-[calc(50%-1rem)] p-3 rounded-lg bg-gray-50 border border-gray-100 shadow-sm hover:shadow-md transition">
                        <div class="text-xs text-gray-500 mb-1">{{ \Carbon\Carbon::parse($activity['time'])->diffForHumans() }}</div>
                        <div class="text-sm font-medium text-gray-800">{{ $activity['text'] }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 text-sm mt-4">Chưa có hoạt động nào.</div>
                @endforelse
                
            </div>
        </div>
        
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx = document.getElementById('scanChart').getContext('2d');
    
    // 1. Khởi tạo biểu đồ rỗng
    let scanChart = new Chart(ctx, {
        type: 'bar',
        data: { labels: [], datasets: [{ label: 'Lượt quét', data: [], backgroundColor: '#10b981', borderRadius: 4, barThickness: 40 }] },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                x: { grid: { display: false } }
            }
        }
    });

    // 2. Hàm gọi API lấy dữ liệu
    function updateChartData(filterType) {
        fetch(`{{ route('enterprise.dashboard.chart') }}?filter=${filterType}`)
            .then(response => response.json())
            .then(res => {
                scanChart.data.labels = res.labels;
                scanChart.data.datasets[0].data = res.data;
                scanChart.update(); 

                document.getElementById('totalScansCard').innerText = res.total_scans;
                
                let title = filterType === 'week' ? 'Lượt quét (7 Ngày)' : (filterType === 'month' ? 'Lượt quét (6 Tháng)' : 'Lượt quét (5 Năm)');
                document.getElementById('cardTitle').innerText = title;
            })
            .catch(error => console.error('Lỗi lấy dữ liệu:', error));
    }

    // 3. Lắng nghe sự kiện
    document.getElementById('timeFilter').addEventListener('change', function() {
        updateChartData(this.value);
    });

    // 4. Load dữ liệu mặc định
    window.addEventListener('DOMContentLoaded', () => {
        updateChartData('month');
    });
</script>
@endpush