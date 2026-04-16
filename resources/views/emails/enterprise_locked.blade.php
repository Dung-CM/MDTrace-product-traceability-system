<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Thông báo tạm khóa tài khoản</h2>
    <p>Xin chào <strong>{{ $company_name }}</strong>,</p>
    <p>Chúng tôi lấy làm tiếc phải thông báo rằng tài khoản của bạn trên hệ thống <strong>MDTrace</strong> đã bị tạm khóa.</p>
    <div style="background: #fff4e5; border-left: 5px solid #ffa117; padding: 15px; margin: 20px 0;">
        <p><strong>Lý do:</strong> {{ $reason }}</p>
        <p><strong>Thời gian khóa:</strong> {{ $duration }}</p>
        @if($until != 'Không xác định')
            <p><strong>Ngày dự kiến mở khóa:</strong> {{ $until }}</p>
        @endif
    </div>
    <p>Mọi thắc mắc vui lòng liên hệ với Ban quản trị qua email này.</p>
    <p>Trân trọng,<br>Đội ngũ MDTrace</p>
</div>