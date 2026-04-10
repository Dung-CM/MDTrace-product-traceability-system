<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MDTrace - Truy xuất nguồn gốc Blockchain</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                    colors: {
                        primary: '#0A2540',
                        accent: '#10B981',
                        'accent-dark': '#059669',
                    }
                }
            }
        }
    </script>

    <style>
        .hero-bg {
            background: linear-gradient(135deg, #0A2540 0%, #1E3A8A 100%);
        }
        .stat-card {
            transition: all 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-8px);
        }
    </style>
</head>
<body class="font-sans antialiased bg-white">

    @include('layouts.header')

   

    {{-- HERO SECTION - PHIÊN BẢN CẢI TIẾN GIỐNG FIGMA --}}
<section class="hero-bg pt-28 pb-24 text-white overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-12 items-center">
        
        <!-- Left Content -->
            <div class="space-y-8">
                
                <!-- Badge Blockchain -->
                <div class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500/10 border border-emerald-400/30 rounded-full">
                    <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse"></span>
                    <span class="text-emerald-400 font-semibold text-sm tracking-wider">
                        BLOCKCHAIN-POWERED TRACEABILITY
                    </span>
                </div>

                <!-- Tiêu đề chính -->
                <h1 class="text-5xl md:text-6xl font-bold leading-tight">
                    Minh bạch nguồn gốc <span class="text-emerald-400">— Bảo vệ người tiêu dùng</span>
                </h1>

                <!-- Mô tả -->
                <p class="text-xl text-gray-200 max-w-lg">
                    Quét mã QR để truy xuất thông tin lô hàng chỉ trong <span class="font-semibold">1 giây</span>. 
                    Công nghệ <span class="text-emerald-400 font-medium">Blockchain</span> đảm bảo dữ liệu không thể giả mạo.
                </p>

                <!-- Hai nút -->
                <div class="flex flex-wrap gap-4 pt-4">
                    <!-- Nút Quét QR -->
                    <a href="#" 
                       class="bg-emerald-400 hover:bg-emerald-500 transition-all duration-300 text-black px-8 py-4 rounded-2xl font-semibold flex items-center gap-3 text-lg">
                        Giới thiệu
                    </a>

                    <!-- Nút Đăng ký doanh nghiệp -->
                    <a href="{{ route('register') }}" 
                       class="border-2 border-white/70 hover:bg-white/10 hover:border-white/90 transition-all duration-300 px-8 py-4 rounded-2xl font-semibold flex items-center gap-3 text-lg">
                        <i class="fa-solid fa-building"></i>
                        Đăng ký cho doanh nghiệp
                    </a>
                </div>
            </div>

        <!-- RIGHT: PHONE MOCKUP GIỐNG FIGMA -->
        <!-- <div class="flex justify-center relative">
            <div class="relative w-[320px]">
                
              
                <div class="bg-[#0f172a] rounded-[52px] p-3 shadow-2xl phone-mockup border-8 border-black relative z-10">
                    <div class="bg-white rounded-[40px] overflow-hidden h-[620px] relative">
                        
                       
                        <div class="h-11 bg-gradient-to-r from-blue-700 to-indigo-700 flex items-center px-6 text-white text-xs">
                            <div class="flex-1">9:41</div>
                            <div class="flex items-center gap-1.5">
                                <i class="fa-solid fa-signal"></i>
                                <i class="fa-solid fa-wifi"></i>
                                <span>92%</span>
                            </div>
                        </div>

                       
                        <div class="bg-white border-b px-5 py-4 flex items-center justify-between">
                            <span class="font-semibold text-lg">MDTrace</span>
                            <div class="bg-emerald-500 text-white text-[10px] px-3 py-1 rounded-full flex items-center gap-1 font-medium">
                                <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span>
                                BLOCK #4,281,933
                            </div>
                        </div>

                      
                        <div class="relative h-80 bg-gray-100 flex items-center justify-center">
                            <img src="https://via.placeholder.com/280x280/10B981/ffffff?text=QR+CODE" 
                                 alt="QR Code" 
                                 class="w-56 h-56 object-contain">
                          
                            <div class="absolute border-4 border-emerald-400 border-dashed w-64 h-64 rounded-xl animate-[pulse_2s_infinite]"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-64 h-64 border-2 border-emerald-400 rounded-xl relative">
                                    <div class="absolute -top-1 -left-1 w-6 h-6 border-t-4 border-l-4 border-emerald-400"></div>
                                    <div class="absolute -top-1 -right-1 w-6 h-6 border-t-4 border-r-4 border-emerald-400"></div>
                                    <div class="absolute -bottom-1 -left-1 w-6 h-6 border-b-4 border-l-4 border-emerald-400"></div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 border-b-4 border-r-4 border-emerald-400"></div>
                                </div>
                            </div>
                        </div>

                      
                        <div class="p-6 space-y-4">
                            <div class="bg-emerald-100 text-emerald-700 text-center py-2.5 rounded-2xl text-sm font-semibold">
                                ✅ ĐÃ XÁC THỰC BLOCKCHAIN
                            </div>
                            
                            <div>
                                <h3 class="font-bold text-xl">Rau cải hữu cơ</h3>
                                <p class="text-emerald-600 font-medium">#TRC2024001</p>
                            </div>

                            <div class="grid grid-cols-2 gap-y-3 text-sm">
                                <div class="text-gray-500">Xuất xứ</div>
                                <div class="text-right font-medium">Đà Lạt, Lâm Đồng</div>
                                <div class="text-gray-500">Ngày thu hoạch</div>
                                <div class="text-right font-medium">28/03/2026</div>
                                <div class="text-gray-500">Nông trại</div>
                                <div class="text-right font-medium">HTX Rau sạch Đà Lạt</div>
                            </div>

                            <button onclick="alert('Đang xem hành trình đầy đủ...')" 
                                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-4 rounded-2xl font-semibold flex items-center justify-center gap-2 transition">
                                Xem hành trình đầy đủ 
                                <i class="fa-solid fa-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </div>

              
                <div class="absolute -left-6 top-52 bg-white text-gray-900 px-5 py-3 rounded-2xl shadow-2xl font-bold text-lg border border-gray-100">
                    99.9%<br>
                    <span class="text-xs font-normal text-gray-500">Tỉ lệ xác thực</span>
                </div>

                
                <div class="absolute -right-8 top-20 bg-gray-900 text-white text-xs p-4 rounded-2xl shadow-2xl max-w-[180px]">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-emerald-400">●</span>
                        <span class="font-medium">BLOCK #4,281,933</span>
                    </div>
                    <div class="text-[10px] text-gray-400">0x3F9a2...c81b4</div>
                    <div class="text-emerald-400 text-[10px] mt-1">✔ Xác nhận: Hợp lệ</div>
                </div>-->

            </div>
        </div>
    </div>
