<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông báo từ hệ thống MDTrace</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f9fafb; margin: 0; padding: 20px; }
        .email-wrapper { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); }
        .header { background-color: #ef4444; color: #ffffff; padding: 20px; text-align: center; }
        .header h2 { margin: 0; font-size: 20px; font-weight: 600; }
        .body-content { padding: 30px; }
        .reason-box { background-color: #fef2f2; border-left: 4px solid #dc2626; padding: 15px 20px; margin: 20px 0; border-radius: 0 8px 8px 0; color: #991b1b; }
        .footer { background-color: #f3f4f6; padding: 15px; text-align: center; font-size: 12px; color: #6b7280; border-top: 1px solid #e5e7eb; }
        .btn-contact { display: inline-block; background-color: #4b5563; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 15px; font-weight: bold; font-size: 14px;}
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h2>Hệ thống MDTrace</h2>
        </div>
        
        <div class="body-content">
            <p>Xin chào <strong>{{ $company_name }}</strong>,</p>
            
            <p>Cảm ơn doanh nghiệp đã quan tâm và đăng ký tham gia Hệ thống Truy xuất nguồn gốc MDTrace.</p>
            
            <p>Sau khi xem xét hồ sơ đăng ký và đối chiếu với cơ sở dữ liệu pháp lý, Ban quản trị rất tiếc phải thông báo hồ sơ của doanh nghiệp <strong>chưa được xét duyệt</strong> vào lúc này.</p>
            
            <p><strong>Lý do từ chối cụ thể:</strong></p>
            
            <div class="reason-box">
                <em>{{ $reason }}</em>
            </div>
            
            <p>Vui lòng kiểm tra lại thông tin. Nếu doanh nghiệp cho rằng có sự nhầm lẫn hoặc đã khắc phục được vấn đề nêu trên, xin vui lòng đăng ký lại tài khoản mới với thông tin chính xác hơn.</p>
            
            <p>Trân trọng,<br><strong>Ban Quản trị MDTrace</strong></p>
        </div>
        
        <div class="footer">
            Đây là email tự động từ hệ thống. Vui lòng không trả lời email này. <br>
            © {{ date('Y') }} MDTrace. Bảo vệ thương hiệu, minh bạch nguồn gốc.
        </div>
    </div>
</body>
</html>