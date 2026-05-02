<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Portal Santri & Wali - Miftahul Ulum Kalisat</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700&family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --teal-deep:    #0d4f4a;
            --teal-mid:     #136f65;
            --teal-main:    #1a9b8a;
            --teal-light:   #2dc4af;
            --teal-glow:    #4de8d0;
            --gold:         #c8a951;
            --gold-light:   #e4c97e;
            --white:        #ffffff;
            --off-white:    #f4f9f8;
            --glass-bg:     rgba(255,255,255,0.92);
            --glass-border: rgba(255,255,255,0.6);
            --shadow-card:  0 32px 80px rgba(13,79,74,0.25), 0 8px 24px rgba(13,79,74,0.15);
            --shadow-btn:   0 8px 32px rgba(26,155,138,0.45);
            --input-border: #b2d8d4;
            --input-focus:  #1a9b8a;
            --text-dark:    #0d2e2b;
            --text-mid:     #3a6b66;
            --text-light:   #7ab5af;
            --radius-card:  24px;
            --radius-input: 50px;
        }

        html, body {
            height: 100%;
            font-family: 'Nunito', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            overflow: hidden;
            background: var(--teal-deep);
        }

        /* ── BACKGROUND ── */
        .bg-layer {
            position: fixed;
            inset: 0;
            z-index: 0;
        }

        .bg-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }

        .bg-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(
                135deg,
                rgba(13, 79, 74, 0.82) 0%,
                rgba(13, 79, 74, 0.60) 65%,
                rgba(8, 45, 42, 0.75) 100%
            );
        }

        /* subtle animated bokeh */
        .bokeh {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.18;
            animation: drift 12s ease-in-out infinite alternate;
        }
        .bokeh-1 {
            width: 500px; height: 500px;
            background: var(--teal-glow);
            top: -100px; left: -100px;
            animation-delay: 0s;
        }
        .bokeh-2 {
            width: 400px; height: 400px;
            background: var(--gold);
            bottom: -80px; right: 200px;
            animation-delay: -4s;
        }
        .bokeh-3 {
            width: 300px; height: 300px;
            background: var(--teal-light);
            top: 50%; left: 30%;
            animation-delay: -8s;
        }
        @keyframes drift {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, 20px) scale(1.08); }
        }

        /* ── LAYOUT ── */
        .page-wrapper {
            position: relative;
            z-index: 1;
            display: flex;
            width: 100%;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 40px 24px;
        }

        .split {
            display: flex;
            width: 100%;
            max-width: 1160px;
            gap: 0;
            align-items: center;
        }

        /* ── LEFT HERO PANEL ── */
        .hero {
            flex: 1;
            padding: 48px 56px 48px 32px;
            color: var(--white);
            animation: fadeInLeft 0.9s cubic-bezier(0.22,1,0.36,1) both;
        }

        @keyframes fadeInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        .hero-logo {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 70px;
        }

        .hero-logo img {
            width: 125px;
            height: 125px;
            object-fit: contain;
            filter: drop-shadow(0 4px 16px rgba(0,0,0,0.3));
        }

        .hero-logo-text {
            display: flex;
            flex-direction: column;
        }

        .hero-logo-name {
            font-family: 'Cinzel', serif;
            font-size: 45px;
            font-weight: 900;
            letter-spacing: 0em;
            line-height: 1.25;
            color: var(--white);
        }

        .hero-logo-sub {
            font-family: 'Cinzel', serif;
            font-size: 35px;
            font-weight: 400;
            letter-spacing: 0em;
            text-transform: uppercase;
            color: var(--teal-glow);
            margin-top: 2px;
        }

        .hero-divider {
            width: 64px;
            height: 2px;
            background: linear-gradient(to right, var(--gold), transparent);
            margin-bottom: 50px;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.20);
            border-radius: 100px;
            padding: 6px 16px 6px 10px;
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--teal-glow);
            margin-bottom: 35px;
        }

        .hero-badge-dot {
            width: 10px; height: 10px;
            border-radius: 50%;
            background: var(--teal-glow);
            box-shadow: 0 0 8px var(--teal-glow);
        }

        .hero-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(15px, 10vw, 55px);
            font-weight: 700;
            line-height: 1.12;
            letter-spacing: -0.01em;
            color: var(--white);
            margin-bottom: 30px;
        }

        .hero-title span {
            color: var(--teal-glow);
        }

        .hero-desc {
            font-size: 18px;
            font-weight: 400;
            color: var(--white);
            line-height: 1.7;
            max-width: 450px;
            margin-bottom: 55px;
        }

        .hero-features {
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        .hero-feature {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            color: rgba(255,255,255,0.80);
        }

        .feature-icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.10);
            border: 1px solid rgba(255,255,255,0.15);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            font-size: 14px;
        }

        /* ── CARD ── */
        .card {
            width: 440px;
            flex-shrink: 0;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: var(--radius-card);
            box-shadow: var(--shadow-card);
            padding: 48px 44px 40px;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            animation: fadeInRight 0.9s cubic-bezier(0.22,1,0.36,1) 0.15s both;
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateY(32px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 28px;
        }

        .card-logo img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .card-title {
            font-family: 'Cinzel', serif;
            font-size: 35px;
            font-weight: 700;
            color: var(--text-dark);
            text-align: center;
            letter-spacing: 0.02em;
            margin-bottom: 6px;
        }

        .card-subtitle {
            font-size: 13.5px;
            color: var(--text-mid);
            text-align: center;
            margin-bottom: 36px;
            font-weight: 400;
        }

        /* ── FORM ── */
        .form-group {
            position: relative;
            margin-bottom: 22px;
        }

        .form-input {
            width: 100%;
            height: 54px;
            padding: 0 44px 0 16px;
            font-family: 'Nunito', sans-serif;
            font-size: 14.5px;
            font-weight: 500;
            color: var(--text-dark);
            background: var(--white);
            border: 1.5px solid var(--input-border);
            border-radius: var(--radius-input);
            outline: none;
            transition: border-color 0.25s, box-shadow 0.25s;
            appearance: none;
        }

        .form-input::placeholder {
            color: transparent;
        }

        /* Floating label */
        .form-label {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            font-weight: 500;
            color: var(--text-light);
            pointer-events: none;
            transition: all 0.22s cubic-bezier(0.4,0,0.2,1);
            background: transparent;
            padding: 0 4px;
            transform-origin: left center;
        }

        /* When focused OR has value → label floats up to border */
        .form-input:focus + .form-label,
        .form-input:not(:placeholder-shown) + .form-label {
            top: 0;
            transform: translateY(-50%) scale(0.82);
            color: var(--teal-main);
            background: var(--glass-bg);
            font-weight: 600;
        }

        .form-input:focus {
            border-color: var(--input-focus);
            box-shadow: 0 0 0 3px rgba(26,155,138,0.12);
        }

        /* Icon inside input */
        .input-icon {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .toggle-password {
            pointer-events: all;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            color: var(--text-light);
            display: flex;
            align-items: center;
            transition: color 0.2s;
        }

        .toggle-password:hover { color: var(--teal-main); }

        /* ── REMEMBER / FORGOT ── */
        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            margin-top: -6px;
        }

        .checkbox-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
        }

        .checkbox-wrap input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 17px;
            height: 17px;
            border: 1.5px solid var(--input-border);
            border-radius: 5px;
            background: var(--white);
            cursor: pointer;
            position: relative;
            transition: border-color 0.2s, background 0.2s;
            flex-shrink: 0;
        }

        .checkbox-wrap input[type="checkbox"]:checked {
            background: var(--teal-main);
            border-color: var(--teal-main);
        }

        .checkbox-wrap input[type="checkbox"]:checked::after {
            content: '';
            position: absolute;
            left: 4px;
            top: 1.5px;
            width: 5px;
            height: 9px;
            border: 2px solid #fff;
            border-top: none;
            border-left: none;
            transform: rotate(45deg);
        }

        .checkbox-wrap span {
            font-size: 13px;
            color: var(--text-mid);
            user-select: none;
        }

        .forgot-link {
            font-size: 13px;
            font-weight: 600;
            color: var(--teal-main);
            text-decoration: none;
            transition: color 0.2s;
        }

        .forgot-link:hover { color: var(--teal-deep); }

        /* ── BUTTON ── */
        .btn-login {
            width: 100%;
            height: 52px;
            background: linear-gradient(135deg, var(--teal-main) 0%, var(--teal-deep) 100%);
            color: var(--white);
            border: none;
            border-radius: var(--radius-input);
            font-family: 'Cinzel', serif;
            font-size: 14px;
            font-weight: 600;
            letter-spacing: 0.12em;
            cursor: pointer;
            box-shadow: var(--shadow-btn);
            position: relative;
            overflow: hidden;
            transition: transform 0.18s, box-shadow 0.18s;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
            transition: opacity 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(26,155,138,0.55);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* ripple */
        .btn-login .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255,255,255,0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple {
            to { transform: scale(4); opacity: 0; }
        }

        /* ── CARD FOOTER ── */
        .card-register {
            text-align: center;
            margin-top: 24px;
            font-size: 13px;
            color: var(--text-mid);
        }

        .card-register a {
            font-weight: 700;
            color: var(--teal-main);
            text-decoration: none;
        }

        .card-register a:hover { text-decoration: underline; }

        .card-links {
            display: flex;
            justify-content: center;
            gap: 24px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid rgba(0,0,0,0.07);
        }

        .card-links a {
            font-size: 12px;
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.2s;
        }

        .card-links a:hover { color: var(--teal-main); }

        /* ── VALIDATION ERROR ── */
        .error-msg {
            font-size: 12px;
            color: #e05252;
            margin-top: 5px;
            padding-left: 4px;
        }

        /* ── ALERT ── */
        .alert {
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
        }

        .alert-success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 860px) {
            .hero { display: none; }
            .split { justify-content: center; }
            .card { width: 100%; max-width: 420px; }
        }

        @media (max-width: 480px) {
            .card { padding: 36px 28px 32px; }
        }
    </style>
