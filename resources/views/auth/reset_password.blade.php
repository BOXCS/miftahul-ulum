<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Miftahul Ulum</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #009688, #00796b);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }

        .header {
            background: linear-gradient(135deg, #009688, #00796b);
            color: white;
            text-align: center;
            padding: 40px 30px;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .header p {
            opacity: 0.9;
            font-size: 16px;
        }

        .form-container {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }

        .form-group input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            outline: none;
            border-color: #009688;
            box-shadow: 0 0 0 3px rgba(0, 150, 136, 0.1);
        }

        .password-group {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            font-size: 18px;
        }

        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #009688, #00796b);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
        }

        .submit-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .message {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }

        .success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .info-box {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .info-box h3 {
            color: #009688;
            margin-bottom: 10px;
        }

        .info-box p {
            color: #666;
            line-height: 1.5;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #009688;
            text-decoration: none;
            font-weight: 500;
        }

        .back-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 480px) {
            .container {
                margin: 10px;
                border-radius: 15px;
            }

            .form-container {
                padding: 30px 20px;
            }

            .header {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">🔐</div>
            <h1>Reset Password</h1>
            <p>Masukkan password baru Anda</p>
        </div>

        <div class="form-container">
            <div class="info-box">
                <h3>Verifikasi Berhasil!</h3>
                <p>Link verifikasi Anda valid. Silakan masukkan password baru untuk akun Anda.</p>
            </div>

            <form id="resetForm" action="/api/web-reset-password" method="POST">
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div class="form-group">
                    <label for="email_display">Email</label>
                    <input type="email" id="email_display" value="{{ $email }}" disabled>
                </div>

                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="password-group">
                        <input type="password" id="password" name="password" required
                               placeholder="Minimal 8 karakter">
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            👁️
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="password-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               placeholder="Masukkan ulang password baru">
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                            👁️
                        </button>
                    </div>
                </div>

                <button type="submit" class="submit-btn" id="submitBtn">
                    Reset Password
                </button>
            </form>

            <div class="back-link">
                <a href="javascript:void(0)" onclick="redirectToApp()">
                    ← Kembali ke Aplikasi
                </a>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const button = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁️';
            }
        }

        function redirectToApp() {
            // Coba buka deep link ke aplikasi mobile
            const deepLink = 'miftahululum://login';
            const fallbackUrl = '/';

            // Untuk Android dan iOS
            if (navigator.userAgent.match(/Android/i)) {
                window.location.href = deepLink;
                setTimeout(() => {
                    window.location.href = fallbackUrl;
                }, 1000);
            } else if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) {
                window.location.href = deepLink;
                setTimeout(() => {
                    window.location.href = fallbackUrl;
                }, 1000);
            } else {
                window.location.href = fallbackUrl;
            }
        }

        document.getElementById('resetForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const submitBtn = document.getElementById('submitBtn');

            // Validasi
            if (password.length < 8) {
                alert('Password minimal 8 karakter');
                return;
            }

            if (password !== passwordConfirmation) {
                alert('Konfirmasi password tidak cocok');
                return;
            }

            // Disable button dan show loading
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';

            // Submit form
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.message.includes('berhasil')) {
                    // Success
                    document.querySelector('.form-container').innerHTML = `
                        <div class="message success">
                            <h3>✅ Password Berhasil Direset!</h3>
                            <p>Password Anda telah berhasil diubah. Silakan login dengan password baru Anda.</p>
                        </div>
                        <div style="text-align: center; margin-top: 30px;">
                            <button onclick="redirectToApp()" class="submit-btn">
                                Kembali ke Aplikasi
                            </button>
                        </div>
                    `;
                } else {
                    throw new Error(data.message || 'Gagal mereset password');
                }
            })
            .catch(error => {
                // Error
                const errorDiv = document.createElement('div');
                errorDiv.className = 'message error';
                errorDiv.innerHTML = `<strong>❌ Gagal!</strong><br>${error.message}`;

                this.insertBefore(errorDiv, this.firstChild);

                // Re-enable button
                submitBtn.disabled = false;
                submitBtn.textContent = 'Reset Password';

                // Remove error message after 5 seconds
                setTimeout(() => {
                    if (errorDiv.parentNode) {
                        errorDiv.remove();
                    }
                }, 5000);
            });
        });
    </script>
</body>
</html>
