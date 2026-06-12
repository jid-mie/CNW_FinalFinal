<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=index.html">
    <title>Xác nhận OTP</title>
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
            max-width: 500px;
            margin: 0 auto;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
            text-align: center;
        }
        .logo {
            font-size: 24px;
            font-weight: 800;
            background: linear-gradient(90deg, #38bdf8, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 24px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        h1 {
            font-size: 22px;
            font-weight: 700;
            color: #f8fafc;
            margin-top: 0;
            margin-bottom: 12px;
        }
        p {
            font-size: 15px;
            line-height: 1.6;
            color: #94a3b8;
            margin-bottom: 28px;
        }
        .otp-box {
            background: rgba(59, 130, 246, 0.1);
            border: 2px dashed #3b82f6;
            border-radius: 12px;
            padding: 16px;
            font-size: 32px;
            font-weight: 800;
            letter-spacing: 6px;
            color: #38bdf8;
            display: inline-block;
            margin-bottom: 28px;
            min-width: 200px;
            text-shadow: 0 0 10px rgba(56, 189, 248, 0.3);
        }
        .footer {
            font-size: 12px;
            color: #64748b;
            margin-top: 32px;
            border-top: 1px solid #334155;
            padding-top: 20px;
        }
        .highlight {
            color: #38bdf8;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">CNW Play</div>
        <h1>Mã Xác Nhận Đăng Ký</h1>
        <p>Cảm ơn bạn đã lựa chọn hệ thống đặt sân thể thao <span class="highlight">CNW PlayManagement</span>. Để tiếp tục quá trình đăng ký tài khoản, vui lòng nhập mã OTP dưới đây:</p>
        
        <div class="otp-box">
            {{ $code }}
        </div>
        
        <p style="font-size: 13px; color: #64748b; margin-top: 0;">Mã xác nhận này sẽ hết hạn sau <span class="highlight">5 phút</span>. Vui lòng không chia sẻ mã này với bất kỳ ai để bảo vệ tài khoản của bạn.</p>
        
        <div class="footer">
            Đây là email tự động gửi từ hệ thống CNW PlayManagement.<br>
            © {{ date('Y') }} CNW Play. All rights reserved.
        </div>
    </div>
</body>
</html>
