<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xác nhận đặt sân</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
            margin: 0;
            padding: 40px 20px;
            -webkit-font-smoothing: antialiased;
        }
        .container {
            max-width: 520px;
            margin: 0 auto;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid #334155;
            border-radius: 24px;
            padding: 40px 32px;
        }
        .header {
            text-align: center;
            margin-bottom: 32px;
        }
        .header h1 {
            font-size: 22px;
            font-weight: 800;
            color: #4ade80;
            margin: 0 0 6px;
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 12px;
            color: #94a3b8;
            margin: 0;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #334155, transparent);
            margin: 24px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #1e293b;
            font-size: 14px;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #94a3b8;
            font-weight: 600;
        }
        .info-value {
            color: #f1f5f9;
            font-weight: 700;
            text-align: right;
        }
        .price-box {
            background: rgba(74, 222, 128, 0.1);
            border: 1px solid rgba(74, 222, 128, 0.25);
            border-radius: 16px;
            padding: 16px 20px;
            margin: 24px 0;
            text-align: center;
        }
        .price-box .label {
            font-size: 11px;
            text-transform: uppercase;
            color: #94a3b8;
            letter-spacing: 1px;
            font-weight: 600;
        }
        .price-box .amount {
            font-size: 28px;
            font-weight: 800;
            color: #4ade80;
            margin-top: 4px;
        }
        .status-badge {
            display: inline-block;
            background: rgba(74, 222, 128, 0.15);
            color: #4ade80;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 4px 14px;
            border-radius: 20px;
            border: 1px solid rgba(74, 222, 128, 0.3);
        }
        .footer {
            text-align: center;
            margin-top: 32px;
            font-size: 12px;
            color: #475569;
            line-height: 1.6;
        }
        .footer a {
            color: #4ade80;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Đặt sân thành công</h1>
            <p>CNW PlayManagement</p>
        </div>

        <div class="divider"></div>

        <div class="info-row">
            <span class="info-label">Mã đặt lịch</span>
            <span class="info-value">#PLAY{{ $booking->id }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Môn thể thao</span>
            <span class="info-value">{{ optional($booking->field?->sport)->name ?? 'N/A' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Sân</span>
            <span class="info-value">{{ $booking->field->name ?? 'N/A' }}</span>
        </div>
        @if ($booking->field->address ?? false)
        <div class="info-row">
            <span class="info-label">Địa chỉ</span>
            <span class="info-value">{{ $booking->field->address }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Ngày đặt</span>
            <span class="info-value">{{ \Illuminate\Support\Carbon::parse($booking->booking_date)->format('d/m/Y') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Giờ đặt</span>
            <span class="info-value">
                @if ($booking->timeSlot)
                    {{ substr($booking->timeSlot->start_time, 0, 5) }} - {{ substr($booking->timeSlot->end_time, 0, 5) }}
                @else
                    N/A
                @endif
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Trạng thái</span>
            <span class="info-value"><span class="status-badge">Đã xác nhận</span></span>
        </div>

        <div class="price-box">
            <div class="label">Tổng tiền thanh toán</div>
            <div class="amount">{{ number_format($booking->total_price, 0, ',', '.') }}₫</div>
        </div>

        <div class="divider"></div>

        <div class="footer">
            <p>Cảm ơn bạn đã sử dụng dịch vụ của <strong>CNW PlayManagement</strong>.</p>
            <p>Vui lòng xuất trình mã đặt lịch <strong>#PLAY{{ $booking->id }}</strong> tại quầy trước giờ đấu 10 phút.</p>
            <p style="margin-top:16px;">
                <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
            </p>
        </div>
    </div>
</body>
</html>
