<!-- resources/views/layouts/footer.blade.php -->
{{-- FOOTER --}}
<footer class="bg-[#0A2540] text-gray-300 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10">
            
            <!-- Cột 1: Logo & Giới thiệu -->
            <div class="md:col-span-4">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-11 h-11 bg-emerald-500 rounded-2xl flex items-center justify-center text-white text-3xl font-bold">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <span class="text-3xl font-bold text-white">MD<span class="text-emerald-400">Trace</span></span>
                </div>
                <p class="text-gray-400 leading-relaxed max-w-sm">
                    Nền tảng tạo mã truy xuất nguồn gốc hàng hóa hàng đầu Việt Nam. 
                    Kết hợp QR Code và Blockchain giúp doanh nghiệp minh bạch thông tin và chống hàng giả hiệu quả.
                </p>

                <!-- Chứng nhận -->
                <div class="flex gap-3 mt-8">
                    <div class="bg-white/10 text-white text-xs px-4 py-2 rounded-xl border border-white/20">ISO 27001</div>
                    <div class="bg-white/10 text-white text-xs px-4 py-2 rounded-xl border border-white/20">GDPR</div>
                    <div class="bg-white/10 text-white text-xs px-4 py-2 rounded-xl border border-white/20">SSL</div>
                </div>
            </div>

            <!-- Cột 2: Liên kết nhanh -->
            <div class="md:col-span-2">
                <h4 class="text-white font-semibold mb-6">LIÊN KẾT NHANH</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#how-it-works" class="hover:text-emerald-400 transition">Quy trình</a></li>
                    <li><a href="#benefits" class="hover:text-emerald-400 transition">Lợi ích</a></li>
                    <li><a href="#testimonials" class="hover:text-emerald-400 transition">Đánh giá</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition">Sản Phẩm </a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition">Doanh nghiệp</a></li>
                   
                </ul>
            </div>

            <!-- Cột 3: Liên hệ -->
            <div class="md:col-span-3">
                <h4 class="text-white font-semibold mb-6">LIÊN HỆ</h4>
                <div class="space-y-4 text-sm">
                    <div class="flex gap-3">
                        <i class="fa-solid fa-location-dot mt-1 text-emerald-400"></i>
                        <div>
                            723A Đường Hà Hoàng Hổ, Phường Mỹ Xuyên<br>
                            An Giang
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <i class="fa-solid fa-phone mt-1 text-emerald-400"></i>
                        <div>0123 456 789</div>
                    </div>
                    <div class="flex gap-3">
                        <i class="fa-solid fa-envelope mt-1 text-emerald-400"></i>
                        <div>info@mdtrace.com</div>
                    </div>
                    <div class="flex gap-3">
                        <i class="fa-solid fa-globe mt-1 text-emerald-400"></i>
                        <div>www.mdtrace.com</div>
                    </div>
                </div>
            </div>

            <!-- Cột 4: Theo dõi & Newsletter -->
            <div class="md:col-span-3">
                <h4 class="text-white font-semibold mb-6">THEO DÕI CHÚNG TÔI</h4>
                <div class="flex gap-4 mt-4">
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#1877F2] hover:text-white transition-all group">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-full bg-white/5 flex items-center justify-center hover:bg-[#FF0000] hover:text-white transition-all group">
                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                        </a>
                    </div>

                <h4 class="text-white font-semibold mt-4">Nhận tin tức & cập nhật</h4>
                <div class="flex">
                    <input type="email" 
                           placeholder="Email của bạn" 
                           class="bg-white/10 border border-white/20 focus:border-emerald-400 text-white placeholder-gray-400 px-5 py-3  mt-4 rounded-l-2xl w-full outline-none text-sm">
                    <button class="bg-emerald-500 hover:bg-emerald-600 px-8 mt-4 rounded-r-2xl transition">
                        →
                    </button>
                </div>
            </div>
        </div>

        <!-- Bottom bar -->
        <div class="border-t border-white/10 mt-16 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-400">
            <div>
                © 2026 MDTrace. Đồ Án Tốt nghiệp Trường Đại Học An Giang - ĐHQG TPHCM.
            </div>
            <div class="flex gap-6 mt-4 md:mt-0">
                <a href="#" class="hover:text-white transition">Điều khoản dịch vụ</a>
                <a href="#" class="hover:text-white transition">Chính sách bảo mật</a>
                <a href="#" class="hover:text-white transition">GDPR</a>
            </div>
        </div>
    </div>
</footer>