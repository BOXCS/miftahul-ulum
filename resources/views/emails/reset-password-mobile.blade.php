<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Reset Password</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #009688, #00796b);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
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
        .token-container {
            background: #f8f9fa;
            border: 2px dashed #009688;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            margin: 30px 0;
        }
        .token {
            font-size: 32px;
            font-weight: bold;
            color: #009688;
            letter-spacing: 8px;
            font-family: 'Courier New', monospace;
        }
        .token-label {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
        }
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .warning-text {
            color: #856404;
            font-size: 14px;
        }
        .instructions {
            background: #e8f5e8;
            border-left: 4px solid #28a745;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .instructions h3 {
            color: #155724;
            margin-top: 0;
            font-size: 16px;
        }
        .instructions ol {
            color: #155724;
            margin: 10px 0;
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 5px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .footer p {
            margin: 0;
            color: #666;
            font-size: 14px;
        }
        .help-text {
            color: #999;
            font-size: 12px;
            margin-top: 20px;
            text-align: center;
        }
        @media (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 5px;
            }
            .content {
                padding: 20px 15px;
            }
            .token {
                font-size: 28px;
                letter-spacing: 4px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Kode Verifikasi</h1>
        </div>

        <div class="content">
            <div class="greeting">
                Halo {{ $nama }},
            </div>

            <div class="message">
                Kami menerima permintaan untuk mereset password akun Anda. Untuk melanjutkan proses reset password, silakan masukkan kode verifikasi berikut di aplikasi mobile:
            </div>

            <div class="token-container">
                <div class="token">{{ $token }}</div>
                <div class="token-label">Kode Verifikasi (6 Digit)</div>
            </div>

            <div class="instructions">
                <h3>📱 Cara Menggunakan:</h3>
                <ol>
                    <li>Buka aplikasi mobile Miftahul Ulum</li>
                    <li>Masukkan kode verifikasi <strong>{{ $token }}</strong> di halaman verifikasi</li>
                    <li>Tap tombol "Verifikasi" untuk menyelesaikan proses</li>
                    <li>Password Anda akan berhasil direset</li>
                </ol>
            </div>

            <div class="warning">
                <div class="warning-text">
                    <strong>⚠️ Penting:</strong>
                    <ul style="margin: 10px 0; padding-left: 20px;">
                        <li>Kode ini akan kedaluwarsa dalam <strong>5 menit</strong></li>
                        <li>Jangan bagikan kode ini kepada siapa pun</li>
                        <li>Jika Anda tidak meminta reset password, abaikan email ini</li>
                    </ul>
                </div>
            </div>

            <div class="help-text">
                Jika Anda mengalami masalah atau tidak meminta reset password, silakan hubungi administrator atau abaikan email ini.
            </div>
        </div>

        <div class="footer">
            <p><strong>Miftahul Ulum</strong></p>
            <p>Email otomatis - Mohon tidak membalas email ini</p>
        </div>
    </div>
</body>
</html>
