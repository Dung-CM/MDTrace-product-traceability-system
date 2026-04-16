<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; background: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); margin: auto; }
        .header { text-align: center; border-bottom: 2px solid #10b981; padding-bottom: 20px; margin-bottom: 20px; }
        .header h1 { color: #10b981; margin: 0; }
        .content { color: #333; line-height: 1.6; }
        .btn { display: inline-block; background-color: #10b981; color: #ffffff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
        .footer { text-align: center; font-size: 12px; color: #888; margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Hệ thống MDTrace</h1>
        </div>
        <div class="content">
            <h3>Kính chào quý doanh nghiệp, {{ $enterpriseName }}!</h3>
            <p>Hồ sơ đăng ký tham gia Hệ thống Truy xuất Nguồn gốc Blockchain MDTrace của quý vị đã được Ban quản trị phê duyệt thành công.</p>
            <p>Ngay bây giờ, quý vị có thể đăng nhập vào hệ thống để bắt đầu thiết lập thông tin sản phẩm và khởi tạo các lô hàng áp dụng mã QR chuẩn GS1.</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/login') }}" class="btn">Đăng nhập hệ thống ngay</a>
            </div>
            
            <p style="margin-top: 30px;">Nếu cần hỗ trợ kỹ thuật, vui lòng phản hồi lại email này.</p>
            <p>Trân trọng,<br><strong>Đội ngũ MDTrace</strong></p>
        </div>
        <div class="footer">
            Đây là email tự động, vui lòng không trả lời trực tiếp trừ khi cần hỗ trợ.
        </div>
    </div>
</body>
</html>