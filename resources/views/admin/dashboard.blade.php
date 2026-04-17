<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden">

    <aside class="w-64 bg-[#0A2540] text-white flex flex-col shadow-xl z-20">
        <div class="h-20 flex items-center px-6 border-b border-white/10">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center font-bold text-lg mr-3">
                <i class="fa-solid fa-qrcode"></i>
            </div>
            <span class="text-2xl font-bold tracking-tight">MD<span class="text-emerald-400">Trace</span></span>
        </div>

        <nav class="flex-1 py-6 px-4 space-y-2">
            <a href="{{ route('admin.dashboard.stats') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition shadow-sm {{ Route::is('admin.dashboard.stats') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i>
                <span class="font-semibold">Tổng quan hệ thống</span>
            </a>
            
            <div x-data="{ open: {{ (Route::is('admin.dashboard') || Route::is('admin.enterprises.active')) ? 'true' : 'false' }} }">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-building-shield w-5 text-center {{ (Route::is('admin.dashboard') || Route::is('admin.enterprises.active')) ? 'text-emerald-400' : 'group-hover:text-emerald-400' }}"></i>
                        <span class="font-semibold {{ (Route::is('admin.dashboard') || Route::is('admin.enterprises.active')) ? 'text-white' : '' }}">Quản lý Doanh nghiệp</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition.origin.top class="mt-2 ml-6 space-y-1 border-l border-emerald-500/30 pl-4" style="display: {{ (Route::is('admin.dashboard') || Route::is('admin.enterprises.active')) ? 'block' : 'none' }};">
                   <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition {{ Route::is('admin.dashboard') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-solid fa-clipboard-question w-4 text-center {{ Route::is('admin.dashboard') ? 'text-emerald-400' : 'text-amber-500/70' }}"></i>
                        <span>Duyệt hồ sơ mới</span>
                    </a>

                    <a href="{{ route('admin.enterprises.active') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition {{ Route::is('admin.enterprises.active') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-solid fa-building-circle-check w-4 text-center {{ Route::is('admin.enterprises.active') ? 'text-emerald-400' : 'text-blue-400/70' }}"></i>
                        <span>Đang hoạt động</span>
                    </a>
                </div>
            </div>
            
            <div x-data="{ open: {{ (Route::is('admin.categories.*') || Route::is('admin.block_explorer')) ? 'true' : 'false' }} }" class="mt-4">
                <button @click="open = !open" 
                        class="w-full flex items-center justify-between gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition group">
                    <div class="flex items-center gap-3">
                        <i class="fa-solid fa-boxes-stacked w-5 text-center group-hover:text-emerald-400 {{ (Route::is('admin.categories.*') || Route::is('admin.block_explorer')) ? 'text-emerald-400' : '' }}"></i>
                        <span class="font-semibold {{ (Route::is('admin.categories.*') || Route::is('admin.block_explorer')) ? 'text-white' : '' }}">Quản lý Sản phẩm</span>
                    </div>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" x-transition.origin.top class="mt-2 ml-6 space-y-1 border-l border-emerald-500/30 pl-4" style="display: {{ (Route::is('admin.categories.*') || Route::is('admin.block_explorer')) ? 'block' : 'none' }};">
                   <a href="{{ route('admin.categories.index') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition {{ Route::is('admin.categories.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-solid fa-layer-group w-4 text-center {{ Route::is('admin.categories.*') ? 'text-emerald-400' : 'text-indigo-400/70' }}"></i>
                        <span>Danh mục sản phẩm</span>
                    </a>

                    <a href="{{ route('admin.block_explorer') }}" 
                       class="flex items-center gap-2 py-2 text-sm transition {{ Route::is('admin.block_explorer') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-brands fa-hive w-4 text-center {{ Route::is('admin.block_explorer') ? 'text-emerald-400' : 'text-amber-400/70' }}"></i>
                        <span>Sổ cái Blockchain</span>
                    </a>
                </div>
            </div>
            
            <a href="{{ route('admin.scans.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition mt-4 {{ Route::is('admin.scans.index') ? 'bg-emerald-600 text-white shadow-sm' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i>
                <span class="font-semibold">Lịch sử quét mã</span>
            </a>

            <a href="{{ route('admin.profile') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-xl transition shadow-sm mt-4 {{ Route::is('admin.profile') ? 'bg-emerald-600 text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                <i class="fa-solid fa-user-gear w-5 text-center"></i>
                <span class="font-semibold">Hồ sơ cá nhân</span>
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-3 text-gray-400 hover:text-red-400 hover:bg-red-400/10 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-right-from-bracket w-5"></i>
                    <span class="font-medium">Đăng xuất</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        
        <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 z-10">
            <div class="text-xl font-bold text-gray-800">Doanh nghiệp chờ duyệt</div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-900">Quản trị viên</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-emerald-500/20 shadow-sm flex items-center justify-center bg-gray-100">
                    @if(Auth::user()->profile && Auth::user()->profile->logo_url)
                        {{-- Dùng asset kết hợp với storage để đảm bảo đường dẫn tuyệt đối --}}
                        <img src="{{ asset('storage/' . Auth::user()->profile->logo_url) }}" 
                             alt="Admin Avatar" 
                             class="w-full h-full object-cover">
                    @else
                        {{-- Hiện icon nếu không có ảnh hoặc profile bị null --}}
                        <i class="fa-solid fa-user-shield text-gray-400"></i>
                    @endif
                </div>
            </div>
        </header>
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            
            {{-- Hiển thị thông báo thành công nếu duyệt OK --}}
            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-xl"></i>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
           {{-- CHÈN THÊM CỤM NÀY VÀO: THÔNG BÁO LỖI (MÀU ĐỎ) --}}
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                    <h2 class="font-bold text-gray-800 text-lg">Danh sách đăng ký mới</h2>
                    <span class="bg-amber-100 text-amber-700 text-xs font-bold px-3 py-1 rounded-full">
                        {{ $pendingEnterprises->count() }} chờ duyệt
                    </span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-200">
                                <th class="px-6 py-4 font-semibold">Tên doanh nghiệp</th>
                                <th class="px-6 py-4 font-semibold">Mã số thuế</th>
                                <th class="px-6 py-4 font-semibold">Tài khoản Email</th>
                                <th class="px-6 py-4 font-semibold">Ngày đăng ký</th>
                                <th class="px-6 py-4 font-semibold text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($pendingEnterprises as $enterprise)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800">{{ $enterprise->profile->company_name ?? 'Chưa cập nhật' }}</div>
                                        <div class="text-xs text-gray-500 mt-1"><i class="fa-solid fa-phone mr-1"></i> {{ $enterprise->profile->phone ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-600">
                                        <div class="flex items-center gap-2">
                                            <span class="font-medium text-gray-700">{{ $enterprise->profile->tax_code ?? 'N/A' }}</span>
                                            @if(!empty($enterprise->profile->tax_code))
                                                <button type="button" onclick="traCuuMST('{{ $enterprise->profile->tax_code }}', '{{ $enterprise->profile->company_name ?? $enterprise->name }}')" class="bg-blue-50 text-blue-600 px-2 py-1 rounded text-xs font-bold hover:bg-blue-100 transition shadow-sm border border-blue-100">
                                                    <i class="fa-solid fa-magnifying-glass"></i> Tra cứu
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-gray-600">
                                        {{ $enterprise->email }}
                                    </td>
                                    <td class="px-6 py-4 text-gray-500 text-sm">
                                        {{ $enterprise->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center gap-2">
                                            {{-- Nút Duyệt --}}
                                            <form action="{{ route('admin.enterprise.approve', $enterprise->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn duyệt?')" class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-2 rounded-lg text-sm font-semibold transition shadow-sm flex items-center gap-1">
                                                    <i class="fa-solid fa-check"></i> Duyệt
                                                </button>
                                            </form>

                                            {{-- Nút Từ chối (Mở hộp thoại) --}}
                                            <button type="button" onclick="openRejectModal({{ $enterprise->id }}, '{{ $enterprise->profile->company_name ?? $enterprise->email }}')" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-lg text-sm font-semibold transition shadow-sm flex items-center gap-1">
                                                <i class="fa-solid fa-xmark"></i> Từ chối
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
                                            <i class="fa-solid fa-inbox"></i>
                                        </div>
                                        <p>Không có doanh nghiệp nào đang chờ duyệt.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- <div class="bg-white rounded-xl shadow-sm border border-gray-200 mt-8 mb-8">
                <div class="p-5 border-b border-gray-200 flex justify-between items-center bg-gray-50 rounded-t-xl">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="fa-solid fa-link text-emerald-600"></i> MDTrace Block Explorer
                    </h3>
                    <div class="flex items-center gap-2">
                        <span class="flex h-3 w-3 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                        </span>
                        <span class="text-xs text-emerald-700 font-bold uppercase tracking-wider">Live Network</span>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-white text-gray-500 text-[11px] uppercase tracking-wider border-b border-gray-100">
                                <th class="p-4 font-bold">Mã Giao dịch (Txn Hash)</th>
                                <th class="p-4 font-bold">Mã Lô hàng</th>
                                <th class="p-4 font-bold">Sản phẩm</th>
                                <th class="p-4 font-bold">Thời gian</th>
                                <th class="p-4 font-bold text-center">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse($recentBlocks as $block)
                            <tr class="hover:bg-slate-50 transition cursor-default">
                                <td class="p-4 font-mono text-emerald-600 font-bold text-xs">
                                    <div class="flex items-center gap-2" title="{{ $block->transaction_hash }}">
                                        <i class="fa-brands fa-hive text-gray-300"></i> 
                                        {{ substr($block->transaction_hash, 0, 16) }}...
                                    </div>
                                </td>
                                <td class="p-4 font-bold text-slate-700 text-xs">
                                    {{ $block->batch->batch_code ?? 'N/A' }}
                                </td>
                                <td class="p-4 text-gray-600 text-xs truncate max-w-[200px]" title="{{ $block->batch->product->name ?? '' }}">
                                    {{ $block->batch->product->name ?? 'Dữ liệu trống' }}
                                </td>
                                <td class="p-4 text-gray-500 text-[11px] font-medium">
                                    {{ $block->created_at->diffForHumans() }}
                                </td>
                                <td class="p-4 text-center">
                                    <span class="bg-emerald-100 text-emerald-700 text-[10px] font-bold px-3 py-1 rounded-full border border-emerald-200">
                                        ĐÃ MINT
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fa-solid fa-boxes-packing text-4xl opacity-20"></i>
                                        <p class="italic text-sm">Sổ cái hiện đang trống. Chưa có lô hàng nào được đóng gói lên chuỗi.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div> -->
        </main>
    </div>

    <div id="rejectModal" class="fixed inset-0 bg-black/60 hidden items-center justify-center z-50 backdrop-blur-sm transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg p-6 transform transition-all">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Từ chối doanh nghiệp</h3>
                <button onclick="closeRejectModal()" class="text-gray-400 hover:text-red-500 text-xl"><i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <p class="text-sm text-gray-500 mb-5">Bạn đang thực hiện từ chối tài khoản: <strong id="rejectCompanyName" class="text-gray-800 text-base"></strong></p>

            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Lý do từ chối <span class="text-red-500">*</span></label>
                    <textarea name="reason" rows="4" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:border-red-500 focus:ring-1 focus:ring-red-500" placeholder="Nhập lý do chi tiết để doanh nghiệp có thể khắc phục..."></textarea>
                    <p class="text-xs text-gray-400 mt-2">Lý do này sẽ được lưu lại trong hệ thống.</p>
                </div>
                <div class="flex justify-end gap-3 pt-2 border-t border-gray-100">
                    <button type="button" onclick="closeRejectModal()" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-xl transition">Hủy bỏ</button>
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-xl font-semibold transition shadow-md flex items-center gap-2">
                        <i class="fa-solid fa-ban"></i> Xác nhận Từ chối
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="mstModal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden transform transition-transform scale-95" id="mstModalContent">
        <div class="bg-blue-600 p-4 flex justify-between items-center text-white">
            <h3 class="font-bold text-lg"><i class="fa-solid fa-building-shield mr-2"></i>Kết quả tra cứu từ Tổng cục Thuế</h3>
            <button onclick="closeMstModal()" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
        </div>
        <div class="p-6">
            <div id="mstLoading" class="text-center py-8">
                <i class="fa-solid fa-spinner fa-spin text-4xl text-blue-500 mb-3"></i>
                <p class="text-gray-500">Đang kết nối cơ sở dữ liệu quốc gia...</p>
            </div>
            
            <div id="mstResult" class="hidden space-y-4">
                <div class="bg-gray-50 p-3 rounded-lg border border-gray-100">
                    <p class="text-xs text-gray-500 font-bold uppercase mb-1">Doanh nghiệp khai báo:</p>
                    <p class="font-bold text-slate-800" id="txtDnkhaibao">...</p>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                    <p class="text-xs text-blue-600 font-bold uppercase mb-1">Dữ liệu pháp lý (VietQR API):</p>
                    <h4 class="font-bold text-lg text-slate-900 mb-2" id="txtTenCongTy">...</h4>
                    <p class="text-sm text-gray-700"><i class="fa-solid fa-location-dot text-gray-400 mr-2"></i><span id="txtDiaChi">...</span></p>
                </div>
            </div>
            
            <div id="mstError" class="hidden text-center py-6">
                <i class="fa-solid fa-triangle-exclamation text-4xl text-red-500 mb-3"></i>
                <p class="text-red-600 font-medium">Không tìm thấy dữ liệu. Mã số thuế không tồn tại hoặc sai định dạng!</p>
            </div>
        </div>
        <div class="bg-gray-50 p-4 text-right border-t border-gray-100">
            <button onclick="closeMstModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg font-medium transition">Đóng</button>
        </div>
    </div>
</div>

<script>
     function openRejectModal(id, companyName) {
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
            document.getElementById('rejectCompanyName').innerText = companyName;
            // Cập nhật đường dẫn action của Form cho đúng ID doanh nghiệp
            document.getElementById('rejectForm').action = '/admin/enterprise/' + id + '/reject';
        }

    function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
        }

    function traCuuMST(taxCode, dnName) {
        // Mở Modal và hiện trạng thái Loading
        document.getElementById('mstModal').classList.remove('hidden');
        document.getElementById('mstLoading').classList.remove('hidden');
        document.getElementById('mstResult').classList.add('hidden');
        document.getElementById('mstError').classList.add('hidden');
        document.getElementById('txtDnkhaibao').innerText = dnName;

        // Gọi API của VietQR
        fetch(`https://api.vietqr.io/v2/business/${taxCode}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('mstLoading').classList.add('hidden');
                
                // Mã 00 là thành công của VietQR
                if (data.code === '00' && data.data) {
                    document.getElementById('mstResult').classList.remove('hidden');
                    document.getElementById('txtTenCongTy').innerText = data.data.name;
                    document.getElementById('txtDiaChi').innerText = data.data.address;
                } else {
                    document.getElementById('mstError').classList.remove('hidden');
                }
            })
            .catch(error => {
                document.getElementById('mstLoading').classList.add('hidden');
                document.getElementById('mstError').classList.remove('hidden');
            });
    }

    function closeMstModal() {
        document.getElementById('mstModal').classList.add('hidden');
    }
</script>
</body>
</html>