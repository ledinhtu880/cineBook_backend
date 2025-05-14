<!-- resources/views/emails/booking-confirmation.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <title>Xác nhận đặt vé</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #144184;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            border: 1px solid #ddd;
        }

        .booking-details {
            margin: 20px 0;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }

        .button {
            background-color: #144184;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Xác nhận đặt vé thành công</h1>
        </div>

        <div class="content">
            <p>Chào {{ $booking->user->name }},</p>

            <p>Cảm ơn bạn đã đặt vé xem phim tại CineBook. Dưới đây là chi tiết đơn hàng của bạn:</p>

            <div class="booking-details">
                <p><strong>Mã đặt vé:</strong> {{ $booking->code }}</p>
                <p><strong>Phim:</strong> {{ $booking->showtime->movie->title }}</p>
                <p><strong>Rạp:</strong> {{ $booking->showtime->cinema->name }}</p>
                <p><strong>Suất chiếu:</strong>
                    {{ \Carbon\Carbon::parse($booking->showtime->start_time)->format('H:i, d/m/Y') }}</p>
                <p><strong>Ghế:</strong>
                    @foreach($booking->bookingDetails as $detail)
                        {{ $detail->seat->seat_code }}@if(!$loop->last), @endif
                    @endforeach
                </p>
                @if($booking->bookingCombos && $booking->bookingCombos->count() > 0)
                    <p><strong>Combo:</strong>
                        @foreach($booking->bookingCombos as $bookingCombo)
                            {{ $bookingCombo->combo->name }} ({{ $bookingCombo->quantity }})@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @endif
                <p><strong>Tổng tiền:</strong> {{ $booking->total_price_formatted }}</p>
            </div>

            <p>Vui lòng đến rạp trước giờ chiếu 15-30 phút để nhận vé. Bạn có thể xuất trình mã đặt vé hoặc email này
                tại quầy vé.</p>
        </div>

        <div class="footer">
            <p>Email này được gửi tự động, vui lòng không phản hồi.</p>
            <p>&copy; 2025 CineBook. Tất cả các quyền được bảo lưu.</p>
        </div>
    </div>
</body>

</html>