</section>

    {{-- STATS SECTION - Theo ảnh Figma --}}
    <section class="bg-primary py-12 text-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="stat-card">
                    <div class="text-5xl font-bold text-accent">0</div>
                    <div class="text-sm text-gray-300 mt-2">Doanh nghiệp tin dùng</div>
                </div>
                <div class="stat-card">
                    <div class="text-5xl font-bold text-accent">0</div>
                    <div class="text-sm text-gray-300 mt-2">Mã QR đã tạo</div>
                </div>
                <div class="stat-card">
                    <div class="text-5xl font-bold text-accent">99.9%</div>
                    <div class="text-sm text-gray-300 mt-2">Uptime đảm bảo</div>
                </div>
                <div class="stat-card">
                    <div class="text-5xl font-bold text-accent">&lt;1s</div>
                    <div class="text-sm text-gray-300 mt-2">Tốc độ truy xuất</div>
                </div>
            </div>
        </div>
    </section>

   {{-- QUY TRÌNH - HOW IT WORKS (PHIÊN BẢN GIỐNG FIGMA NHẤT) --}}
<section id="how-it-works" class="py-28 bg-white">
    <div class="max-w-6xl mx-auto px-6">
        <div class="text-center mb-16">
            <span class="bg-emerald-100 text-emerald-700 px-5 py-2 rounded-full text-sm font-semibold">CÁCH HOẠT ĐỘNG</span>
            <h2 class="text-4xl font-bold text-gray-900 mt-5">Quy trình đơn giản, hiệu quả</h2>
            <p class="text-gray-600 mt-4 max-w-2xl mx-auto">
                Từ sản xuất đến tay người tiêu dùng – mọi thông tin được ghi lại minh bạch chỉ trong 4 bước.
            </p>
        </div>

        <div class="relative max-w-5xl mx-auto">
            
            <!-- Đường kẻ ngang -->
            <div class="hidden md:block absolute top-[52px] left-[15%] right-[15%] h-[3px] bg-gradient-to-r from-blue-400 via-emerald-400 to-teal-500 z-0"></div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 md:gap-6 relative z-10">

                <!-- Bước 1 -->
                <div class="group text-center">
                    <div class="mx-auto w-20 h-20 bg-blue-600 rounded-3xl flex items-center justify-center text-white text-4xl mb-6 shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-2xl">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    
                    <div class="step-card bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-3 transition-all duration-300">
                        <span class="block text-emerald-600 text-xs font-semibold mb-2">BƯỚC 01</span>
                        <h3 class="font-bold text-lg mb-3">Doanh nghiệp tạo QR</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Đăng ký thông tin sản phẩm, lô hàng. Hệ thống tự động sinh mã QR duy nhất và ghi nhận lên Blockchain.
                        </p>
                    </div>
                </div>

                <!-- Bước 2 -->
                <div class="group text-center">
                    <div class="mx-auto w-20 h-20 bg-emerald-600 rounded-3xl flex items-center justify-center text-white text-4xl mb-6 shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-2xl">
                        <i class="fa-solid fa-mobile-screen-button"></i>
                    </div>
                    
                    <div class="step-card bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-3 transition-all duration-300">
                        <span class="block text-emerald-600 text-xs font-semibold mb-2">BƯỚC 02</span>
                        <h3 class="font-bold text-lg mb-3">Khách hàng quét mã</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Người tiêu dùng dùng camera điện thoại quét mã QR trên bao bì. Không cần cài app, hoạt động ngay trên trình duyệt.
                        </p>
                    </div>
                </div>

                <!-- Bước 3 -->
                <div class="group text-center">
                    <div class="mx-auto w-20 h-20 bg-indigo-600 rounded-3xl flex items-center justify-center text-white text-4xl mb-6 shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-2xl">
                        <i class="fa-solid fa-eye"></i>
                    </div>
                    
                    
                    <div class="step-card bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-3 transition-all duration-300">
                        <span class="block text-emerald-600 text-xs font-semibold mb-2">BƯỚC 03</span>
                        <h3 class="font-bold text-lg mb-3">Xem thông tin chi tiết</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Hiển thị toàn bộ hành trình sản phẩm: xuất xứ, ngày sản xuất, quy trình vận chuyển, chứng nhận chất lượng.
                        </p>
                    </div>
                </div>

                <!-- Bước 4 -->
                <div class="group text-center">
                    <div class="mx-auto w-20 h-20 bg-teal-600 rounded-3xl flex items-center justify-center text-white text-4xl mb-6 shadow-lg transition-all duration-300 group-hover:scale-110 group-hover:shadow-2xl">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                   
                    
                    <div class="step-card bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-xl hover:-translate-y-3 transition-all duration-300">
                        <span class="block text-emerald-600 text-xs font-semibold mb-2">BƯỚC 04</span>
                        <h3 class="font-bold text-lg mb-3">Xác thực Blockchain</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">
                            Dữ liệu được xác thực tức thì qua mạng Blockchain phi tập trung. Đảm bảo tính toàn vẹn, không thể giả mạo.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Nút Xem demo -->
            <div class="text-center mt-16">
                <a href="#" 
                   class="inline-flex items-center gap-3 bg-white border border-gray-300 hover:border-emerald-500 hover:text-emerald-600 px-8 py-4 rounded-2xl font-medium transition-all">
                    Xem demo chi tiết 
                    <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- PHẦN LỢI ÍCH - BENEFITS (Theo Figma) --}}
