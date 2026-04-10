<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            <a href="#" class="flex items-center gap-3 bg-emerald-500/20 text-emerald-400 px-4 py-3 rounded-xl transition">
                <i class="fa-solid fa-building w-5"></i>
                <span class="font-medium">Quản lý doanh nghiệp</span>
            </a>
            <a href="#" class="flex items-center gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition">
                <i class="fa-solid fa-layer-group w-5"></i>
                <span class="font-medium">Quản lý danh mục</span>
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
                <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center text-gray-600">
                    <i class="fa-solid fa-user"></i>
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
            {{-- Hiển thị báo lỗi validate --}}
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 shadow-sm">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
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
                                        {{ $enterprise->profile->tax_code ?? 'N/A' }}
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
    </script>
</body>
</html>