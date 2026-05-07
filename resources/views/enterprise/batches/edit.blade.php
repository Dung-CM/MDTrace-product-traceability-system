@extends('enterprise.layouts.app')

@section('title', 'Sửa Lô hàng & Mã QR')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Sửa Lô Hàng: {{ $batch->batch_code }}</h2>
        <a href="{{ route('enterprise.batches.index') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">
            <i class="fa-solid fa-arrow-left mr-1"></i> Quay lại
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden p-8">
        <form id="main-form" action="{{ route('enterprise.batches.update', $batch->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

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
                            <option value="{{ $product->id }}" {{ (old('product_id') ?? $batch->product_id) == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} (Mã: {{ $product->gtin_code ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mã Lô Hàng (Batch Code) <span class="text-red-500">*</span></label>
                    <input type="text" name="batch_code" value="{{ old('batch_code', $batch->batch_code) }}" required placeholder="VD: LO-BC-001" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 font-mono transition uppercase" readonly title="Không thể sửa mã lô">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Số lượng sản phẩm <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity', $batch->quantity) }}" required min="1" placeholder="VD: 1000" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Ngày sản xuất thực tế <span class="text-red-500">*</span></label>
                    <input type="date" name="manufacturing_date" value="{{ old('manufacturing_date', $batch->manufacturing_date->format('Y-m-d')) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hạn sử dụng <span class="text-red-500">*</span></label>
                    <input type="date" name="expiry_date" value="{{ old('expiry_date', $batch->expiry_date->format('Y-m-d')) }}" required class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition">
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                <h3 class="text-lg font-bold text-emerald-700 pb-2 mb-4">Dữ Liệu Truy Xuất Động (Tùy chọn)</h3>
                <p class="text-sm text-gray-500 mb-4">Nhập nhật ký sản xuất, thông tin nguyên liệu và phân phối dành riêng cho đợt hàng này.</p>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <button type="button" onclick="openModal('modal-nguongoc')" class="flex flex-col items-center justify-center p-4 bg-white border-2 border-emerald-100 rounded-xl hover:border-emerald-500 hover:bg-emerald-50 transition group">
                        <i class="fa-solid fa-leaf text-2xl text-emerald-600 mb-2 group-hover:scale-110 transition"></i>
                        <span class="text-sm font-semibold text-gray-700 text-center">Nguồn gốc nguyên liệu</span>
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
            
            <div class="mt-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl flex gap-3 items-start">
                <i class="fa-solid fa-qrcode text-emerald-600 mt-1"></i>
                <p class="text-sm text-emerald-800">
                    Mã QR đã được sinh ra. Thay đổi dữ liệu ở đây sẽ tự động cập nhật nội dung khi khách hàng quét mã QR cũ.
                </p>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end gap-4">
                <button type="submit" class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition flex items-center gap-2 shadow-md">
                    <i class="fa-solid fa-floppy-disk"></i> Cập Nhật Lô Hàng
                </button>
            </div>
        </form>
    </div>
</div>

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
                        <input type="text" name="supplier_name" value="{{ old('supplier_name', $batch->origin_info['supplier_name'] ?? '') }}" form="main-form" placeholder="VD: Nông dân tại Tịnh Biên" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Địa chỉ <span class="text-red-500">*</span></label>
                        <input type="text" name="supplier_address" value="{{ old('supplier_address', $batch->origin_info['supplier_address'] ?? '') }}" form="main-form" placeholder="VD: Ấp Tây Hưng, Xã Tân An, An Giang" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition">
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
                            @php 
                                $materials = $batch->origin_info['materials'] ?? [['name' => '', 'batch' => '', 'mfg' => '', 'exp' => '', 'image_url' => '']]; 
                            @endphp
                            @foreach($materials as $index => $mat)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <input type="text" name="materials[{{$index}}][name]" value="{{ $mat['name'] ?? '' }}" form="main-form" placeholder="Nước Thốt nốt tươi" class="w-full min-w-[150px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="text" name="materials[{{$index}}][batch]" value="{{ $mat['batch'] ?? '' }}" form="main-form" placeholder="Mã lô..." class="w-full min-w-[100px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="date" name="materials[{{$index}}][mfg]" value="{{ $mat['mfg'] ?? '' }}" form="main-form" class="w-full min-w-[130px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="date" name="materials[{{$index}}][exp]" value="{{ $mat['exp'] ?? '' }}" form="main-form" class="w-full min-w-[130px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm">
                                </td>
                                <td class="px-4 py-3">
                                    @if(!empty($mat['image_url']))
                                        <img src="{{ asset('storage/' . $mat['image_url']) }}" class="h-10 w-auto rounded border border-gray-200 mb-1">
                                    @endif
                                    <input type="file" name="materials[{{$index}}][image_url]" form="main-form" accept="image/*" class="w-full min-w-[180px] text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button type="button" onclick="this.closest('tr').remove()" class="text-gray-400 hover:text-red-500 transition" title="Xóa dòng này">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
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
                @php 
                    $traces = $batch->trace_logs ?? [['name' => '', 'start_time' => '', 'end_time' => '', 'product_ref' => '', 'person' => '', 'unit' => '', 'location' => '', 'description' => '', 'image_url' => '']]; 
                @endphp
                @foreach($traces as $index => $trace)
                <div class="relative bg-white border border-gray-200 rounded-xl p-5 shadow-sm trace-card">
                    <div class="absolute top-4 right-4">
                        <button type="button" onclick="removeTraceStage(this)" class="text-gray-400 hover:text-red-500 transition bg-red-50 hover:bg-red-100 p-2 rounded-lg" title="Xóa công đoạn này">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </div>
                    
                    <h5 class="text-md font-bold text-purple-700 mb-4 flex items-center border-b border-gray-100 pb-2">
                        <span class="bg-purple-100 text-purple-700 w-6 h-6 rounded-full flex items-center justify-center text-xs mr-2 trace-number">{{ $index + 1 }}</span>
                        Thông tin công đoạn
                    </h5>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tên công đoạn <span class="text-red-500">*</span></label>
                            <input type="text" name="traces[{{$index}}][name]" value="{{ $trace['name'] ?? '' }}" form="main-form" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition font-bold text-purple-800 uppercase">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Thời gian bắt đầu</label>
                            <input type="datetime-local" name="traces[{{$index}}][start_time]" value="{{ $trace['start_time'] ?? '' }}" form="main-form" class="trace-start-input w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition" onchange="updateTraceStats()">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Thời gian kết thúc</label>
                            <input type="datetime-local" name="traces[{{$index}}][end_time]" value="{{ $trace['end_time'] ?? '' }}" form="main-form" class="trace-end-input w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition" onchange="updateTraceStats()">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Sản phẩm tham chiếu</label>
                            <input type="text" name="traces[{{$index}}][product_ref]" value="{{ $trace['product_ref'] ?? '' }}" form="main-form" placeholder="VD: Gói bắp cải 500g" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Đối tượng thực hiện</label>
                            <input type="text" name="traces[{{$index}}][person]" value="{{ $trace['person'] ?? '' }}" form="main-form" placeholder="VD: Trần Quốc Cường" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Đơn vị thực hiện</label>
                            <input type="text" name="traces[{{$index}}][unit]" value="{{ $trace['unit'] ?? '' }}" form="main-form" placeholder="VD: Công ty TNHH MTV Bảo Toàn" class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Địa điểm</label>
                            <input type="text" name="traces[{{$index}}][location]" value="{{ $trace['location'] ?? '' }}" form="main-form" placeholder="VD: 316 Hoàng Diệu..." class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Mô tả ngắn</label>
                            <textarea name="traces[{{$index}}][description]" form="main-form" rows="2" placeholder="Nhập tóm tắt công việc đã thực hiện..." class="w-full px-4 py-2 rounded-xl border border-gray-200 focus:ring-2 focus:ring-purple-500 transition">{{ $trace['description'] ?? '' }}</textarea>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Ảnh minh chứng</label>
                            @if(!empty($trace['image_url']))
                                <img src="{{ asset('storage/' . $trace['image_url']) }}" class="h-16 w-auto mb-2 rounded-xl border border-gray-200 shadow-sm">
                            @endif
                            <input type="file" name="traces[{{$index}}][image_url]" form="main-form" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-200 rounded-xl cursor-pointer">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="px-6 py-4 bg-emerald-50 border-t border-emerald-100 mt-4">
            <h5 class="text-sm font-bold text-emerald-800 mb-3 flex items-center">
                <i class="fa-solid fa-chart-simple mr-2"></i>Thống kê giai đoạn
            </h5>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex flex-col">
                    <span class="text-xs text-emerald-600 font-medium">Tổng số công việc</span>
                    <span id="stat-total-tasks" class="text-lg font-bold text-emerald-900">{{ count($traces) }}</span>
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
                <i class="fa-solid fa-check"></i> Thu nhỏ
            </button>
        </div>
    </div>
</div>

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
                    <input type="text" name="distributor_name" value="{{ old('distributor_name', $batch->distributor_info['name'] ?? '') }}" form="main-form" placeholder="VD: CO.OPMART LONG XUYÊN" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition uppercase font-bold text-teal-800">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Địa chỉ phân phối <span class="text-red-500">*</span></label>
                    <input type="text" name="distributor_address" value="{{ old('distributor_address', $batch->distributor_info['address'] ?? '') }}" form="main-form" placeholder="VD: 12 Nguyễn Huệ, Phường Long Xuyên, An Giang" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Ngày xuất hàng</label>
                    <input type="date" name="distributor_date" value="{{ old('distributor_date', $batch->distributor_info['date'] ?? '') }}" form="main-form" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Quốc gia</label>
                    <input type="text" name="distributor_country" value="{{ old('distributor_country', $batch->distributor_info['country'] ?? 'Việt Nam') }}" form="main-form" placeholder="VD: Việt Nam" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tỉnh/thành đến</label>
                    <input type="text" name="distributor_province" value="{{ old('distributor_province', $batch->distributor_info['province'] ?? '') }}" form="main-form" placeholder="VD: An Giang" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Điều kiện bảo quản</label>
                    <input type="text" name="distributor_storage" value="{{ old('distributor_storage', $batch->distributor_info['storage'] ?? '') }}" form="main-form" placeholder="VD: Bảo quản trong môi trường dưới 5°C" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-teal-500 transition">
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100 flex justify-end gap-3 bg-gray-50">
            <button type="button" onclick="closeModal('modal-phanphoi')" class="px-6 py-2.5 bg-teal-600 text-white font-semibold rounded-xl hover:bg-teal-700 shadow-sm flex items-center gap-2 transition">
                <i class="fa-solid fa-check"></i> Thu nhỏ
            </button>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        $('select[name="product_id"]').select2({
            placeholder: "Gõ tên hoặc mã sản phẩm để tìm...",
            allowClear: true,
            width: '100%'
        });
    });

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

    // Biến lưu thứ tự thêm dòng mới (Đếm số dòng đang có sẵn từ DB để cộng thêm, tránh trùng id)
    let materialIndex = {{ count($batch->origin_info['materials'] ?? []) > 0 ? count($batch->origin_info['materials']) : 1 }};
    
    function addMaterialRow() {
        const tbody = document.getElementById('material-list');
        const newRow = document.createElement('tr');
        newRow.className = 'border-b border-gray-100 hover:bg-gray-50 transition';
        // ĐÃ ĐỔI image THÀNH image_url 
        newRow.innerHTML = `
            <td class="px-4 py-3"><input type="text" name="materials[${materialIndex}][name]" form="main-form" placeholder="Tên nguyên liệu..." class="w-full min-w-[150px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm"></td>
            <td class="px-4 py-3"><input type="text" name="materials[${materialIndex}][batch]" form="main-form" placeholder="Mã lô..." class="w-full min-w-[100px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm"></td>
            <td class="px-4 py-3"><input type="date" name="materials[${materialIndex}][mfg]" form="main-form" class="w-full min-w-[130px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm"></td>
            <td class="px-4 py-3"><input type="date" name="materials[${materialIndex}][exp]" form="main-form" class="w-full min-w-[130px] px-3 py-2 rounded-lg border border-gray-200 focus:ring-2 focus:ring-emerald-500 transition text-sm"></td>
            <td class="px-4 py-3"><input type="file" name="materials[${materialIndex}][image_url]" form="main-form" accept="image/*" class="w-full min-w-[180px] text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer"></td>
            <td class="px-4 py-3 text-center"><button type="button" onclick="this.closest('tr').remove()" class="text-gray-400 hover:text-red-500 transition" title="Xóa dòng này"><i class="fa-solid fa-trash-can"></i></button></td>
        `;
        tbody.appendChild(newRow);
        materialIndex++;
    }

    // Biến lưu thứ tự thêm bước (Đếm số dòng đang có sẵn từ DB để cộng thêm, tránh trùng id)
    let traceIndex = {{ count($batch->trace_logs ?? []) > 0 ? count($batch->trace_logs) : 1 }};

    function addTraceStage() {
        const container = document.getElementById('trace-stages-container');
        if (!container) return;

        const newCard = document.createElement('div');
        newCard.className = 'relative bg-white border border-gray-200 rounded-xl p-5 shadow-sm trace-card';
        // ĐÃ ĐỔI image THÀNH image_url
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
                <div class="md:col-span-2"><label class="block text-sm font-semibold text-gray-700 mb-1">Ảnh minh chứng</label><input type="file" name="traces[${traceIndex}][image_url]" form="main-form" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 border border-gray-200 rounded-xl cursor-pointer"></div>
            </div>
        `;
        container.appendChild(newCard);
        traceIndex++;
        updateTraceStats();
    }

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

    $(document).ready(function() {
        updateTraceStats();
    });
</script>

<style>
    .select2-container .select2-selection--single {
        height: 48px !important;
        border-radius: 0.75rem !important;
        border-color: #e5e7eb !important;
        display: flex;
        align-items: center;
        padding-left: 0.5rem;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 46px !important;
    }
</style>
@endsection