@extends('enterprise.layouts.app')

@section('title', 'Sửa Sản Phẩm')

@php
    // Trích xuất dữ liệu JSON từ Database ra để gắn vào Form cho gọn code
   
    $details = $product->product_details ?? [];
    $comp = $product->company_info ?? [];
    // $dist = $product->distributor_info ?? [];
   // $origin = $product->origin_info ?? [];
    
    // Nếu chưa có mảng nào thì gán mặc định 1 phần tử trống để giao diện không bị lỗi
    $materials = !empty($origin['materials']) ? $origin['materials'] : [[]];
    $traces = !empty($product->trace_logs) ? $product->trace_logs : [[]];
@endphp
 
@section('content')

<div class="max-w-5xl mx-auto">
    
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Cập Nhật Sản Phẩm: {{ $product->name }}</h2>
        <a href="{{ route('enterprise.products.index') }}" class="text-blue-600 hover:text-blue-700 font-medium">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại danh sách
        </a>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-100 text-emerald-700 p-4 rounded-xl flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form id="main-form" action="{{ route('enterprise.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')
            
            <input type="hidden" name="trace_code" value="{{ $product->trace_code }}" readonly>
            
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
                <h3 class="text-lg font-bold text-blue-700 border-b-2 border-blue-100 pb-2 mb-4">1. Thông Tin Cơ Bản</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tên sản phẩm <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Công ty phân phối/sản xuất <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" value="{{ old('company_name', $product->company_name) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Danh mục <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">-- Chọn danh mục --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Trạng thái xác thực</label>
                        <label class="inline-flex items-center cursor-pointer mt-2">
                            <input type="checkbox" name="is_authentic" value="1" class="sr-only peer" {{ old('is_authentic', $product->is_authentic) ? 'checked' : '' }}>
                            <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                            <span class="ms-3 text-sm font-medium text-gray-700">Hiển thị tick "Sản phẩm chính hãng"</span>
                        </label>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh sản phẩm (Ảnh chính)</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-blue-500 transition bg-gray-50">
                            <div class="space-y-1 text-center">
                                <img id="main-image-preview" src="{{ $product->image_url ? asset('storage/' . $product->image_url) : '#' }}" alt="Preview" class="{{ $product->image_url ? '' : 'hidden' }} mx-auto h-32 object-cover rounded-lg mb-3 shadow-sm border border-gray-200">
                                <i id="main-image-icon" class="fa-solid fa-image text-gray-400 text-3xl mb-3 {{ $product->image_url ? 'hidden' : '' }}"></i>
                                
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 px-3 py-1.5 shadow-sm border border-gray-200">
                                        <span>Thay đổi ảnh</span>
                                        <input type="file" name="image" class="sr-only" accept="image/*" onchange="previewMainImage(this)">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">PNG, JPG tối đa 2MB. Bỏ trống nếu muốn giữ ảnh cũ.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-700 border-b-2 border-blue-100 pb-2 mb-4">2. Dữ Liệu Truy Xuất</h3>
              
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mã GTIN</label>
                        <input type="text" name="gtin_code" value="{{ old('gtin_code', $product->gtin_code) }}" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition font-mono">
                    </div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-blue-700 border-b-2 border-blue-100 pb-2 mb-4">3. Chứng Nhận Của Riêng Sản Phẩm</h3>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Bổ sung thêm chứng nhận (nếu có)</label>
                    <input type="file" name="certificates[]" multiple accept="image/*,application/pdf" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-gray-200 rounded-xl cursor-pointer">
                    @if(!empty($product->certificates))
                        <p class="text-sm text-emerald-600 mt-2"><i class="fa-solid fa-check mr-1"></i> Đang có {{ count($product->certificates) }} chứng nhận cũ được lưu.</p>
                    @endif
                </div>
            </div>

            <div class="mb-4">
                <h3 class="text-lg font-bold text-blue-700 border-b-2 border-blue-100 pb-2 mb-4">4. Nội Dung Mở Rộng Chi Tiết</h3>
                <p class="text-sm text-gray-500 mb-4">Nhấn vào từng mục để sửa thông tin chi tiết.</p>
                <div class="grid grid-cols-2 gap-4 max-w-2xl">
                    <button type="button" onclick="openModal('modal-sanpham')" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-blue-100 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group"><i class="fa-solid fa-box-open text-2xl text-blue-600 mb-2 group-hover:scale-110 transition"></i><span class="text-sm font-semibold text-gray-700 text-center">Thông tin Sản Phẩm </span></button>
                    <button type="button" onclick="openModal('modal-congty')" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-orange-100 rounded-xl hover:border-orange-500 hover:bg-orange-50 transition group"><i class="fa-solid fa-building text-2xl text-orange-600 mb-2 group-hover:scale-110 transition"></i><span class="text-sm font-semibold text-gray-700 text-center">Thông tin Công Ty</span></button>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
                <a href="{{ route('enterprise.products.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition">Hủy bỏ</a>
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition flex items-center gap-2 shadow-md">
                    <i class="fa-solid fa-save"></i> Cập Nhật Sản Phẩm
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
                            <input type="text" name="product_type" form="main-form" value="{{ $details['product_type'] ?? '' }}" placeholder="VD: Đường Thốt Nốt" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Xuất xứ</label>
                            <input type="text" name="origin_country" form="main-form" value="{{ $details['origin_country'] ?? '' }}" placeholder="VD: Việt Nam" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Thương hiệu</label>
                            <input type="text" name="brand_name" form="main-form" value="{{ $details['brand_name'] ?? '' }}" placeholder="VD: Ngọc Trang" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Trọng lượng / Thể tích</label>
                            <input type="text" name="weight" form="main-form" value="{{ $details['weight'] ?? '' }}" placeholder="VD: 500g" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Chỉ tiêu chất lượng</label>
                            <input type="text" name="quality_criteria" form="main-form" value="{{ $details['quality_criteria'] ?? '' }}" placeholder="VD: Không sử dụng đường hóa học" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Hướng dẫn bảo quản</label>
                            <input type="text" name="storage_instructions" form="main-form" value="{{ $details['storage_instructions'] ?? '' }}" placeholder="VD: Bảo quản nơi khô ráo thoáng mát" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Cách dùng</label>
                            <textarea name="usage_instructions" form="main-form" rows="2" placeholder="VD: Nấu các loại chè, làm bánh ngọt..." class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-blue-500 transition">{{ $details['usage_instructions'] ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                <div>
                    <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Bài viết giới thiệu chi tiết</h5>
                    <p class="text-xs text-gray-500 mb-2">Nhập nội dung bài viết giới thiệu về nguyên liệu, công dụng, đặc điểm của sản phẩm.</p>
                    <textarea name="detailed_introduction" form="main-form" class="rich-editor w-full px-4 py-3 rounded-xl border border-gray-200 transition">{{ $details['detailed_introduction'] ?? '' }}</textarea>
                    
                    <textarea name="company_info" form="main-form" class="rich-editor w-full px-4 py-3 rounded-xl border border-gray-200 transition mt-4">{{ $details['company_info_html'] ?? '' }}</textarea>
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
                    <input type="text" name="company_name_detail" form="main-form" value="{{ $comp['company_name'] ?? $profile->company_name ?? '' }}" placeholder="VD: Công ty TNHH MTV Bảo Toàn" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1"><i class="fa-solid fa-phone text-gray-400 mr-1"></i> Số điện thoại</label>
                    <input type="text" name="company_phone" form="main-form" value="{{ $comp['phone'] ?? $profile->phone ?? '' }}" placeholder="VD: 016 948 6204" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1"><i class="fa-solid fa-envelope text-gray-400 mr-1"></i> Email</label>
                    <input type="email" name="company_email" form="main-form" value="{{ $comp['email'] ?? $profile->contact_email ?? '' }}" placeholder="VD: ctybaotoan@gmail.com" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1"><i class="fa-solid fa-globe text-gray-400 mr-1"></i> Website</label>
                    <input type="text" name="company_website" form="main-form" value="{{ $comp['website'] ?? $profile->website_url ?? '' }}" placeholder="VD: [https://baotoan.com](https://baotoan.com)" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1"><i class="fa-solid fa-location-dot text-gray-400 mr-1"></i> Địa chỉ</label>
                    <input type="text" name="company_address" form="main-form" value="{{ $comp['address'] ?? $profile->address ?? '' }}" placeholder="VD: 316 Hoàng Diệu, Phường Châu Đốc, An Giang" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition">
                </div>
            </div>
        </div>

        <div class="mb-8">
            <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Mô tả doanh nghiệp</h5>
            <p class="text-xs text-gray-500 mb-2">Giới thiệu lịch sử, sứ mệnh, quy trình của công ty.</p>
            <textarea name="company_description" form="main-form" class="rich-editor w-full px-4 py-3 rounded-xl border border-gray-200">{{ $comp['description'] ?? $profile->description ?? '' }}</textarea>
        </div>

        <div>
            <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Đa phương tiện</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Link Google Maps nhúng (Iframe)</label>
                    <textarea name="company_map_link" form="main-form" rows="4" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-orange-500 transition font-mono text-sm">{{ $comp['map_link'] ?? $profile->map_link ?? '' }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh doanh nghiệp</label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl bg-gray-50">
                        <div class="space-y-1 text-center">
                            <label class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 px-3 py-1.5 border border-gray-200 shadow-sm hover:bg-orange-50 transition">
                                <span>Chọn file ảnh mới</span>
                                <input type="file" name="company_images[]" form="main-form" multiple accept="image/*" class="sr-only">
                            </label>
                            <p class="text-xs text-gray-500 mt-2">Nếu để trống, hệ thống sẽ giữ nguyên ảnh cũ.</p>
                        </div>
                    </div>
                    @php
                            $displayImages = !empty($comp['company_images']) ? $comp['company_images'] : (!empty($profile->company_images) ? $profile->company_images : []);
                        @endphp
                        
                        @if(count($displayImages) > 0)
                            <p class="text-sm text-emerald-600 mt-2 font-semibold">
                                <i class="fa-solid fa-images mr-1"></i> Sẽ sử dụng {{ count($displayImages) }} ảnh doanh nghiệp (từ Hồ sơ hoặc Up mới).
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

</form>
<script>
    // JS MODAL
    function openModal(modalId) { document.getElementById(modalId).classList.remove('hidden'); }
    function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); }

   

    // Xem trước ảnh chính
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

    let editors = {}; // Lưu trữ các biến editor để quản lý

function initCKEditor() {
    document.querySelectorAll('.rich-editor').forEach((el) => {
        // Tránh khởi tạo lại nếu đã tồn tại
        if (!el.classList.contains('ck-initialized')) {
            ClassicEditor.create(el, {
                toolbar: ['heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote']
            }).then(editor => {
                el.classList.add('ck-initialized');
            }).catch(error => {
                console.error(error);
            });
        }
    });
}
    // Khởi tạo CKEditor
    document.addEventListener('DOMContentLoaded', () => {
        // Chạy thống kê ngay lập tức khi mở trang
    //  updateTraceStats();
        initCKEditor();
    });
</script>
<style>.ck-editor__editable { min-height: 200px; font-family: inherit; } .ck-content ul { list-style-type: disc; margin-left: 1.5rem; } .ck-content ol { list-style-type: decimal; margin-left: 1.5rem; }</style>
@endsection