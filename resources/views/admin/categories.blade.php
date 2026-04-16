<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Danh mục - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-50 font-sans flex h-screen overflow-hidden" x-data="{ showAddModal: false, showEditModal: false, editData: {} }">

    <aside class="w-64 bg-[#0A2540] text-white flex flex-col shadow-xl z-20">
        <div class="h-20 flex items-center px-6 border-b border-white/10">
            <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center font-bold text-lg mr-3"><i class="fa-solid fa-qrcode"></i></div>
            <span class="text-2xl font-bold tracking-tight">MD<span class="text-emerald-400">Trace</span></span>
        </div>
        <nav class="flex-1 py-6 px-4 space-y-2">
            <a href="{{ route('admin.dashboard.stats') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition shadow-sm text-gray-400 hover:text-white hover:bg-white/5">
                <i class="fa-solid fa-chart-pie w-5 text-center"></i><span class="font-semibold">Tổng quan hệ thống</span>
            </a>
            
            <div x-data="{ open: false }">
                <button @click="open = !open" class="w-full flex items-center justify-between gap-3 text-gray-400 hover:text-white hover:bg-white/5 px-4 py-3 rounded-xl transition group">
                    <div class="flex items-center gap-3"><i class="fa-solid fa-building-shield w-5 text-center"></i><span class="font-semibold">Quản lý Doanh nghiệp</span></div>
                    <i class="fa-solid fa-chevron-down text-xs transition-transform duration-300"></i>
                </button>
                <div x-show="open" x-transition class="mt-2 ml-6 space-y-1 border-l border-emerald-500/30 pl-4 py-1">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 py-2 text-sm text-gray-400 hover:text-white transition"><span>Duyệt hồ sơ mới</span></a>
                    <a href="{{ route('admin.enterprises.active') }}" class="flex items-center gap-2 py-2 text-sm text-gray-400 hover:text-white transition"><span>Đang hoạt động</span></a>
                </div>
            </div>

            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 bg-emerald-600 text-white px-4 py-3 rounded-xl transition shadow-sm mt-4">
                <i class="fa-solid fa-layer-group w-5 text-center"></i><span class="font-semibold">Danh mục sản phẩm</span>
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
            <div class="text-xl font-bold text-gray-800">Quản lý Danh mục</div>
            <div class="flex items-center gap-4">
                <button @click="showAddModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl font-bold shadow-md shadow-emerald-200 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Thêm danh mục
                </button>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-slate-50 p-8">
            
            @if(session('success'))
                <div class="bg-emerald-100 text-emerald-700 px-6 py-4 rounded-xl mb-6 shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-check-circle text-xl"></i> <b>{{ session('success') }}</b>
                </div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 text-red-700 px-6 py-4 rounded-xl mb-6 shadow-sm flex items-center gap-3">
                    <i class="fa-solid fa-triangle-exclamation text-xl"></i> <b>{{ $errors->first() }}</b>
                </div>
            @endif

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/50 border-b border-gray-100 text-gray-500 text-sm uppercase tracking-wider">
                            <th class="p-5 font-bold w-16 text-center">ID</th>
                            <th class="p-5 font-bold">Tên danh mục</th>
                            <th class="p-5 font-bold">Mô tả</th>
                            <th class="p-5 font-bold w-48">Ngày tạo</th>
                            <th class="p-5 font-bold w-32 text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse($categories as $cat)
                            <tr class="hover:bg-gray-50 transition duration-150">
                                <td class="p-5 text-center font-semibold text-gray-600">#{{ $cat->id }}</td>
                                <td class="p-5 font-bold text-gray-800">{{ $cat->name }}</td>
                                <td class="p-5 text-gray-600 text-sm">{{ $cat->description ?? 'Không có mô tả' }}</td>
                                <td class="p-5 text-gray-500 text-sm">{{ $cat->created_at->format('d/m/Y H:i') }}</td>
                                <td class="p-5 text-center flex justify-center gap-2">
                                    <button @click="editData = { id: {{ $cat->id }}, name: '{{ $cat->name }}', description: '{{ $cat->description }}' }; showEditModal = true" 
                                            class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition flex items-center justify-center">
                                        <i class="fa-solid fa-pen text-sm"></i>
                                    </button>
                                    
                                    <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition flex items-center justify-center">
                                            <i class="fa-solid fa-trash text-sm"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-10 text-center text-gray-400">
                                    <div class="text-4xl mb-3"><i class="fa-solid fa-folder-open"></i></div>
                                    <p>Hệ thống chưa có danh mục sản phẩm nào.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div x-show="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl w-[500px] overflow-hidden" @click.away="showAddModal = false">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-lg text-gray-800">Thêm Danh mục mới</h3>
                <button @click="showAddModal = false" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="VD: Nông sản sạch">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả thêm</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Mô tả ngắn về danh mục này..."></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showAddModal = false" class="px-5 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 font-semibold rounded-xl">Hủy bỏ</button>
                    <button type="submit" class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-md">Lưu danh mục</button>
                </div>
            </form>
        </div>
    </div>

    <div x-show="showEditModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm" style="display: none;" x-transition>
        <div class="bg-white rounded-2xl shadow-2xl w-[500px] overflow-hidden" @click.away="showEditModal = false">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                <h3 class="font-bold text-lg text-gray-800">Cập nhật Danh mục</h3>
                <button @click="showEditModal = false" class="text-gray-400 hover:text-red-500"><i class="fa-solid fa-xmark text-xl"></i></button>
            </div>
            <form :action="`/admin/categories/${editData.id}`" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tên danh mục <span class="text-red-500">*</span></label>
                    <input type="text" name="name" x-model="editData.name" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mô tả thêm</label>
                    <textarea name="description" x-model="editData.description" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:outline-none"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showEditModal = false" class="px-5 py-2.5 text-gray-600 bg-gray-100 hover:bg-gray-200 font-semibold rounded-xl">Hủy bỏ</button>
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>