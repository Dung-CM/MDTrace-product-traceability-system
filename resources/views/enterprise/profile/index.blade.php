@extends('enterprise.layouts.app')
@section('title', 'Hồ sơ doanh nghiệp')

@section('content')
<div class="max-w-6xl mx-auto">
    
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Hồ sơ Doanh nghiệp</h2>
        <p class="text-gray-500 text-sm mt-1">Quản lý thông tin công ty và nhận diện thương hiệu của bạn.</p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-100 text-emerald-700 p-4 rounded-xl flex items-center gap-2 shadow-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('enterprise.profile.update') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            
            <div class="flex flex-col md:flex-row gap-10">
                <div class="w-full md:w-1/3 flex flex-col items-center border-b md:border-b-0 md:border-r border-gray-100 pb-8 md:pb-0 md:pr-8">
                    <div class="relative group cursor-pointer mb-4">
                        @if($profile->logo_url)
                            <img src="{{ asset('storage/' . $profile->logo_url) }}" alt="Logo" class="w-48 h-48 rounded-full object-cover border-4 border-gray-50 shadow-md">
                        @else
                            <div class="w-48 h-48 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-5xl font-bold border-4 border-gray-50 shadow-md">
                                {{ substr($profile->company_name, 0, 1) }}
                            </div>
                        @endif
                        
                        <label class="absolute inset-0 bg-black bg-opacity-50 rounded-full flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 cursor-pointer">
                            <i class="fa-solid fa-camera text-white text-2xl mb-2"></i>
                            <span class="text-white text-sm font-semibold">Đổi Logo</span>
                            <input type="file" name="logo" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </label>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 text-center">{{ $profile->company_name }}</h3>
                    <p class="text-sm text-gray-500 text-center">MST: {{ $profile->tax_code }}</p>
                </div>

                <div class="w-full md:w-2/3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tên công ty / HTX</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $profile->company_name) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mã số thuế</label>
                            <input type="text" name="tax_code" value="{{ old('tax_code', $profile->tax_code) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition bg-gray-50">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Số điện thoại</label>
                            <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Email liên hệ</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $profile->contact_email) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Website</label>
                            <input type="url" name="website_url" value="{{ old('website_url', $profile->website_url) }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Địa chỉ trụ sở</label>
                            <input type="text" name="address" value="{{ old('address', $profile->address) }}" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Bài viết giới thiệu công ty</label>
                            <textarea name="description" class="rich-editor w-full px-4 py-3 rounded-xl border border-gray-200">{{ old('description', $profile->description) }}</textarea>
                        </div>
                         <div class="md:col-span-2 mt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Link Google Maps (Iframe)</label>
                            <textarea name="map_link" rows="3" placeholder='Dán mã <iframe src="..."> vào đây' class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition font-mono text-sm">{{ old('map_link', $profile->map_link) }}</textarea>
                        </div>

                        <div class="md:col-span-2 mt-4">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Hình ảnh cơ sở vật chất (Chọn nhiều ảnh)</label>
                            <input type="file" name="company_images[]" multiple accept="image/*" class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-50">
                            
                            @if(!empty($profile->company_images))
                            <div class="flex flex-wrap gap-3 mt-3">
                                @foreach($profile->company_images as $img)
                                    <img src="{{ asset('storage/' . $img) }}" class="w-24 h-24 object-cover rounded-lg border border-gray-200 shadow-sm">
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="md:col-span-2 mt-6">
                        <h5 class="text-md font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Hồ sơ Pháp lý / Chứng nhận Công ty</h5>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tải lên các giấy tờ (GPKD, ISO, VSATTP...) - Hỗ trợ Ảnh & PDF</label>
                        <input type="file" name="company_certificates[]" multiple accept="image/*,application/pdf" class="w-full px-4 py-2 border border-gray-200 rounded-xl bg-gray-50">
                        
                        @if(!empty($profile->company_certificates))
                        <div class="flex flex-wrap gap-3 mt-3">
                             @foreach($profile->company_certificates as $cert)
                                    <img src="{{ asset('storage/' . $cert) }}" class="w-24 h-24 object-cover rounded-lg border border-gray-200 shadow-sm">
                                @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 shadow-md transition flex items-center gap-2">
                            <i class="fa-solid fa-save"></i> Cập nhật hồ sơ
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof ClassicEditor !== 'undefined') {
            document.querySelectorAll('.rich-editor').forEach((editorElement) => {
                ClassicEditor.create(editorElement, { toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList' ] });
            });
        }
    });

    // Hàm preview ảnh logo khi chọn file mới
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                // Nếu có thẻ img thì đổi src, nếu đang là thẻ div chữ thì bạn có thể reload trang hoặc viết thêm logic thay thế DOM
                let img = input.closest('.relative').querySelector('img');
                if(img) img.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection