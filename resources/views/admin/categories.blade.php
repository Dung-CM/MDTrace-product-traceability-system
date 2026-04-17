<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Danh mục - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden" x-data="{ showAddModal: false, showEditModal: false, editData: { id: '', name: '', description: '' } }">

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
                   <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 py-2 text-sm transition text-gray-400 hover:text-white">
                        <i class="fa-solid fa-clipboard-question w-4 text-center text-amber-500/70"></i><span>Duyệt hồ sơ mới</span>
                    </a>
                    <a href="{{ route('admin.enterprises.active') }}" class="flex items-center gap-2 py-2 text-sm transition text-gray-400 hover:text-white">
                        <i class="fa-solid fa-building-circle-check w-4 text-center text-blue-400/70"></i><span>Đang hoạt động</span>
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
                   <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-2 py-2 text-sm transition {{ Route::is('admin.categories.*') ? 'text-emerald-400 font-bold' : 'text-gray-400 hover:text-white' }}">
                        <i class="fa-solid fa-layer-group w-4 text-center {{ Route::is('admin.categories.*') ? 'text-emerald-400' : 'text-indigo-400/70' }}"></i><span>Danh mục sản phẩm</span>
                    </a>
                    <a href="{{ route('admin.block_explorer') }}" class="flex items-center gap-2 py-2 text-sm transition text-gray-400 hover:text-white">
                        <i class="fa-brands fa-hive w-4 text-center text-amber-400/70"></i><span>Sổ cái Blockchain</span>
                    </a>
                </div>
            </div>
            
            <a href="{{ route('admin.scans.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition mt-4 text-gray-400 hover:text-white hover:bg-white/5">
                <i class="fa-solid fa-clock-rotate-left w-5 text-center"></i><span class="font-semibold">Lịch sử quét mã</span>
            </a>

            <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition shadow-sm mt-4 text-gray-400 hover:text-white hover:bg-white/5">
                <i class="fa-solid fa-user-gear w-5 text-center"></i><span class="font-semibold">Hồ sơ cá nhân</span>
            </a>
        </nav>
        <div class="p-4 border-t border-white/10">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full flex items-center gap-3 text-gray-400 hover:text-red-400 hover:bg-red-400/10 px-4 py-3 rounded-xl transition">
                    <i class="fa-solid fa-right-from-bracket w-5"></i><span class="font-medium">Đăng xuất</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1 flex flex-col h-screen overflow-hidden relative">
        <header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-8 z-10">
            <div class="text-xl font-bold text-gray-800">Danh mục sản phẩm</div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-sm font-bold text-gray-900">Quản trị viên</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email ?? 'admin@mdtrace.com' }}</div>
                </div>
                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-emerald-500/20 shadow-sm flex items-center justify-center bg-gray-100">
                    @if(Auth::user()->profile && Auth::user()->profile->logo_url)
                        {{-- Laravel sẽ lấy ảnh từ thư mục storage/app/public --}}
                        <img src="{{ \Illuminate\Support\Facades\Storage::url(Auth::user()->profile->logo_url) }}" 
                             alt="Admin Avatar" 
                             class="w-full h-full object-cover">
                    @else
                        {{-- Nếu chưa có ảnh thì hiện icon mặc định --}}
                        <i class="fa-solid fa-user-shield text-gray-500"></i>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            
            {{-- Thông báo --}}
            @if(session('success'))
                <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-circle-check text-xl"></i><span class="font-medium">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-6 py-4 rounded-xl mb-6 flex items-center gap-3 shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i><span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            <div class="flex justify-between items-end mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Quản lý Danh mục</h2>
                    <p class="text-sm text-gray-500 mt-1">Phân loại và tổ chức các nhóm sản phẩm trong hệ thống.</p>
                </div>
                <button @click="showAddModal = true" class="bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-600 transition shadow-sm flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Thêm danh mục
                </button>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider border-b border-gray-100">
                                <th class="px-6 py-4 font-bold w-16 text-center">ID</th>
                                <th class="px-6 py-4 font-bold">Tên danh mục</th>
                                <th class="px-6 py-4 font-bold">Mô tả</th>
                                <th class="px-6 py-4 font-bold w-48">Ngày tạo</th>
                                <th class="px-6 py-4 font-bold text-center w-32">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-gray-50">
                            @forelse($categories as $category)
                            <tr class="hover:bg-slate-50 transition cursor-default">
                                <td class="px-6 py-4 text-center font-mono text-gray-400 font-bold">#{{ $category->id }}</td>
                                <td class="px-6 py-4 font-bold text-emerald-700">{{ $category->name }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    @if($category->description)
                                        {{ $category->description }}
                                    @else
                                        <span class="italic text-gray-400 text-xs">Không có mô tả</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-500 text-xs font-medium">{{ $category->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center gap-3">
                                        <button @click="editData = { id: '{{ $category->id }}', name: '{{ addslashes($category->name) }}', description: '{{ addslashes($category->description) }}' }; showEditModal = true" 
                                                class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition shadow-sm" title="Sửa">
                                            <i class="fa-solid fa-pen-to-square text-xs"></i>
                                        </button>

                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này? Các sản phẩm thuộc danh mục có thể bị ảnh hưởng.')" 
                                                    class="w-8 h-8 rounded-full bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition shadow-sm" title="Xóa">
                                                <i class="fa-solid fa-trash-can text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="p-12 text-center text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <i class="fa-solid fa-layer-group text-4xl opacity-20"></i>
                                        <p class="italic text-sm">Chưa có danh mục nào trong hệ thống.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <div x-show="showAddModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div @click.away="showAddModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 transform transition-all overflow-hidden">
            <div class="bg-emerald-500 p-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-lg"><i class="fa-solid fa-plus-circle mr-2"></i>Thêm danh mục mới</h3>
                <button @click="showAddModal = false" class="text-white/70 hover:text-white text-xl leading-none">&times;</button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm" placeholder="Vd: Nông sản, Dược phẩm...">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mô tả (Tùy chọn)</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 outline-none text-sm" placeholder="Nhập mô tả ngắn gọn..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-lg transition text-sm">Hủy bỏ</button>
                    <button type="submit" class="bg-emerald-500 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-emerald-600 transition shadow-sm text-sm">Lưu danh mục</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm transition-opacity">
        <div @click.away="showEditModal = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 transform transition-all overflow-hidden">
            <div class="bg-blue-600 p-4 flex justify-between items-center text-white">
                <h3 class="font-bold text-lg"><i class="fa-solid fa-pen-to-square mr-2"></i>Chỉnh sửa danh mục</h3>
                <button @click="showEditModal = false" class="text-white/70 hover:text-white text-xl leading-none">&times;</button>
            </div>
            <form x-bind:action="`/admin/categories/${editData.id}`" method="POST" class="p-6">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none text-sm">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Mô tả (Tùy chọn)</label>
                    <textarea name="description" x-model="editData.description" rows="3" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 outline-none text-sm"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-gray-600 font-medium hover:bg-gray-100 rounded-lg transition text-sm">Hủy bỏ</button>
                    <button type="submit" class="bg-blue-600 text-white px-5 py-2.5 rounded-lg font-semibold hover:bg-blue-700 transition shadow-sm text-sm">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>