</head>
<body>

<!-- Background -->
<div class="bg-layer">
    <img src="{{ asset('assets/hehe.jpg') }}" alt="" class="bg-image">
    <div class="bg-overlay"></div>
    <div class="bokeh bokeh-1"></div>
    <div class="bokeh bokeh-2"></div>
    <div class="bokeh bokeh-3"></div>
</div>

<!-- Page -->
<div class="page-wrapper">
    <div class="split">

        <!-- ── Hero / Branding ── -->
        <div class="hero">
            <div class="hero-logo">
                <img src="{{ asset('assets/logo2.png') }}" alt="Logo Miftahul Ulum Kalisat">
                <div class="hero-logo-text">
                    <span class="hero-logo-name">Pondok Pesantren</span>
                    <span class="hero-logo-sub">Miftahul Ulum Kalisat</span>

                </div>
            </div>

            <div class="hero-badge">
                <span class="hero-badge-dot"></span>
                Portal Resmi
            </div>

            <div class="hero-divider"></div>

            <h1 class="hero-title">
                Portal Admin<br>& Pengurus Pondok</span>
            </h1>

            <p class="hero-desc">
                Mencetak Generasi Rabbani Berakhlakul Karimah.<br>
                Akses informasi, perkembangan santri, dan komunikasi resmi dalam satu platform terpadu.
            </p>
        </div>

        <!-- ── Login Card ── -->
        <div class="card">

            <div class="card-logo">
                <img src="{{ asset('assets/logo.png') }}" alt="Logo">
            </div>

            <h2 class="card-title">Selamat Datang</h2>
            <p class="card-subtitle">Akses Akun Anda</p>

            {{-- Session Error --}}
            @if (session('error'))
                <div class="alert alert-error">
                    ⚠️ {{ session('error') }}
                </div>
            @endif

            {{-- Status Success --}}
            @if (session('status'))
                <div class="alert alert-success">
                    ✅ {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="form-group">
                    <input
                        id="email"
                        type="email"
                        name="email"
                        class="form-input"
                        value="{{ old('email') }}"
                        placeholder=" "
                        autocomplete="email"
                        autofocus
                        required
                    >
                    <label for="email" class="form-label">Email</label>
                    <div class="input-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                            <polyline points="22,6 12,13 2,6"/>
                        </svg>
                    </div>
                    @error('email')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-input"
                        placeholder=" "
                        autocomplete="current-password"
                        required
                    >
                    <label for="password" class="form-label">Kata Sandi</label>
                    <div class="input-icon">
                        <button type="button" class="toggle-password" onclick="togglePassword(this)" aria-label="Tampilkan/sembunyikan kata sandi">
                            <!-- Eye icon -->
                            <svg class="eye-open" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <!-- Eye-off icon -->
                            <svg class="eye-closed" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                                <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="error-msg">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-login" id="btnLogin">MASUK</button>
            </form>

            <div class="card-register">
                Lupa Kata Sandi? <a href="#">Hubungi Admin</a>
            </div>

        </div>
        <!-- end card -->

    </div>
</div>

<script>
    // Toggle show/hide password
    function togglePassword(btn) {
        const input = document.getElementById('password');
        const eyeOpen   = btn.querySelector('.eye-open');
        const eyeClosed = btn.querySelector('.eye-closed');

        if (input.type === 'password') {
            input.type = 'text';
            eyeOpen.style.display   = 'none';
            eyeClosed.style.display = 'block';
        } else {
            input.type = 'password';
            eyeOpen.style.display   = 'block';
            eyeClosed.style.display = 'none';
        }
    }

    // Ripple effect on login button
    document.getElementById('btnLogin').addEventListener('click', function(e) {
        const btn    = this;
        const circle = document.createElement('span');
        const rect   = btn.getBoundingClientRect();
        const size   = Math.max(rect.width, rect.height);
        const x      = e.clientX - rect.left - size / 2;
        const y      = e.clientY - rect.top  - size / 2;

        circle.classList.add('ripple');
        circle.style.cssText = `width:${size}px;height:${size}px;left:${x}px;top:${y}px`;
        btn.appendChild(circle);
        setTimeout(() => circle.remove(), 600);
    });
</script>

</body>
</html>
