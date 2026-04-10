@extends('enterprise.layouts.app')

@section('title', 'Tạo Lô hàng & Mã QR')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Tạo Lô Hàng Mới</h2>
        <a href="{{ route('enterprise.batches.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8">
        <form action="{{ route('enterprise.batches.store') }}" method="POST">
            @csrf

            @if ($errors->any())
                <div class="mb-6 bg-red-50 text-red-700 p-4 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Chọn Sản phẩm xuất lô <span class="text-red-500">*</span></label>
                    <select name="product_id" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                        <option value="">-- Click để chọn sản phẩm --</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (Mã: {{ $product->gtin_code ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mã Lô Hàng (Batch Code) <span class="text-red-500">*</span></label>
                    <input type="text" name="batch_code" value="{{ old('batch_code') }}" required placeholder="VD: LO-BC-001" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 font-mono transition uppercase">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Số lượng sản phẩm <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity') }}" required min="1" placeholder="VD: 1000" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày sản xuất thực tế <span class="text-red-500">*</span></label>
                    <input type="date" name="manufacturing_date" value="{{ old('manufacturing_date') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hạn sử dụng <span class="text-red-500">*</span></label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date') }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>
            </div>

            <div class="mt-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex gap-3 items-start">
                <i class="fa-solid fa-qrcode text-emerald-600 mt-1"></i>
                <p class="text-sm text-emerald-800">
                    Hệ thống sẽ <strong>tự động sinh ra một mã QR Code duy nhất</strong> cho lô hàng này. Bạn có thể tải mã QR về để in dán lên bao bì sản phẩm ngay sau khi nhấn Lưu.
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition flex items-center gap-2 shadow-md">
                    <i class="fa-solid fa-wand-magic-sparkles"></i> Lưu Lô Hàng & Tạo QR
                </button>
            </div>
        </form>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Biến thẻ select product_id thành dạng có ô tìm kiếm
        $('select[name="product_id"]').select2({
            placeholder: "Gõ tên hoặc mã sản phẩm để tìm...",
            allowClear: true,
            width: '100%' // Đảm bảo full viền theo Tailwind
        });
    });
</script>

<style>
    /* Chỉnh lại giao diện Select2 cho khớp với Tailwind của bạn */
    .select2-container .select2-selection--single {
        height: 48px !important; /* Tương đương py-3 */
        border-radius: 0.75rem !important; /* rounded-xl */
        border-color: #e5e7eb !important; /* border-gray-200 */
        display: flex;
        align-items: center;
        padding-left: 0.5rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
    }
</style>
@endsection