<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giới thiệu - MDTrace</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .hero-bg {
            background: linear-gradient(135deg, #0A2540 0%, #1E3A8A 100%);
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">

    <!-- Header (Menu) -->
    @include('layouts.header')

    <!-- Phần 1: Hero & Ý tưởng -->
    <section class="hero-bg pt-32 pb-20 text-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-6 text-center relative z-10">
            <span class="inline-block py-1 px-3 rounded-full bg-emerald-500/20 text-emerald-400 text-sm font-semibold mb-4 border border-emerald-500/30">VỀ CHÚNG TÔI</span>
            <h1 class="text-4xl md:text-5xl font-bold mb-6">Ý Tưởng Xây Dựng MDTrace</h1>
            <p class="text-lg text-gray-300 leading-relaxed">
                Trong bối cảnh thị trường hàng hóa ngày càng phức tạp, việc phân biệt hàng thật - hàng giả và theo dõi nguồn gốc sản phẩm trở thành một thách thức lớn. MDTrace ra đời với sứ mệnh mang lại sự <span class="text-emerald-400 font-semibold">minh bạch tuyệt đối</span> cho chuỗi cung ứng. Bằng cách kết hợp sức mạnh của công nghệ Blockchain phi tập trung và tiêu chuẩn truy xuất toàn cầu, chúng tôi mong muốn xây dựng một cây cầu vững chắc kết nối niềm tin giữa doanh nghiệp và người tiêu dùng.
            </p>
        </div>
    </section>

    <!-- Phần 2: Nội dung nổi bật của MDTrace -->
    <section class="py-20 bg-white">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Giá Trị Cốt Lõi & Tính Năng Nổi Bật</h2>
                <p class="text-gray-500 mt-4">Những yếu tố làm nên sự khác biệt của hệ thống truy xuất MDTrace.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-link"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Công Nghệ Blockchain</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Sử dụng chuỗi khối để mã hóa và niêm phong dữ liệu. Đảm bảo mọi thông tin về lô hàng, nhật ký sản xuất là bất biến và không thể bị giả mạo hay chỉnh sửa trái phép.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-qrcode"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Chuẩn Hóa GS1 Toàn Cầu</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Mã QR truy xuất được xây dựng dựa trên tiêu chuẩn GTIN và cấu trúc GS1, giúp sản phẩm dễ dàng tích hợp, lưu thông và xuất khẩu sang các thị trường quốc tế.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-gray-50 rounded-3xl p-8 border border-gray-100 hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center text-2xl mb-6">
                        <i class="fa-solid fa-mobile-screen"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Trải Nghiệm Tối Ưu</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Giao diện trực quan cho cả doanh nghiệp quản lý lẫn người dùng cuối. Chỉ với 1 giây quét mã bằng camera điện thoại, toàn bộ hành trình sản phẩm sẽ hiện ra chi tiết.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Phần 3 & 4: Thông tin Nhà phát triển & Trường Đại học -->
    <section class="py-20 bg-gray-50 border-t border-gray-200">
        <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-12">
            
            <!-- Cột Người làm trang web -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4 mb-6 border-b border-gray-100 pb-6">
                    <div class="w-24 h-24 bg-gray-100 rounded-full border-4 border-emerald-50 flex items-center justify-center text-gray-300 overflow-hidden">
                        <i class="fa-solid fa-user text-3xl"></i>
                        <img src="{{ asset('images/avtadmin.jpg') }}" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Sinh Viên Thực Hiện</h3>
                        <p class="text-emerald-600 font-medium">CHAU MẠNH DŨNG - DTH225615 - KHÓA 23TH</p>
                        <p class="text-gray-500 text-sm mt-1">Sinh viên chuyên ngành Công nghệ thông tin</p>
                    </div>
                </div>
                <div class="prose prose-sm text-gray-600">
                    <p>
                        Là một sinh viên năm cuối chuyên ngành Công nghệ thông tin, 
                        tôi luôn nuôi dưỡng niềm đam mê mãnh liệt với việc kiến tạo ra những sản phẩm phần mềm mang lại giá trị thực tiễn cao cho cộng đồng. 
                        Định hướng nghề nghiệp mà tôi đang theo đuổi và dồn tâm huyết phát triển chính là Lập trình Web kết hợp với Thiết kế trải nghiệm người dùng UI UX. 
                        Đối với tôi một hệ thống phần mềm thành công không chỉ nằm ở những thuật toán phức tạp hay kiến trúc cơ sở dữ liệu đồ sộ phía sau mà còn phải được thể hiện qua một giao diện trực quan, thân thiện và dễ dàng tiếp cận đối với mọi đối tượng.
                         Tư duy này đã trở thành kim chỉ nam xuyên suốt quá trình học tập của tôi và được ứng dụng triệt để vào việc thực hiện đồ án thực tập cuối khóa này.
                    </p>
                    <p>
                        Khi tiếp nhận đề tài xây dựng website tạo mã truy xuất thông tin cho hàng hóa, tôi nhận thấy đây là một cơ hội tuyệt vời để cọ xát thực tế nhưng cũng đi kèm với vô vàn thách thức kỹ thuật. Về mặt lập trình web, hệ thống đòi hỏi một kiến trúc backend vững chắc sử dụng framework Laravel kết hợp với cơ chế lưu trữ lai giữa cơ sở dữ liệu MySQL và công nghệ chuỗi khối Blockchain. Tuy nhiên dưới góc độ của một người đam mê UI UX, bài toán hóc búa nhất đặt ra là làm thế nào để thiết kế một nền tảng phần mềm SaaS đa doanh nghiệp mà ở đó các hợp tác xã nông nghiệp hay những doanh nghiệp vừa và nhỏ không rành về công nghệ vẫn có thể dễ dàng thao tác khởi tạo lô hàng và sinh mã QR. Việc cân bằng giữa sự phức tạp của hệ thống xác thực mật mã học và sự tối giản của giao diện người dùng thực sự là một rào cản lớn mà tôi phải nỗ lực vượt qua.
                        Quá trình tìm hiểu và hiện thực hóa đồ án đã diễn ra với không ít những khó khăn và những lần thử nghiệm thất bại. Ban đầu tôi gặp rào cản lớn trong việc tích hợp API giao tiếp giữa hệ thống web truyền thống và mạng lưới Blockchain phân tán sao cho mượt mà không làm ảnh hưởng đến tốc độ tải trang. Bên cạnh đó việc phác thảo và tinh chỉnh giao diện cho ba nhóm tác nhân khác nhau bao gồm quản trị viên, doanh nghiệp sản xuất và khách hàng tra cứu cũng tiêu tốn rất nhiều thời gian nghiên cứu hành vi người dùng. Bằng sự nỗ lực tự học không ngừng nghỉ, áp dụng phương pháp thiết kế lấy người dùng làm trung tâm và đặc biệt là nhờ sự tiếp thu ý kiến định hướng từ giảng viên hướng dẫn cùng các anh chị tại đơn vị thực tập, tôi đã từng bước tháo gỡ được những nút thắt kỹ thuật và hoàn thiện sản phẩm.
                        Việc hoàn thành đồ án này không chỉ là một cột mốc đánh dấu sự khép lại chặng đường đại học mà còn là minh chứng rõ nét cho sự trưởng thành trong tư duy phát triển sản phẩm của bản thân tôi. Trải nghiệm thực tế này đã giúp tôi củng cố nền tảng lập trình web vững chắc, đồng thời mài giũa tư duy thiết kế giao diện UI UX để giải quyết trọn vẹn bài toán minh bạch thông tin hàng hóa. Những kiến thức và kỹ năng quý giá tích lũy được từ dự án sẽ là hành trang vững chắc tiếp thêm động lực để tôi theo đuổi và chinh phục con đường trở thành một chuyên gia phát triển ứng dụng web và thiết kế trải nghiệm người dùng trong tương lai.
                    </p>
                </div>
            </div>

            <!-- Cột Trường Đại học -->
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
                <div class="flex items-center gap-4 mb-6 border-b border-gray-100 pb-6">
                    <div class="w-24 h-24 bg-gray-100 rounded-xl border border-gray-200 flex items-center justify-center text-gray-300 overflow-hidden p-2">
                        <i class="fa-solid fa-graduation-cap text-3xl"></i>
                        <img src="{{ asset('images/logoagu.jpg') }}" class="w-full h-full object-contain">
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">Trường Đại Học An Giang</h3>
                        <p class="text-blue-600 font-medium">Đại học Quốc gia TP. Hồ Chí Minh</p>
                        <p class="text-gray-500 text-sm mt-1">Khoa Công nghệ thông tin</p>
                    </div>
                </div>
                <div class="prose prose-sm text-gray-600">
                    <p>
                        Trải qua chặng đường gắn bó dưới mái trường Đại học An Giang thuộc Đại học Quốc gia Thành phố Hồ Chí Minh, tôi luôn tự hào khi được rèn luyện và trưởng thành tại Khoa Công nghệ Thông tin. Môi trường học tập năng động, gắn liền thực tiễn cùng hệ thống kiến thức chuyên ngành vững chắc mà quý thầy cô truyền đạt đã trở thành bệ phóng quan trọng giúp tôi tự tin bước vào con đường nghề nghiệp.
                    </p>
                    <p>
                        Để đồ án thực tập này được hoàn thiện một cách trọn vẹn nhất, tôi xin gửi lời tri ân sâu sắc đến Thầy TS. Huỳnh Lý Thanh Nhàn. Trong suốt quá trình nghiên cứu và thực hiện đề tài, Thầy đã luôn dành thời gian tận tình chỉ bảo, định hướng chuyên môn và đưa ra những lời khuyên quý báu giúp tôi tháo gỡ nhiều vướng mắc kỹ thuật. Sự tâm huyết và những kinh nghiệm thực tiễn mà Thầy truyền đạt chính là nguồn động lực to lớn giúp tôi hoàn thành tốt dự án này.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    @include('layouts.footer')

</body>
</html>