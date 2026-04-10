@extends('enterprise.layouts.app')

@section('title', 'Thêm Sản phẩm mới')

@section('content')
<div class="max-w-5xl mx-auto">
    
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Tạo Sản Phẩm Truy Xuất</h2>
        <a href="{{ route('enterprise.products.index') }}"class="text-emerald-600 hover:text-emerald-700 font-medium">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại danh sách
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-100 text-emerald-700 p-4 rounded-xl flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form id="main-form" action="{{ route('enterprise.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-sm">
                    <h3 class="text-sm font-bold text-red-800">Không thể lưu, vui lòng kiểm tra lại:</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="mb-8">
                <h3 class="text-lg font-bold text-emerald-700 border-b-2 border-emerald-100 pb-2 mb-4">1. Thông Tin Cơ Bản</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="VD: ĐƯỜNG THỐT NỐT (dạng bột)" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        @error('name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Công ty phân phối/sản xuất <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" value="{{ $profile->company_name ?? '' }}"placeholder="VD: CÔNG TY TNHH MTV" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                        @error('company_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Trạng thái xác thực</label>
                        <label class="inline-flex items-center cursor-pointer mt-2">
                            <input type="checkbox" name="is_authentic" value="1" class="sr-only peer" checked>
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-emerald-300 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700">Hiển thị tick "Sản phẩm chính hãng"</span>
                        </label>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh sản phẩm (Ảnh chính)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-emerald-500 transition bg-gray-50">
                            <div class="space-y-1 text-center">
                                <img id="main-image-preview" src="#" alt="Preview" class="hidden mx-auto h-32 object-cover rounded-lg mb-3 shadow-sm border border-gray-200">
                                
                                <i id="main-image-icon" class="fa-solid fa-image text-gray-400 text-3xl mb-3"></i>
                                
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-emerald-600 hover:text-emerald-500 px-3 py-1.5 shadow-sm border border-gray-200">
                                        <span>Chọn ảnh sản phẩm</span>
                                        <input type="file" name="image" class="sr-only" accept="image/*" onchange="previewMainImage(this)">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG tối đa 2MB</p>
                            </div>
                        </div>
                        @error('image') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-emerald-700 border-b-2 border-emerald-100 pb-2 mb-4">2. Dữ Liệu Truy Xuất</h3>
                
                   <!-- // đã xóa thẻ div của nhập liệu truy xuất hệ thống  -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày sản xuất</label>
                        <input type="date" name="mfg_date" value="{{ old('mfg_date') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hạn sử dụng</label>
                        <input type="date" name="exp_date" value="{{ old('exp_date') }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mã GTIN</label>
                        <input type="text" name="gtin_code" value="{{ old('gtin_code') }}" placeholder="VD: 8933000000001" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition font-mono">
                        @error('gtin_code') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Số lô</label>
                        <input type="text" name="batch_code" value="{{ old('batch_code') }}" placeholder="VD: TNDB100126" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-emerald-700 border-b-2 border-emerald-100 pb-2 mb-4">3. Chứng Nhận Của Riêng Sản Phẩm</h3>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Giấy chứng nhận (VietGAP, OCOP, Phiếu kiểm nghiệm...)</label>
                    <input type="file" name="certificates[]" multiple accept="image/*,application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-200 rounded-xl cursor-pointer">
                </div>
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-bold text-emerald-700 border-b-2 border-emerald-100 pb-2 mb-4">4. Nội Dung Mở Rộng Chi Tiết</h3>
                <p class="text-sm text-gray-500 mb-4">Nhấn vào từng mục để bổ sung thông tin chi tiết cho hệ thống truy xuất.</p>
                
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                    <button type="button" onclick="openModal('modal-nguongoc')" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-emerald-100 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition group">
                        <i class="fa-solid fa-leaf text-2xl text-emerald-600 mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-sm font-semibold text-gray-700 text-center">Nguồn gốc nguyên liệu</span>
                    </button>

                    <button type="button" onclick="openModal('modal-sanpham')" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-blue-100 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group">
                        <i class="fa-solid fa-box-open text-2xl text-blue-600 mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-sm font-semibold text-gray-700 text-center">Thông tin sản phẩm</span>
                    </button>

                    <button type="button" onclick="openModal('modal-congty')" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-orange-100 rounded-xl hover:border-orange-500 hover:bg-orange-50 transition group">
                        <i class="fa-solid fa-building text-2xl text-orange-600 mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-sm font-semibold text-gray-700 text-center">Thông tin công ty</span>
                    </button>

                    <button type="button" onclick="openModal('modal-truyxuat')" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-purple-100 rounded-xl hover:border-purple-500 hover:bg-purple-50 transition group">
                        <i class="fa-solid fa-route text-2xl text-purple-600 mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-sm font-semibold text-gray-700 text-center">Truy xuất nguồn gốc</span>
                    </button>

                    <button type="button" onclick="openModal('modal-phanphoi')" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-teal-100 rounded-xl hover:border-teal-500 hover:bg-teal-50 transition group">
                        <i class="fa-solid fa-truck-fast text-2xl text-teal-600 mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-sm font-semibold text-gray-700 text-center">Đơn vị phân phối</span>
                    </button>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
                <button type="reset" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">Hủy bỏ</button>
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition flex items-center gap-2 shadow-md">
                    <i class="fa-solid fa-save"></i> Lưu Thông Tin Hệ Thống
                </button>
            </div>
        </form>
    </div>
</div>
<!--Modal 1: Nguồn gốc nguyên liệu-->
<div id="modal-nguongoc" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-5xl mx-4 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        <div class=" px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-emerald-50">
            <h4 class="text-lg font-bold text-emerald-800">
                <i class="fa-solid fa-leaf mr-2"></i>Nhập Thông Tin Nguồn Gốc Nguyên Liệu
            </h4>
            <button type="button" onclick="closeModal('modal-nguongoc')" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <div class="mb-8">
                <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Thông tin Nhà cung cấp</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tên nhà cung cấp <span class="text-red-500">*</span></label>
                        <input type="text" name="supplier_name" form="main-form" placeholder="VD: Nông dân tại Tịnh Biên" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ <span class="text-red-500">*</span></label>
                        <input type="text" name="supplier_address" form="main-form" placeholder="VD: Ấp Tây Hưng, Xã Tân An, An Giang" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                </div>
            </div>

            <div>
                <div class="flex justify-between items-center border-b border-gray-200 pb-2 mb-4">
                    <h5 class="text-md font-bold text-gray-800">Chi tiết nguyên liệu</h5>
                    <button type="button" onclick="addMaterialRow()" class="text-sm text-emerald-600 hover:text-emerald-700 font-semibold bg-emerald-50 px-3 py-1.5 rounded-lg border border-emerald-100 transition">
                        <i class="fa-solid fa-plus mr-1"></i>Thêm dòng
                    </button>
                </div>
                
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-left text-sm text-gray-600 whitespace-nowrap">
                        <thead class="bg-gray-50 text-gray-700 font-semibold border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-3">Tên nguyên liệu</th>
                                <th class="px-4 py-3">Mã lô</th>
                                <th class="px-4 py-3">Ngày sản xuất</th>
                                <th class="px-4 py-3">Hạn sử dụng</th>
                                <th class="px-4 py-3">Hình ảnh</th>
                                <th class="px-4 py-3 text-center w-12">Xóa</th>
                            </tr>
                        </thead>
                        <tbody id="material-list">
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <input type="text" name="materials[0][name]" form="main-form" placeholder="Nước Thốt nốt tươi" class="w-full min-w-[150px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="materials[0][batch]" form="main-form" placeholder="Mã lô..." class="w-full min-w-[100px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="date" name="materials[0][mfg]" form="main-form" class="w-full min-w-[130px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="date" name="materials[0][exp]" form="main-form" class="w-full min-w-[130px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="file" name="materials[0][image]" form="main-form" accept="image/*" class="w-full min-w-[180px] text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" class="text-gray-400 hover:text-red-500 transition" title="Xóa dòng này">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-xs text-gray-400 mt-2 italic">* Chọn "Thêm dòng" nếu sản phẩm được cấu thành từ nhiều nguyên liệu khác nhau.</p>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
            <button type="button" onclick="closeModal('modal-nguongoc')" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition">Thu nhỏ</button>
            <button type="button" onclick="closeModal('modal-nguongoc')" class="px-6 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-check"></i> Hoàn tất nhập
            </button>
        </div>
    </div>
</div>
<!--Modal 2: Thông tin sản phẩm-->
<div id="modal-sanpham" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-4xl mx-4 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-blue-50">
            <h4 class="text-lg font-bold text-blue-800">
                <i class="fa-solid fa-box-open mr-2"></i>Nhập Thông Tin Chi Tiết Sản Phẩm
            </h4>
            <button type="button" onclick="closeModal('modal-sanpham')" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <div class="mb-6">
                <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Các thuộc tính cơ bản</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Loại sản phẩm</label>
                        <input type="text" name="product_type" form="main-form" placeholder="VD: Đường Thốt Nốt" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Xuất xứ</label>
                        <input type="text" name="origin_country" form="main-form" placeholder="VD: Việt Nam" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Thương hiệu</label>
                        <input type="text" name="brand_name" form="main-form" placeholder="VD: Ngọc Trang" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Trọng lượng / Thể tích</label>
                        <input type="text" name="weight" form="main-form" placeholder="VD: 500g" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Chỉ tiêu chất lượng</label>
                        <input type="text" name="quality_criteria" form="main-form" placeholder="VD: Không sử dụng đường hóa học" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Hướng dẫn bảo quản</label>
                        <input type="text" name="storage_instructions" form="main-form" placeholder="VD: Bảo quản nơi khô ráo thoáng mát" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Cách dùng</label>
                        <textarea name="usage_instructions" form="main-form" rows="2" placeholder="VD: Nấu các loại chè, làm bánh ngọt..." class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition"></textarea>
                    </div>
                </div>
            </div>

            <div>
                <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Bài viết giới thiệu chi tiết</h5>
                <p class="text-xs text-gray-500 mb-2">Nhập nội dung bài viết giới thiệu về nguyên liệu, công dụng, đặc điểm của sản phẩm.</p>
                <textarea name="detailed_introduction" form="main-form" class="rich-editor w-full px-4 py-3 rounded-xl border border-gray-200 transition"></textarea>
                
                <textarea name="company_info" form="main-form" class="rich-editor w-full px-4 py-3 rounded-xl border border-gray-200 transition"></textarea>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
            <button type="button" onclick="closeModal('modal-sanpham')" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition">Thu nhỏ</button>
            <button type="button" onclick="closeModal('modal-sanpham')" class="px-6 py-2.5 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 shadow-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-check"></i> Hoàn tất nhập
            </button>
        </div>
    </div>
</div>

<!--Modal 3: Thông tin công ty-->
<div id="modal-congty" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-5xl mx-4 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        <div class="md:col-span-2 px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-orange-50">
            <h4 class="text-lg font-bold text-orange-800">
                <i class="fa-solid fa-building mr-2"></i>Nhập Thông Tin Công Ty Sản Xuất
            </h4>
            <button type="button" onclick="closeModal('modal-congty')" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            
            <div class="mb-8">
                <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Thông tin cơ bản & Liên hệ</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Tên công ty <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name_detail" form="main-form" value="{{ $profile->company_name ?? '' }}" placeholder="VD: Công ty TNHH MTV Bảo Toàn" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1"><i class="fa-solid fa-phone text-gray-400 mr-1"></i> Số điện thoại</label>
                        <input type="text" name="company_phone" form="main-form" value="{{ $profile->phone ?? '' }}" placeholder="VD: 016 948 6204" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1"><i class="fa-solid fa-envelope text-gray-400 mr-1"></i> Email</label>
                        <input type="email" name="company_email" form="main-form" value="{{ $profile->contact_email ?? '' }}" placeholder="VD: ctybaotoan@gmail.com" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1"><i class="fa-solid fa-globe text-gray-400 mr-1"></i> Website</label>
                        <input type="text" name="company_website" form="main-form" value="{{ $profile->website_url ?? '' }}" placeholder="VD: https://baotoan.com" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1"><i class="fa-solid fa-location-dot text-gray-400 mr-1"></i> Địa chỉ</label>
                        <input type="text" name="company_address" form="main-form" value="{{ $profile->address ?? '' }}" placeholder="VD: 316 Hoàng Diệu, Phường Châu Đốc, An Giang" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Mô tả doanh nghiệp</h5>
                <p class="text-xs text-gray-500 mb-2">Giới thiệu lịch sử, sứ mệnh, quy trình của công ty.</p>
                <textarea name="company_description" form="main-form" class="rich-editor w-full px-4 py-3 rounded-xl border border-gray-200">{{ $profile->description ?? '' }}</textarea>
            </div>

            <div>
                <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Đa phương tiện</h5>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Link Google Maps nhúng (Iframe)</label>
                        <textarea name="company_map_link" form="main-form" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition font-mono text-sm">{{ $profile->map_link ?? '' }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh doanh nghiệp</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl bg-gray-50">
                            <div class="space-y-1 text-center">
                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 px-3 py-1.5 border border-gray-200 shadow-sm hover:bg-orange-50 transition">
                                    <span>Chọn file ảnh mới</span>
                                    <input type="file" name="company_images[]" form="main-form" multiple accept="image/*" class="sr-only">
                                </label>
                                <p class="text-xs text-gray-500 mt-2">Nếu để trống, hệ thống sẽ tự động dùng ảnh từ Hồ sơ doanh nghiệp.</p>
                            </div>
                        </div>
                        @if(!empty($profile->company_images))
                            <p class="text-sm text-emerald-600 mt-2 font-semibold">
                                <i class="fa-solid fa-images mr-1"></i> Đã có {{ count($profile->company_images) }} ảnh từ Hồ sơ.
                            </p>
                        @endif
                    </div>
                        <div class="mt-6 p-4 bg-blue-50 border border-blue-100 rounded-xl flex items-start gap-3">
                        <i class="fa-solid fa-circle-info text-blue-600 mt-0.5"></i>
                        <div>
                            <p class="text-sm text-blue-800 font-semibold">Chứng nhận doanh nghiệp</p>
                            <p class="text-xs text-blue-600 mt-1">
                                @if(!empty($profile->company_certificates))
                                    Hệ thống sẽ tự động đính kèm {{ count($profile->company_certificates) }} tài liệu chứng nhận từ Hồ sơ công ty vào phần thông tin doanh nghiệp khi khách hàng quét mã.
                                @else
                                    Bạn chưa tải lên chứng nhận công ty nào trong Hồ sơ. Hãy vào mục "Cài đặt > Hồ sơ doanh nghiệp" để bổ sung nếu cần.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div> <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
            <button type="button" onclick="closeModal('modal-congty')" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition">Thu nhỏ</button>
            <button type="button" onclick="closeModal('modal-congty')" class="px-6 py-2.5 bg-orange-600 text-white font-semibold rounded-xl hover:bg-orange-700 shadow-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-check"></i> Hoàn tất nhập
            </button>
        </div>
    </div>
</div>
 <!--Modal 4 nhật ký truy xuất nguồn gốc-->
<div id="modal-truyxuat" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-5xl mx-4 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-purple-50">
            <h4 class="text-lg font-bold text-purple-800">
                <i class="fa-solid fa-route mr-2"></i>Nhật Ký Giai Đoạn Sản Xuất
            </h4>
            <button type="button" onclick="closeModal('modal-truyxuat')" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 overflow-y-auto bg-gray-50">
            <div class="flex justify-between items-center pb-4 mb-4 border-b border-gray-200">
                <p class="text-sm text-gray-500">Nhập lần lượt các công đoạn từ lúc bắt đầu đến khi ra thành phẩm.</p>
                <button type="button" onclick="addTraceStage()" class="text-sm text-white font-semibold bg-purple-600 hover:bg-purple-700 px-4 py-2 rounded-lg shadow-sm transition flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i>Thêm công đoạn
                </button>
            </div>

            <div id="trace-stages-container" class="space-y-6">
                <div class="relative bg-white border border-gray-200 rounded-xl p-5 shadow-sm trace-card">
                    <div class="absolute top-4 right-4">
                        <button type="button" onclick="this.closest('.trace-card').remove(); updateTraceStats();" class="text-gray-400 hover:text-red-500 transition bg-red-50 hover:bg-red-100 p-2 rounded-lg" title="Xóa công đoạn này">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                    
                    <h5 class="text-md font-bold text-purple-700 mb-4 flex items-center border-b border-gray-100 pb-2">
                        <span class="bg-purple-100 text-purple-700 w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2 trace-number">1</span>
                        Thông tin công đoạn
                    </h5>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tên công đoạn <span class="text-red-500">*</span></label>
                            <input type="text" name="traces[0][name]" form="main-form" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition font-bold text-purple-800 uppercase">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="traces[0][start_time]" form="main-form" class="trace-start-input w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition" onchange="updateTraceStats()">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Thời gian kết thúc</label>
                            <input type="datetime-local" name="traces[0][end_time]" form="main-form" class="trace-end-input w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition" onchange="updateTraceStats()">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Sản phẩm tham chiếu</label>
                            <input type="text" name="traces[0][product_ref]" form="main-form" placeholder="VD: Gói bắp cải 500g" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Đối tượng thực hiện</label>
                            <input type="text" name="traces[0][person]" form="main-form" placeholder="VD: Trần Quốc Cường" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Đơn vị thực hiện</label>
                            <input type="text" name="traces[0][unit]" form="main-form" placeholder="VD: Công ty TNHH MTV Bảo Toàn" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Địa điểm</label>
                            <input type="text" name="traces[0][location]" form="main-form" placeholder="VD: 316 Hoàng Diệu..." class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mô tả ngắn</label>
                            <textarea name="traces[0][description]" form="main-form" rows="2" placeholder="Nhập tóm tắt công việc đã thực hiện..." class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition"></textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ảnh minh chứng</label>
                            <input type="file" name="traces[0][image]" form="main-form" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-200 rounded-xl cursor-pointer">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 bg-emerald-50 border-t border-emerald-100 mt-4">
            <h5 class="text-sm font-bold text-emerald-800 mb-3 flex items-center">
                <i class="fa-solid fa-chart-simple mr-2"></i>Thống kê giai đoạn
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col">
                    <span class="text-xs text-emerald-600 font-medium">Tổng số công việc</span>
                    <span id="stat-total-tasks" class="text-lg font-bold text-emerald-900">1</span>
                </div>
                <div class="flex flex-col border-l border-emerald-200 pl-4 md:pl-6">
                    <span class="text-xs text-emerald-600 font-medium">Thời gian bắt đầu</span>
                    <span id="stat-start-time" class="text-sm font-bold text-emerald-900 italic">Chưa xác định</span>
                </div>
                <div class="flex flex-col border-l border-emerald-200 pl-4 md:pl-6">
                    <span class="text-xs text-emerald-600 font-medium">Thời gian kết thúc</span>
                    <span id="stat-end-time" class="text-sm font-bold text-emerald-900 italic">Chưa xác định</span>
                </div>
            </div>
        </div>

        <div class="px-6 py-4 border-t border-gray-100 flex justify-end bg-white">
            <button type="button" onclick="closeModal('modal-truyxuat')" class="px-6 py-2.5 bg-purple-600 text-white font-semibold rounded-xl hover:bg-purple-700 transition shadow-md flex items-center gap-2">
                <i class="fa-solid fa-check"></i> Lưu tiến trình vào form
            </button>
        </div>
    </div>
</div>
<!--Modal 5 thông tin phân phối-->
<div id="modal-phanphoi" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-white rounded-2xl w-full max-w-3xl mx-4 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-teal-50">
            <h4 class="text-lg font-bold text-teal-800">
                <i class="fa-solid fa-truck-fast mr-2"></i>Nhập Thông Tin Đơn Vị Phân Phối
            </h4>
            <button type="button" onclick="closeModal('modal-phanphoi')" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>
        
        <div class="p-6 space-y-5 overflow-y-auto">
            <p class="text-sm text-gray-500 border-b border-gray-200 pb-3">Thông tin chi tiết về nhà phân phối, địa điểm và điều kiện giao nhận hàng hóa.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tên nhà phân phối <span class="text-red-500">*</span></label>
                    <input type="text" name="distributor_name" form="main-form" placeholder="VD: CO.OPMART LONG XUYÊN" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition uppercase font-bold text-teal-800">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Địa chỉ phân phối <span class="text-red-500">*</span></label>
                    <input type="text" name="distributor_address" form="main-form" placeholder="VD: 12 Nguyễn Huệ, Phường Long Xuyên, An Giang" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ngày xuất hàng</label>
                    <input type="date" name="distributor_date" form="main-form" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Quốc gia</label>
                    <input type="text" name="distributor_country" form="main-form" placeholder="VD: Việt Nam" value="Việt Nam" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tỉnh/thành đến</label>
                    <input type="text" name="distributor_province" form="main-form" placeholder="VD: An Giang" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Điều kiện bảo quản</label>
                    <input type="text" name="distributor_storage" form="main-form" placeholder="VD: Bảo quản trong môi trường dưới 5°C" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
            <button type="button" onclick="closeModal('modal-phanphoi')" class="px-6 py-2.5 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-100 transition">Thu nhỏ</button>
            <button type="button" onclick="closeModal('modal-phanphoi')" class="px-6 py-2.5 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 shadow-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-check"></i> Hoàn tất nhập
            </button>
        </div>
    </div>
</div>
<script>
    /* =======================================================
       1. CÁC HÀM XỬ LÝ MODAL (ĐÓNG/MỞ)
       ======================================================= */
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.remove('hidden');
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) modal.classList.add('hidden');
    }

    window.onclick = function(event) {
        if (event.target.classList.contains('fixed')) {
            event.target.classList.add('hidden');
        }
    }

    /* =======================================================
       2. CÁC HÀM XỬ LÝ "NGUỒN GỐC NGUYÊN LIỆU"
       ======================================================= */
    // ĐÃ FIX: Khởi tạo cứng bằng 1 vì đây là Form thêm mới
    let materialIndex = 1;
    
    function addMaterialRow() {
        const tbody = document.getElementById('material-list');
        const newRow = document.createElement('tr');
        newRow.className = 'border-b border-gray-100 hover:bg-gray-50 transition';
        newRow.innerHTML = `
            <td class="px-4 py-3"><input type="text" name="materials[${materialIndex}][name]" form="main-form" placeholder="Tên nguyên liệu..." class="w-full min-w-[150px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm"></td>
            <td class="px-4 py-3"><input type="text" name="materials[${materialIndex}][batch]" form="main-form" placeholder="Mã lô..." class="w-full min-w-[100px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm"></td>
            <td class="px-4 py-3"><input type="date" name="materials[${materialIndex}][mfg]" form="main-form" class="w-full min-w-[130px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm"></td>
            <td class="px-4 py-3"><input type="date" name="materials[${materialIndex}][exp]" form="main-form" class="w-full min-w-[130px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm"></td>
            <td class="px-4 py-3"><input type="file" name="materials[${materialIndex}][image]" form="main-form" accept="image/*" class="w-full min-w-[180px] text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"></td>
            <td class="px-4 py-3 text-center"><button type="button" onclick="this.closest('tr').remove()" class="text-gray-400 hover:text-red-500 transition" title="Xóa dòng này"><i class="fa-solid fa-trash-can"></i></button></td>
        `;
        tbody.appendChild(newRow);
        materialIndex++;
    }

    /* =======================================================
       3. CÁC HÀM XỬ LÝ "NHẬT KÝ SẢN XUẤT"
       ======================================================= */
    // ĐÃ FIX: Khởi tạo cứng bằng 1 vì đây là Form thêm mới
    let traceIndex = 1;

    function addTraceStage() {
        const container = document.getElementById('trace-stages-container');
        if (!container) return;

        const newCard = document.createElement('div');
        newCard.className = 'relative bg-white border border-gray-200 rounded-xl p-5 shadow-sm trace-card';
        newCard.innerHTML = `
            <div class="absolute top-4 right-4">
                <button type="button" onclick="removeTraceStage(this)" class="text-gray-400 hover:text-red-500 transition bg-red-50 hover:bg-red-100 p-2 rounded-lg"><i class="fa-solid fa-trash-can"></i></button>
            </div>
            <h5 class="text-md font-bold text-purple-700 mb-4 flex items-center border-b border-gray-100 pb-2">
                <span class="bg-purple-100 text-purple-700 w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2 trace-number"></span>Thông tin công đoạn
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2"><label class="block text-sm font-semibold text-gray-700 mb-1">Tên công đoạn <span class="text-red-500">*</span></label><input type="text" name="traces[${traceIndex}][name]" form="main-form" required class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition font-bold text-purple-800 uppercase"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Thời gian bắt đầu</label><input type="datetime-local" name="traces[${traceIndex}][start_time]" form="main-form" onchange="updateTraceStats()" class="trace-start-input w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Thời gian kết thúc</label><input type="datetime-local" name="traces[${traceIndex}][end_time]" form="main-form" onchange="updateTraceStats()" class="trace-end-input w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Sản phẩm tham chiếu</label><input type="text" name="traces[${traceIndex}][product_ref]" form="main-form" placeholder="VD: Gói bắp cải 500g" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Đối tượng thực hiện</label><input type="text" name="traces[${traceIndex}][person]" form="main-form" placeholder="VD: Trần Quốc Cường" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Đơn vị thực hiện</label><input type="text" name="traces[${traceIndex}][unit]" form="main-form" placeholder="VD: Công ty TNHH MTV Bảo Toàn" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Địa điểm</label><input type="text" name="traces[${traceIndex}][location]" form="main-form" placeholder="VD: 316 Hoàng Diệu..." class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition"></div>
                <div class="md:col-span-2"><label class="block text-sm font-semibold text-gray-700 mb-1">Mô tả ngắn</label><textarea name="traces[${traceIndex}][description]" form="main-form" rows="2" placeholder="Nhập tóm tắt công việc đã thực hiện..." class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition"></textarea></div>
                <div class="md:col-span-2"><label class="block text-sm font-semibold text-gray-700 mb-1">Ảnh minh chứng</label><input type="file" name="traces[${traceIndex}][image]" form="main-form" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-200 rounded-xl cursor-pointer"></div>
            </div>
        `;
        container.appendChild(newCard);
        traceIndex++;
        updateTraceStats();
    }

    // ĐÃ FIX: Xóa code rác nhân bản, gộp chung vào 1 hàm duy nhất
    function removeTraceStage(btn) {
        btn.closest('.trace-card').remove();
        updateTraceStats();
    }

   function updateTraceStats() {
        const cards = document.querySelectorAll('.trace-card');
        const totalTasks = cards.length;
        
        let statTasks = document.getElementById('stat-total-tasks');
        if(statTasks) statTasks.innerText = totalTasks;

        cards.forEach((card, index) => {
            let numSpan = card.querySelector('.trace-number');
            if(numSpan) numSpan.innerText = index + 1;
        });

        let statStart = document.getElementById('stat-start-time');
        if (statStart && cards.length > 0) {
            let firstStartInput = cards[0].querySelector('.trace-start-input');
            if (firstStartInput && firstStartInput.value) {
                statStart.innerText = formatDate(new Date(firstStartInput.value));
            } else {
                statStart.innerText = "Chưa xác định";
            }
        } else if (statStart) {
            statStart.innerText = "Chưa xác định";
        }

        let statEnd = document.getElementById('stat-end-time');
        if (statEnd && cards.length > 0) {
            let lastEndInput = cards[cards.length - 1].querySelector('.trace-end-input');
            if (lastEndInput && lastEndInput.value) {
                statEnd.innerText = formatDate(new Date(lastEndInput.value));
            } else {
                statEnd.innerText = "Chưa xác định";
            }
        } else if (statEnd) {
            statEnd.innerText = "Chưa xác định";
        }
    }

    function formatDate(date) {
        const h = String(date.getHours()).padStart(2, '0');
        const m = String(date.getMinutes()).padStart(2, '0');
        const d = String(date.getDate()).padStart(2, '0');
        const mo = String(date.getMonth() + 1).padStart(2, '0');
        const y = date.getFullYear();
        return `${h}:${m} ${d}/${mo}/${y}`;
    }

    /* =======================================================
       4. HÀM HIỂN THỊ ẢNH XEM TRƯỚC (PREVIEW)
       ======================================================= */
    function previewMainImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                const previewImg = document.getElementById('main-image-preview');
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                document.getElementById('main-image-icon').classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    /* =======================================================
       5. CHẠY MỘT LẦN KHI LOAD XONG TRANG
       ======================================================= */
    document.addEventListener('DOMContentLoaded', () => {
        updateTraceStats();

        if (typeof ClassicEditor !== 'undefined') {
            const editors = document.querySelectorAll('.rich-editor');
            editors.forEach((editorElement) => {
                ClassicEditor.create(editorElement, {
                    toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote' ]
                }).catch(error => {
                    console.error('Lỗi khởi tạo CKEditor:', error);
                });
            });
        } else {
            console.warn("CKEditor chưa được load. Vui lòng kiểm tra layout app.blade.php xem đã nhúng thư viện chưa.");
        }
    });
</script>

<style>
    .ck-editor__editable { min-height: 200px; font-family: inherit; }
    .ck-content ul { list-style-type: disc; margin-left: 1.5rem; }
    .ck-content ol { list-style-type: decimal; margin-left: 1.5rem; }
</style>
@endsection