<section id="benefits" class="py-28 bg-gray-50">
    <div class="max-w-6xl mx-auto px-6">
        
        <!-- Badge -->
        <div class="inline-flex items-center gap-2 bg-emerald-100 text-emerald-700 px-5 py-2 rounded-full text-sm font-semibold mb-6">
            LỢI ÍCH NỔI BẬT
        </div>

        <!-- Tiêu đề + Mô tả nằm ngang hàng -->
        <div class="grid md:grid-cols-12 gap-10 items-start">
            
            <!-- Tiêu đề lớn -->
            <div class="md:col-span-7">
                <h2 class="text-4xl md:text-5xl font-bold leading-tight text-gray-900">
                    Tại sao hàng nghìn doanh nghiệp tin chọn MDTrace?
                </h2>
            </div>

            <!-- Mô tả (ngang hàng với tiêu đề) -->
            <div class="md:col-span-5">
                <p class="text-gray-600 text-[17px] leading-relaxed pt-3">
                    Giải pháp toàn diện giúp doanh nghiệp xây dựng uy tín, chống hàng giả 
                    và tuân thủ tiêu chuẩn quốc tế – tất cả trong một nền tảng duy nhất.
                </p>
            </div>
        </div>
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mt-16">
            
            <!-- Card 1 -->
            <div class="benefit-card group bg-white rounded-3xl p-8 border border-red-100 hover:border-red-300 transition-all duration-300 hover:-translate-y-2">
                <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center text-red-500 mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-shield-halved text-2xl"></i>
                </div>
                <span class="inline-block bg-red-50 text-red-600 text-xs font-medium px-4 py-1 rounded-full mb-4">Bảo vệ thương hiệu</span>
                <h3 class="font-bold text-xl mb-3">Chống hàng giả hiệu quả</h3>
                <p class="text-gray-600 text-[15px] leading-relaxed">
                    Mỗi mã QR được gắn duy nhất với lô hàng, không thể sao chép. Phát hiện hàng nhái ngay tức thì, bảo vệ thương hiệu và người tiêu dùng.
                </p>
            </div>

            <!-- Card 2 -->
            <div class="benefit-card group bg-white rounded-3xl p-8 border border-emerald-100 hover:border-emerald-300 transition-all duration-300 hover:-translate-y-2">
                <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center text-emerald-500 mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-heart text-2xl"></i>
                </div>
                <span class="inline-block bg-emerald-50 text-emerald-600 text-xs font-medium px-4 py-1 rounded-full mb-4">Tăng doanh số</span>
                <h3 class="font-bold text-xl mb-3">Tăng niềm tin người tiêu dùng</h3>
                <p class="text-gray-600 text-[15px] leading-relaxed">
                    Khách hàng có thể tự xác minh thông tin sản phẩm trực tiếp. Minh bạch hoàn toàn xây dựng lòng tin lâu dài và tăng doanh số.
                </p>
            </div>

            <!-- Card 3 -->
            <div class="benefit-card group bg-white rounded-3xl p-8 border border-blue-100 hover:border-blue-300 transition-all duration-300 hover:-translate-y-2">
                <div class="w-12 h-12 bg-blue-100 rounded-2xl flex items-center justify-center text-blue-500 mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-list-check text-2xl"></i>
                </div>
                <span class="inline-block bg-blue-50 text-blue-600 text-xs font-medium px-4 py-1 rounded-full mb-4">Tiết kiệm thời gian</span>
                <h3 class="font-bold text-xl mb-3">Quản lý dễ dàng, trực quan</h3>
                <p class="text-gray-600 text-[15px] leading-relaxed">
                    Dashboard thông minh cho phép quản lý hàng ngàn lô hàng, theo dõi chuỗi cung ứng theo thời gian thực từ mọi thiết bị.
                </p>
            </div>

            <!-- Card 4 -->
            <div class="benefit-card group bg-white rounded-3xl p-8 border border-amber-100 hover:border-amber-300 transition-all duration-300 hover:-translate-y-2">
                <div class="w-12 h-12 bg-amber-100 rounded-2xl flex items-center justify-center text-amber-500 mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-lock text-2xl"></i>
                </div>
                <span class="inline-block bg-amber-50 text-amber-600 text-xs font-medium px-4 py-1 rounded-full mb-4">An toàn tuyệt đối</span>
                <h3 class="font-bold text-xl mb-3">Dữ liệu bất biến, an toàn</h3>
                <p class="text-gray-600 text-[15px] leading-relaxed">
                    Dữ liệu được mã hóa và lưu trữ trên Blockchain. Không ai có thể chỉnh sửa hay xóa dữ liệu sau khi đã được ghi nhận.
                </p>
            </div>

            <!-- Card 5 -->
            <div class="benefit-card group bg-white rounded-3xl p-8 border border-purple-100 hover:border-purple-300 transition-all duration-300 hover:-translate-y-2">
                <div class="w-12 h-12 bg-purple-100 rounded-2xl flex items-center justify-center text-purple-500 mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-chart-line text-2xl"></i>
                </div>
                <span class="inline-block bg-purple-50 text-purple-600 text-xs font-medium px-4 py-1 rounded-full mb-4">Tăng giá trị</span>
                <h3 class="font-bold text-xl mb-3">Nâng cao giá trị sản phẩm</h3>
                <p class="text-gray-600 text-[15px] leading-relaxed">
                    Sản phẩm có truy xuất nguồn gốc rõ ràng sẽ được định giá cao hơn 20-35%. Tạo lợi thế cạnh tranh bền vững trên thị trường.
                </p>
            </div>

            <!-- Card 6 -->
            <div class="benefit-card group bg-white rounded-3xl p-8 border border-cyan-100 hover:border-cyan-300 transition-all duration-300 hover:-translate-y-2">
                <div class="w-12 h-12 bg-cyan-100 rounded-2xl flex items-center justify-center text-cyan-500 mb-6 group-hover:scale-110 transition">
                    <i class="fa-solid fa-globe text-2xl"></i>
                </div>
                <span class="inline-block bg-cyan-50 text-cyan-600 text-xs font-medium px-4 py-1 rounded-full mb-4">Xuất khẩu</span>
                <h3 class="font-bold text-xl mb-3">Tuân thủ quy định quốc tế</h3>
                <p class="text-gray-600 text-[15px] leading-relaxed">
                    Đáp ứng tiêu chuẩn truy xuất nguồn gốc của EU, FDA, và các thị trường xuất khẩu khó tính. Mở rộng thị trường không giới hạn.
                </p>
            </div>

        </div>
    </div>
