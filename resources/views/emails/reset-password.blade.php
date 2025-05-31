<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password - Miftahul Ulum</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #009688, #00796b);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #333;
            margin-bottom: 20px;
        }
        .message {
            color: #666;
            margin-bottom: 30px;
            font-size: 16px;
        }
        .reset-button {
            display: inline-block;
            background: linear-gradient(135deg, #009688, #00796b);
            color: white;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            text-align: center;
            margin: 20px 0;
            transition: background 0.3s ease;
        }
        .reset-button:hover {
            background: linear-gradient(135deg, #00796b, #004d40);
        }
        .token-info {
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .token-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
        }
        .token-code {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            color: #009688;
            font-weight: bold;
            letter-spacing: 2px;
            background-color: white;
            padding: 10px;
            border-radius: 4px;
            border: 2px solid #009688;
            text-align: center;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px 30px;
            text-align: center;
            color: #666;
            font-size: 14px;
            border-top: 1px solid #e9ecef;
        }
        .logo {
            width: 60px;
            height: 60px;
            background-color: white;
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #009688;
        }
        @media (max-width: 600px) {
            .container {
                margin: 20px;
                border-radius: 0;
            }
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🏫</div>
            <h1>Reset Password</h1>
            <p style="margin: 0; opacity: 0.9;">Miftahul Ulum Mobile App</p>
        </div>

        <div class="content">
            <div class="greeting">
                Assalamu'alaikum {{ $nama }},
            </div>

            <div class="message">
                Kami menerima permintaan untuk mereset password akun Anda. Jika Anda tidak melakukan permintaan ini, abaikan email ini.
            </div>

            <div class="message">
                Untuk melanjutkan proses reset password, silakan klik tombol di bawah ini:
            </div>

            <div style="text-align: center;">
                <a href="{{ $resetLink }}" class="reset-button">
                    🔐 Reset Password Sekarang
                </a>
            </div>

            <div class="token-info">
                <div class="token-label">Atau gunakan kode verifikasi berikut:</div>
                <div class="token-code">{{ $token }}</div>
                <div style="margin-top: 10px; font-size: 14px; color: #666;">
                    Masukkan kode ini di aplikasi mobile untuk memverifikasi identitas Anda.
                </div>
            </div>

            <div class="warning">
                <strong>⚠️ Penting:</strong>
                <ul style="margin: 10px 0; padding-left: 20px;">
                    <li>Link ini akan kadaluarsa dalam <strong>60 menit</strong></li>
                    <li>Jangan bagikan link atau kode ini kepada siapa pun</li>
                    <li>Jika Anda tidak meminta reset password, segera hubungi administrator</li>
                </ul>
            </div>

            <div class="message" style="margin-top: 30px; font-size: 14px;">
                Jika tombol di atas tidak berfungsi, salin dan tempel URL berikut ke browser Anda:
                <br>
                <span style="color: #009688; word-break: break-all;">{{ $resetLink }}</span>
            </div>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon jangan membalas email ini.</p>
            <p>© {{ date('Y') }} Miftahul Ulum - Sistem Informasi Pesantren</p>
            <p style="margin-top: 15px;">
                <strong>Butuh bantuan?</strong><br>
                Hubungi administrator di: admin@miftahululum.sch.id
            </p>
        </div>
    </div>
</body>
</html>
