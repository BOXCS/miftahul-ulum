<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Pesantren Miftahul Ulum - Teknologi Digital untuk Pendidikan Islami</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E8B57;
            --secondary-color: #FFD700;
            --accent-color: #1e6b44;
            --text-dark: #2c3e50;
            --bg-light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: var(--text-dark);
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(46, 139, 87, 0.3);
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }

        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--accent-color) 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><polygon fill="rgba(255,255,255,0.05)" points="0,0 1000,300 1000,1000 0,700"/></svg>');
            background-size: cover;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .btn-primary {
            background: linear-gradient(45deg, var(--secondary-color), #ffa500);
            border: none;
            padding: 15px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(255, 215, 0, 0.4);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 215, 0, 0.6);
            background: linear-gradient(45deg, #ffa500, var(--secondary-color));
        }

        .btn-outline-light {
            border: 2px solid white;
            padding: 15px 30px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-outline-light:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .app-showcase {
            background: var(--bg-light);
            position: relative;
        }

        .phone-mockup {
            max-width: 300px;
            margin: 0 auto;
            position: relative;
        }

        .phone-frame {
            background: #333;
            border-radius: 30px;
            padding: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .phone-screen {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 20px;
            aspect-ratio: 9/16;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .feature-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .download-section {
            background: linear-gradient(135deg, var(--accent-color), var(--primary-color));
            position: relative;
        }

        .download-btn {
            background: rgba(255,255,255,0.2);
            border: 2px solid white;
            color: white;
            padding: 15px 25px;
            border-radius: 15px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .download-btn:hover {
            background: white;
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(255,255,255,0.3);
        }

        .stats-card {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .stats-number {
            font-size: 3rem;
            font-weight: 700;
            color: var(--secondary-color);
        }

        .footer {
            background: var(--text-dark);
            color: white;
        }

        .social-links a {
            width: 50px;
            height: 50px;
            background: var(--primary-color);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            margin: 0 10px;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--secondary-color);
            color: var(--text-dark);
            transform: translateY(-3px);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }

        .floating {
            animation: float 3s ease-in-out infinite;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2.5rem;
            }
            
            .stats-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-mosque me-2"></i>
                Miftahul Ulum
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tentang">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#fitur">Fitur App</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#download">Download</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#kontak">Kontak</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="hero-section d-flex align-items-center">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content text-white">
                        <h1 class="display-4 fw-bold mb-4">
                            Pondok Pesantren<br>
                            <span style="color: var(--secondary-color);">Miftahul Ulum</span>
                        </h1>
                        <p class="lead mb-4">
                            Memadukan tradisi Islam dengan teknologi digital. Pantau perkembangan santri Anda secara real-time melalui aplikasi mobile terintegrasi.
                        </p>
                        <div class="d-flex flex-wrap gap-3">
                            <a href="#download" class="btn btn-primary btn-lg">
                                <i class="fas fa-download me-2"></i>Download Aplikasi
                            </a>
                            <a href="#tentang" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-info-circle me-2"></i>Pelajari Lebih Lanjut
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="phone-mockup floating">
                        <div class="phone-frame">
                            <div class="phone-screen">
                                <div class="text-center">
                                    <i class="fas fa-mobile-alt fa-4x mb-3"></i>
                                    <h5>Aplikasi Monitoring</h5>
                                    <p>Pantau santri 24/7</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5" style="background: var(--primary-color);">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="stats-card">
                        <div class="stats-number">500+</div>
                        <p class="mb-0">Santri Aktif</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-card">
                        <div class="stats-number">25+</div>
                        <p class="mb-0">Tahun Berdiri</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-card">
                        <div class="stats-number">50+</div>
                        <p class="mb-0">Ustadz & Ustadzah</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stats-card">
                        <div class="stats-number">1000+</div>
                        <p class="mb-0">Alumni</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="display-5 fw-bold mb-4">Tentang Pondok Pesantren Miftahul Ulum</h2>
                    <p class="lead mb-4">
                        Pondok Pesantren Miftahul Ulum adalah lembaga pendidikan Islam yang telah berdiri selama lebih dari 25 tahun, menggabungkan nilai-nilai tradisional pesantren dengan teknologi modern.
                    </p>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3 fs-4"></i>
                                <span>Pendidikan Al-Qur'an & Hadits</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3 fs-4"></i>
                                <span>Pembelajaran Modern</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3 fs-4"></i>
                                <span>Akhlakul Karimah</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-3 fs-4"></i>
                                <span>Teknologi Terintegrasi</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="position-relative">
                        <div class="bg-primary rounded-4 p-4" style="transform: rotate(3deg);">
                            <div class="bg-white rounded-4 p-5 text-center" style="transform: rotate(-3deg);">
                                <i class="fas fa-mosque fa-4x text-primary mb-3"></i>
                                <h4>Visi Kami</h4>
                                <p class="mb-0">Mencetak generasi Qurani yang berakhlak mulia dan menguasai teknologi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="app-showcase py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Fitur Aplikasi Monitoring Santri</h2>
                <p class="lead">Aplikasi mobile yang memungkinkan orang tua memantau perkembangan putra-putri mereka di pesantren secara real-time</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <h4 class="mb-3">Monitoring Kehadiran</h4>
                        <p>Pantau kehadiran santri dalam kegiatan harian seperti sholat berjamaah, mengaji, dan pembelajaran.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h4 class="mb-3">Laporan Progress</h4>
                        <p>Dapatkan laporan berkala tentang perkembangan akademik dan spiritual santri secara detail.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <h4 class="mb-3">Komunikasi Langsung</h4>
                        <p>Berkomunikasi langsung dengan ustadz/ustadzah untuk mengetahui perkembangan santri.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h4 class="mb-3">Jadwal Kegiatan</h4>
                        <p>Akses jadwal harian, mingguan, dan bulanan kegiatan pesantren secara lengkap.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <h4 class="mb-3">Monitoring Kesehatan</h4>
                        <p>Pantau kondisi kesehatan santri dan dapatkan notifikasi jika ada hal yang perlu diperhatikan.</p>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card text-center">
                        <div class="feature-icon">
                            <i class="fas fa-wallet"></i>
                        </div>
                        <h4 class="mb-3">Keuangan Digital</h4>
                        <p>Kelola uang saku santri secara digital dengan sistem yang aman dan transparan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Download Section -->
    <section id="download" class="download-section py-5">
        <div class="container text-center text-white">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">Download Aplikasi Sekarang</h2>
                    <p class="lead mb-5">
                        Mulai pantau perkembangan santri Anda hari ini. Aplikasi tersedia untuk Android dan iOS.
                    </p>
                    
                    <div class="row justify-content-center g-3">
                        <div class="col-auto">
                            <a href="#" class="download-btn">
                                <i class="fab fa-google-play fa-2x"></i>
                                <div class="text-start">
                                    <small>Download dari</small><br>
                                    <strong>Google Play</strong>
                                </div>
                            </a>
                        </div>
                        <div class="col-auto">
                            <a href="#" class="download-btn">
                                <i class="fab fa-apple fa-2x"></i>
                                <div class="text-start">
                                    <small>Download dari</small><br>
                                    <strong>App Store</strong>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <p class="mb-2">Atau scan QR Code untuk download:</p>
                        <div class="bg-white d-inline-block p-3 rounded">
                            <i class="fas fa-qrcode fa-3x text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Apa Kata Mereka</h2>
                <p class="lead">Pengalaman nyata dari orang tua dan alumni pesantren</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="feature-card h-100">
                        <div class="text-center mb-4">
                            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 100 100'><circle cx='50' cy='50' r='50' fill='%23e9ecef'/><text x='50' y='55' text-anchor='middle' font-size='40' fill='%236c757d'>👤</text></svg>" alt="Avatar" class="rounded-circle mb-3" width="80" height="80">
                            <h5>Bu Siti Aminah</h5>
                            <small class="text-muted">Wali Santri</small>
                        </div>
                        <p class="text-center mb-4">"Aplikasi monitoring ini sangat membantu saya memantau perkembangan anak. Saya bisa tahu kegiatan harian dan progress belajarnya secara real-time."</p>
                        <div class="text-center">
                            <div class="text-warning mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <small class="text-muted">Rating: 5/5</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="feature-card h-100">
                        <div class="text-center mb-4">
                            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 100 100'><circle cx='50' cy='50' r='50' fill='%23e9ecef'/><text x='50' y='55' text-anchor='middle' font-size='40' fill='%236c757d'>👤</text></svg>" alt="Avatar" class="rounded-circle mb-3" width="80" height="80">
                            <h5>Ahmad Fauzi, S.Pd</h5>
                            <small class="text-muted">Alumni 2020</small>
                        </div>
                        <p class="text-center mb-4">"Alhamdulillah, pendidikan di Miftahul Ulum membentuk karakter saya. Sekarang saya menjadi guru dan menerapkan nilai-nilai yang dipelajari di pesantren."</p>
                        <div class="text-center">
                            <div class="text-warning mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <small class="text-muted">Alumni Berprestasi</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="feature-card h-100">
                        <div class="text-center mb-4">
                            <img src="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 100 100'><circle cx='50' cy='50' r='50' fill='%23e9ecef'/><text x='50' y='55' text-anchor='middle' font-size='40' fill='%236c757d'>👤</text></svg>" alt="Avatar" class="rounded-circle mb-3" width="80" height="80">
                            <h5>Pak Budi Santoso</h5>
                            <small class="text-muted">Wali Santri</small>
                        </div>
                        <p class="text-center mb-4">"Teknologi yang diterapkan pesantren sangat modern. Komunikasi dengan ustadz mudah, laporan perkembangan anak juga detail dan transparan."</p>
                        <div class="text-center">
                            <div class="text-warning mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <small class="text-muted">Rating: 5/5</small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="border rounded p-3">
                            <div class="text-warning fs-1">4.9</div>
                            <div class="text-warning mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <small>Rating Play Store</small>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="border rounded p-3">
                            <div class="text-warning fs-1">4.8</div>
                            <div class="text-warning mb-2">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <small>Rating App Store</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section id="program" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Program & Kurikulum Unggulan</h2>
                <p class="lead">Program pendidikan terpadu yang menggabungkan nilai-nilai Islam dengan pembelajaran modern</p>
            </div>
            
            <div class="row g-4 mb-5">
                <div class="col-lg-6">
                    <div class="feature-card h-100">
                        <div class="row g-0">
                            <div class="col-4">
                                <div class="feature-icon mx-0">
                                    <i class="fas fa-quran-quran"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <h4>Program Tahfidz Al-Qur'an</h4>
                                <p class="mb-3">Target hafalan 30 juz dengan metode pembelajaran yang efektif dan menyenangkan.</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Metode Tikrar & Muraja'ah</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Bimbingan Ustadz Expert</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Progress Tracking Digital</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="feature-card h-100">
                        <div class="row g-0">
                            <div class="col-4">
                                <div class="feature-icon mx-0">
                                    <i class="fas fa-graduation-cap"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <h4>Pendidikan Formal</h4>
                                <p class="mb-3">Kurikulum nasional terintegrasi dengan pendidikan agama Islam yang komprehensif.</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>SMP & SMA Terakreditasi A</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Kurikulum Merdeka</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Persiapan PTN/PTS</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="feature-card h-100">
                        <div class="row g-0">
                            <div class="col-4">
                                <div class="feature-icon mx-0">
                                    <i class="fas fa-language"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <h4>Bahasa Arab & Inggris</h4>
                                <p class="mb-3">Program intensif bahasa Arab dan Inggris untuk komunikasi global.</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Conversation Daily</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Grammar & Vocabulary</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Sertifikasi Internasional</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="feature-card h-100">
                        <div class="row g-0">
                            <div class="col-4">
                                <div class="feature-icon mx-0">
                                    <i class="fas fa-book-open"></i>
                                </div>
                            </div>
                            <div class="col-8">
                                <h4>Kitab Kuning</h4>
                                <p class="mb-3">Pembelajaran kitab-kitab klasik dengan metode sorogan dan bandongan.</p>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Fiqh, Aqidah, Akhlaq</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Tafsir & Hadits</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Nahwu & Shorof</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Daily Schedule -->
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="feature-card">
                        <h4 class="text-center mb-4">Jadwal Harian Santri</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <small class="fw-bold">04:30</small>
                                    </div>
                                    <div>
                                        <strong>Tahajjud & Subuh</strong><br>
                                        <small class="text-muted">Sholat berjamaah</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <small class="fw-bold">05:30</small>
                                    </div>
                                    <div>
                                        <strong>Mengaji Al-Qur'an</strong><br>
                                        <small class="text-muted">Tahfidz & Tilawah</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <small class="fw-bold">07:00</small>
                                    </div>
                                    <div>
                                        <strong>Sarapan & Persiapan</strong><br>
                                        <small class="text-muted">Makan bersama</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <small class="fw-bold">08:00</small>
                                    </div>
                                    <div>
                                        <strong>Pembelajaran Formal</strong><br>
                                        <small class="text-muted">Sekolah SMP/SMA</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <small class="fw-bold">14:00</small>
                                    </div>
                                    <div>
                                        <strong>Dzuhur & Istirahat</strong><br>
                                        <small class="text-muted">Sholat & makan siang</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <small class="fw-bold">15:30</small>
                                    </div>
                                    <div>
                                        <strong>Ashar & Kajian Kitab</strong><br>
                                        <small class="text-muted">Kitab kuning</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <small class="fw-bold">18:00</small>
                                    </div>
                                    <div>
                                        <strong>Maghrib & Mengaji</strong><br>
                                        <small class="text-muted">Sholat & Al-Qur'an</small>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        <small class="fw-bold">20:00</small>
                                    </div>
                                    <div>
                                        <strong>Isya & Belajar Mandiri</strong><br>
                                        <small class="text-muted">Study time</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Frequently Asked Questions</h2>
                <p class="lead">Pertanyaan yang sering diajukan tentang pesantren dan aplikasi monitoring</p>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion" id="faqAccordion">
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h3 class="accordion-header">
                                <button class="accordion-button fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    Bagaimana cara mendaftar santri baru?
                                </button>
                            </h3>
                            <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Pendaftaran santri baru dapat dilakukan secara online melalui website kami atau datang langsung ke pesantren. Syarat pendaftaran meliputi: fotokopi KTP orang tua, fotokopi KK, fotokopi ijazah terakhir, pas foto 3x4 sebanyak 6 lembar, dan surat keterangan sehat dari dokter.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    Berapa biaya pendidikan di Pondok Pesantren Miftahul Ulum?
                                </button>
                            </h3>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Biaya pendidikan terdiri dari uang pangkal Rp 5.000.000,- dan SPP bulanan Rp 800.000,-. Biaya sudah termasuk makan 3x sehari, asrama, buku pelajaran, dan akses aplikasi monitoring. Tersedia program beasiswa untuk santri berprestasi dan kurang mampu.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    Apakah aplikasi monitoring berbayar?
                                </button>
                            </h3>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Tidak, aplikasi monitoring santri gratis untuk semua orang tua santri yang terdaftar. Biaya pengembangan dan maintenance aplikasi sudah termasuk dalam SPP bulanan. Orang tua akan mendapatkan akun login setelah santri resmi terdaftar.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    Kapan santri bisa pulang ke rumah?
                                </button>
                            </h3>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Santri dapat pulang setiap hari Sabtu sore dan kembali Minggu malam. Untuk liburan panjang, santri pulang saat liburan semester dan bulan Ramadhan. Jadwal kepulangan akan diinformasikan melalui aplikasi dan dapat berubah sesuai kebijakan pesantren.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    Bagaimana sistem keamanan dan kesehatan santri?
                                </button>
                            </h3>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Pesantren memiliki sistem keamanan 24 jam dengan CCTV dan satpam. Untuk kesehatan, tersedia klinik dengan dokter jaga dan perawat. Setiap santri memiliki kartu kesehatan dan asuransi. Kondisi kesehatan santri dipantau melalui aplikasi dan dilaporkan kepada orang tua.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h3 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse" data-bs-target="#faq6">
                                    Fitur apa saja yang tersedia di aplikasi monitoring?
                                </button>
                            </h3>
                            <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Aplikasi memiliki fitur lengkap: monitoring kehadiran sholat dan pembelajaran, laporan progress hafalan Al-Qur'an, komunikasi dengan ustadz, jadwal kegiatan, monitoring kesehatan, e-wallet untuk uang saku, galeri foto kegiatan, dan notifikasi real-time untuk setiap aktivitas santri.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- News & Blog Section -->
    <section id="berita" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Berita & Artikel Terbaru</h2>
                <p class="lead">Update terkini dari Pondok Pesantren Miftahul Ulum</p>
            </div>
            
            <div class="row g-4">
                <!-- Featured Article -->
                <div class="col-lg-8">
                    <div class="feature-card h-100">
                        <div class="row g-0">
                            <div class="col-md-6">
                                <div class="bg-primary rounded h-100 d-flex align-items-center justify-content-center text-white">
                                    <i class="fas fa-newspaper fa-4x"></i>
                                </div>
                            </div>
                            <div class="col-md-6 p-4">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-primary me-2">Featured</span>
                                    <small class="text-muted">15 Juni 2025</small>
                                </div>
                                <h4 class="mb-3">Peluncuran Aplikasi Monitoring Santri Versi 2.0</h4>
                                <p class="mb-3">Pondok Pesantren Miftahul Ulum dengan bangga mengumumkan peluncuran aplikasi monitoring santri versi 2.0 dengan fitur-fitur terbaru yang lebih canggih dan user-friendly.</p>
                                <a href="#" class="btn btn-outline-primary">Baca Selengkapnya</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- News List -->
                <div class="col-lg-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="feature-card">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-success me-2">Prestasi</span>
                                    <small class="text-muted">12 Juni 2025</small>
                                </div>
                                <h6 class="mb-2">Santri Miftahul Ulum Juara 1 Lomba Tahfidz Tingkat Provinsi</h6>
                                <p class="small mb-2">Ahmad Ridwan berhasil meraih juara 1 dalam kompetisi tahfidz Al-Qur'an 30 juz tingkat provinsi.</p>
                                <a href="#" class="small text-primary text-decoration-none">Baca selengkapnya →</a>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="feature-card">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-info me-2">Event</span>
                                    <small class="text-muted">10 Juni 2025</small>
                                </div>
                                <h6 class="mb-2">Open House Pondok Pesantren 2025</h6>
                                <p class="small mb-2">Kunjungi pesantren kami dalam acara open house yang akan diselenggarakan pada tanggal 20-22 Juni 2025.</p>
                                <a href="#" class="small text-primary text-decoration-none">Baca selengkapnya →</a>
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <div class="feature-card">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-warning me-2">Tips</span>
                                    <small class="text-muted">8 Juni 2025</small>
                                </div>
                                <h6 class="mb-2">5 Tips Mendampingi Anak di Pondok Pesantren</h6>
                                <p class="small mb-2">Panduan untuk orang tua dalam mendampingi dan mendukung anak selama menimba ilmu di pesantren.</p>
                                <a href="#" class="small text-primary text-decoration-none">Baca selengkapnya →</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Blog Categories -->
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="text-center mb-4">
                        <h4>Kategori Artikel</h4>
                    </div>
                    <div class="row g-3 justify-content-center">
                        <div class="col-md-2 col-4">
                            <a href="#" class="text-decoration-none">
                                <div class="text-center p-3 rounded border hover-shadow">
                                    <i class="fas fa-graduation-cap fa-2x text-primary mb-2"></i>
                                    <div class="small">Pendidikan</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-4">
                            <a href="#" class="text-decoration-none">
                                <div class="text-center p-3 rounded border hover-shadow">
                                    <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                                    <div class="small">Prestasi</div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-2 col-4">
                            <a href="#" class="text-decoration-none">
                                <div class="text-center p-3 rounded border hover-shadow">
                                    <i class="fas fa-calendar-alt fa-2x text-success mb-2"></i>
                                    <div class="small">Event</div>
                                </div>
                            </a>
                        </div>
            
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h5>Alamat</h5>
                        <p>Jl. Pesantren No. 123<br>Kota Santri, Indonesia</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-phone"></i>
                        </div>
                        <h5>Telepon</h5>
                        <p>+62 21 1234-5678<br>+62 812-3456-7890</p>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="text-center">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <h5>Email</h5>
                        <p>info@miftahuluulum.id<br>admin@miftahululum.id</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="mb-3">
                        <i class="fas fa-mosque me-2"></i>
                        Pondok Pesantren Miftahul Ulum
                    </h5>
                    <p>Mendidik generasi Qurani dengan teknologi modern untuk masa depan yang lebih baik.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h6 class="mb-3">Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="#beranda" class="text-light text-decoration-none">Beranda</a></li>
                        <li><a href="#tentang" class="text-light text-decoration-none">Tentang</a></li>
                        <li><a href="#fitur" class="text-light text-decoration-none">Fitur</a></li>
                        <li><a href="#download" class="text-light text-decoration-none">Download</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h6 class="mb-3">Program</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light text-decoration-none">Tahfidz Al-Qur'an</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Pendidikan Formal</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Kursus Bahasa Arab</a></li>
                        <li><a href="#" class="text-light text-decoration-none">Program Kitab Kuning</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3">
                    <h6 class="mb-3">Newsletter</h6>
                    <p>Dapatkan update terbaru dari pesantren</p>
                    <div class="input-group">
                        <input type="email" class="form-control" placeholder="Email Anda">
                        <button class="btn btn-primary" type="button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; 2025 Pondok Pesantren Miftahul Ulum. All rights reserved. | Dikembangkan dengan ❤️ menggunakan Laravel & Bootstrap</p>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script>
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'linear-gradient(135deg, rgba(46, 139, 87, 0.95), rgba(30, 107, 68, 0.95))';
            } else {
                navbar.style.background = 'linear-gradient(135deg, var(--primary-color), var(--accent-color))';
            }
        });

        // Add animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        // Stats counter animation
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                const value = Math.floor(progress * (end - start) + start);
                element.textContent = value + '+';
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // Trigger counter animation when stats section is visible
        const statsObserver = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const numbers = entry.target.querySelectorAll('.stats-number');
                    numbers.forEach(number => {
                        const finalValue = parseInt(number.textContent);
                        number.textContent = '0+';
                        animateValue(number, 0, finalValue, 2000);
                    });
                    statsObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });

        const statsSection = document.querySelector('.py-5[style*="background: var(--primary-color)"]');
        if (statsSection) {
            statsObserver.observe(statsSection);
        }
    </script>
</body>
</html>