</section>

{{-- PHẦN TESTIMONIALS / KHÁCH HÀNG NÓI GÌ --}}
<section id="testimonials" class="hero-bg pt-28 pb-24 text-white overflow-hidden">
    <div class="max-w-6xl mx-auto px-6">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <span class="bg-emerald-100 text-emerald-700 px-5 py-2 rounded-full text-sm font-semibold">KHÁCH HÀNG NÓI GÌ</span>
            <h2 class="text-4xl font-bold text-white mt-5">Được tin dùng bởi hàng nghìn doanh nghiệp</h2>
            <p class="text-gray-300 mt-4 max-w-2xl mx-auto">
                Từ hộ nông dân đến tập đoàn lớn – MDTrace phục vụ mọi quy mô.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            <!-- Card 1 -->
            <div class="testimonial-card bg-white rounded-3xl p-8 shadow-lg transition-all duration-300 hover:-translate-y-4 hover:shadow-2xl group">
                <div class="flex text-yellow-400 mb-6 text-xl">
                    ★★★★★
                </div>
                <p class="text-gray-700 leading-relaxed mb-8 italic">
                    "MDTrace giúp chúng tôi chứng minh chất lượng sản phẩm với khách hàng. 
                    Doanh thu tăng 35% sau 6 tháng sử dụng nhờ niềm tin được xây dựng vững chắc."
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden">
                        <!-- Thay bằng ảnh thật nếu có -->
                        <img src="https://via.placeholder.com/48x48/4F46E5/ffffff?text=NV" alt="Nguyễn Văn Minh" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">Nguyễn Văn Minh</p>
                        <p class="text-sm text-gray-500">Giám đốc HTX Rau sạch Đà Lạt</p>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="testimonial-card bg-white rounded-3xl p-8 shadow-lg transition-all duration-300 hover:-translate-y-4 hover:shadow-2xl group">
                <div class="flex text-yellow-400 mb-6 text-xl">
                    ★★★★★
                </div>
                <p class="text-gray-700 leading-relaxed mb-8 italic">
                    "Chúng tôi đã xuất khẩu sang thị trường EU nhờ hệ thống truy xuất nguồn gốc của MDTrace. 
                    Đáp ứng đầy đủ tiêu chuẩn mà không cần đầu tư hạ tầng phức tạp."
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden">
                        <img src="https://via.placeholder.com/48x48/EC4899/ffffff?text=TL" alt="Trần Thị Lan" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">Trần Thị Lan</p>
                        <p class="text-sm text-gray-500">CEO Công ty TNHH Thực phẩm Sạch</p>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="testimonial-card bg-white rounded-3xl p-8 shadow-lg transition-all duration-300 hover:-translate-y-4 hover:shadow-2xl group">
                <div class="flex text-yellow-400 mb-6 text-xl">
                    ★★★★★
                </div>
                <p class="text-gray-700 leading-relaxed mb-8 italic">
                    "Hệ thống rất dễ triển khai và tích hợp với ERP hiện có của chúng tôi. 
                    Đội ngũ hỗ trợ luôn phản hồi nhanh, chuyên nghiệp. Khuyến nghị 100%."
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gray-200 rounded-full overflow-hidden">
                        <img src="https://via.placeholder.com/48x48/14B8A6/ffffff?text=PQ" alt="Phạm Quốc Hùng" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">Phạm Quốc Hùng</p>
                        <p class="text-sm text-gray-500">Trưởng phòng Chất lượng - VinFood</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@include('layouts.footer')

{{-- NÚT QUAY VỀ ĐẦU TRANG --}}
<button id="backToTop" 
        class="fixed bottom-8 right-8 bg-emerald-600 hover:bg-emerald-700 text-white w-14 h-14 rounded-2xl flex items-center justify-center shadow-xl transition-all duration-300 opacity-0 invisible z-50">
    <i class="fa-solid fa-arrow-up text-xl"></i>
</button>

<script>
    // Back to Top Button
    const backToTopBtn = document.getElementById('backToTop');

    window.addEventListener('scroll', () => {
        if (window.scrollY > 500) {
            backToTopBtn.classList.remove('opacity-0', 'invisible');
            backToTopBtn.classList.add('opacity-100', 'visible');
        } else {
            backToTopBtn.classList.add('opacity-0', 'invisible');
            backToTopBtn.classList.remove('opacity-100', 'visible');
        }
    });

    backToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
</script>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>