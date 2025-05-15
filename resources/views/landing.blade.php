<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pondok Pesantren Miftahul Ulum Jember</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS CSS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
            color: #333;
        }
        
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('https://images.unsplash.com/photo-1566669437688-88f436a61e4f?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 120px 0;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
        }
        
        .section-title {
            position: relative;
            margin-bottom: 50px;
            color: #2c3e50;
        }
        
        .section-title:after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: #3498db;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .program-card {
            border-top: 4px solid #3498db;
        }
        
        .program-card .card-icon {
            font-size: 2.5rem;
            color: #3498db;
            margin-bottom: 15px;
        }
        
        .testimonial-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            position: relative;
        }
        
        .testimonial-card:before {
            content: '\201C';
            font-size: 4rem;
            color: #3498db;
            opacity: 0.2;
            position: absolute;
            top: 10px;
            left: 10px;
        }
        
        .testimonial-card .blockquote-footer {
            font-size: 0.9rem;
            color: #6c757d;
        }
        
        .footer {
            background: #2c3e50;
            padding: 30px 0;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin: 0 10px;
            transition: color 0.3s;
        }
        
        .social-icons a:hover {
            color: #3498db;
        }
        
        .nav-pills .nav-link.active {
            background-color: #3498db;
        }
        
        .btn-primary {
            background-color: #3498db;
            border-color: #3498db;
        }
        
        .btn-outline-primary {
            color: #3498db;
            border-color: #3498db;
        }
        
        .btn-outline-primary:hover {
            background-color: #3498db;
            color: white;
        }
        
        .contact-info i {
            color: #3498db;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-mosque me-2"></i>Miftahul Ulum
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#beranda">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#profil">Ptofil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#program">Program</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#galeri">Galeri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#testimoni">Testimoni</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#pendaftaran">Pendaftaran</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section text-center" id="beranda">
        <div class="container" data-aos="fade-up">
            <h1 class="display-3 fw-bold mb-4">Pondok Pesantren Miftahul Ulum</h1>
            <p class="lead fs-4 mb-5">Menjadi Generasi Qurani Berakhlakul Karimah</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="#pendaftaran" class="btn btn-primary btn-lg px-4">Daftar Sekarang</a>
                <a href="#program" class="btn btn-outline-light btn-lg px-4">Program Kami</a>
            </div>
        </div>
    </section>

    <!-- Profil Section -->
    <section class="py-5" id="profil">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up">Profil Pondok</h2>
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4" data-aos="fade-right">
                    <img src="https://images.unsplash.com/photo-1585036156171-384164a8c675?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                         alt="Pondok Pesantren" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <h3 class="mb-4">Sejarah Singkat</h3>
                    <p>Pondok Pesantren Miftahul Ulum Jember berdiri sejak tahun 1980 dengan visi membentuk generasi berilmu dan berakhlak mulia. Didirikan oleh KH. Ahmad Shodiq, pondok ini telah berkembang menjadi salah satu lembaga pendidikan Islam terkemuka di Jawa Timur.</p>
                    <p>Dengan luas area 5 hektar, kami memiliki fasilitas lengkap untuk menunjang kegiatan belajar mengajar, termasuk asrama, masjid, perpustakaan, laboratorium, dan area olahraga.</p>
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users fa-2x me-3 text-primary"></i>
                                <div>
                                    <h5 class="mb-0">500+</h5>
                                    <small>Santri Aktif</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-graduate fa-2x me-3 text-primary"></i>
                                <div>
                                    <h5 class="mb-0">1000+</h5>
                                    <small>Alumni</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-book-quran fa-2x me-3 text-primary"></i>
                                <div>
                                    <h5 class="mb-0">50+</h5>
                                    <small>Hafidz Qur'an</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-chalkboard-teacher fa-2x me-3 text-primary"></i>
                                <div>
                                    <h5 class="mb-0">30+</h5>
                                    <small>Pengajar</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Section -->
    <section class="py-5 bg-light" id="program">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up">Program Pendidikan</h2>
            <p class="text-center mb-5" data-aos="fade-up">Kami menyediakan berbagai program unggulan untuk membentuk santri yang berilmu dan berakhlak mulia</p>
            
            <div class="row text-center">
                <div class="col-md-4 mb-4" data-aos="zoom-in">
                    <div class="card program-card h-100 py-4">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="fas fa-book-quran"></i>
                            </div>
                            <h5 class="card-title">Madrasah Diniyah</h5>
                            <p class="card-text">Pendidikan agama mendalam meliputi Fiqh, Aqidah, Akhlak, Bahasa Arab, dan Ilmu Al-Qur'an dengan metode pembelajaran klasikal dan sorogan.</p>
                            <a href="#" class="btn btn-outline-primary mt-3">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card program-card h-100 py-4">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="fas fa-quran"></i>
                            </div>
                            <h5 class="card-title">Tahfidz Qur'an</h5>
                            <p class="card-text">Program hafalan Al-Qur'an dengan target mutqin, dibimbing oleh hafidz berpengalaman. Tersedia program 1 juz, 5 juz, 10 juz, hingga 30 juz.</p>
                            <a href="#" class="btn btn-outline-primary mt-3">Selengkapnya</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card program-card h-100 py-4">
                        <div class="card-body">
                            <div class="card-icon">
                                <i class="fas fa-home"></i>
                            </div>
                            <h5 class="card-title">Keasramaan</h5>
                            <p class="card-text">Pembinaan akhlak dan kedisiplinan santri sehari-hari melalui kegiatan harian, mingguan, dan bulanan di lingkungan asrama yang nyaman.</p>
                            <a href="#" class="btn btn-outline-primary mt-3">Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="#" class="btn btn-primary btn-lg">Lihat Semua Program</a>
            </div>
        </div>
    </section>

    <!-- Galeri Section -->
    <section class="py-5" id="galeri">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up">Galeri Kegiatan</h2>
            <p class="text-center mb-5" data-aos="fade-up">Momen berharga dalam kegiatan sehari-hari di Pondok Pesantren Miftahul Ulum</p>
            
            <div class="row g-3">
                <div class="col-md-4" data-aos="fade-up">
                    <div class="card border-0">
                        <img src="https://images.unsplash.com/photo-1585032226651-759b368d7246?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                             class="card-img-top" alt="Kegiatan Belajar">
                        <div class="card-body">
                            <h5 class="card-title">Kegiatan Belajar</h5>
                            <p class="card-text">Santri sedang mengikuti pelajaran di kelas</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="card border-0">
                        <img src="https://images.unsplash.com/photo-1566669437684-8b436a61e4f0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                             class="card-img-top" alt="Hafalan Qur'an">
                        <div class="card-body">
                            <h5 class="card-title">Hafalan Qur'an</h5>
                            <p class="card-text">Sesi setoran hafalan kepada ustadz</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="card border-0">
                        <img src="https://images.unsplash.com/photo-1519817650390-64a93db51149?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80" 
                             class="card-img-top" alt="Kegiatan Olahraga">
                        <div class="card-body">
                            <h5 class="card-title">Kegiatan Olahraga</h5>
                            <p class="card-text">Santri bermain futsal di lapangan pesantren</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-5" data-aos="fade-up">
                <a href="#" class="btn btn-outline-primary">Lihat Galeri Lengkap</a>
            </div>
        </div>
    </section>

    <!-- Testimoni Section -->
    <section class="py-5 bg-light" id="testimoni">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up">Apa Kata Mereka?</h2>
            <p class="text-center mb-5" data-aos="fade-up">Testimoni dari wali santri dan alumni tentang pengalaman mereka</p>
            
            <div class="row">
                <div class="col-md-6 mb-4" data-aos="fade-right">
                    <div class="testimonial-card">
                        <blockquote class="blockquote">
                            <p>"Anak saya berubah lebih disiplin dan rajin setelah mondok di sini. Tidak hanya ilmu agamanya yang bertambah, tapi juga akhlaknya semakin baik. Saya sangat merekomendasikan pondok ini untuk pendidikan anak."</p>
                            <footer class="blockquote-footer mt-3">Ibu Aminah, <cite>Wali Santri</cite></footer>
                        </blockquote>
                    </div>
                </div>
                <div class="col-md-6 mb-4" data-aos="fade-left">
                    <div class="testimonial-card">
                        <blockquote class="blockquote">
                            <p>"Lingkungan pesantren yang nyaman untuk belajar dan menghafal Qur'an. Guru-gurunya sangat perhatian dan metode pembelajarannya mudah dipahami. Saya bangga menjadi alumni Miftahul Ulum."</p>
                            <footer class="blockquote-footer mt-3">Ahmad, <cite>Alumni 2020</cite></footer>
                        </blockquote>
                    </div>
                </div>
                <div class="col-md-6 mb-4" data-aos="fade-right" data-aos-delay="100">
                    <div class="testimonial-card">
                        <blockquote class="blockquote">
                            <p>"Program tahfidz di sini sangat bagus. Dalam 2 tahun, anak saya sudah hafal 10 juz dengan tajwid yang benar. Pengasuh dan ustadz sangat kompeten dalam membimbing santri."</p>
                            <footer class="blockquote-footer mt-3">Bapak Budi, <cite>Wali Santri</cite></footer>
                        </blockquote>
                    </div>
                </div>
                <div class="col-md-6 mb-4" data-aos="fade-left" data-aos-delay="100">
                    <div class="testimonial-card">
                        <blockquote class="blockquote">
                            <p>"Selain ilmu agama, saya juga belajar kemandirian dan tanggung jawab selama mondok di Miftahul Ulum. Bekal ini sangat membantu saya dalam melanjutkan studi ke perguruan tinggi."</p>
                            <footer class="blockquote-footer mt-3">Siti, <cite>Alumni 2019</cite></footer>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pendaftaran Section -->
    <section class="py-5 bg-primary text-white" id="pendaftaran">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mb-4 mb-lg-0" data-aos="fade-right">
                    <h2 class="display-5 fw-bold mb-3">Pendaftaran Santri Baru</h2>
                    <p class="lead mb-4">Tahun Ajaran 2025/2026 telah dibuka! Daftarkan putra/putri Anda sekarang untuk mendapatkan pendidikan terbaik yang mengintegrasikan ilmu agama dan umum.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="https://wa.me/6281234567890" class="btn btn-light btn-lg px-4" target="_blank" data-aos="zoom-in">
                            <i class="fab fa-whatsapp me-2"></i>Daftar via WhatsApp
                        </a>
                        <a href="#" class="btn btn-outline-light btn-lg px-4" data-aos="zoom-in" data-aos-delay="100">
                            <i class="fas fa-file-alt me-2"></i>Formulir Online
                        </a>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-left">
                    <div class="card text-dark">
                        <div class="card-body p-4">
                            <h4 class="card-title mb-4">Informasi Pendaftaran</h4>
                            <ul class="list-unstyled">
                                <li class="mb-3"><i class="fas fa-calendar-alt text-primary me-2"></i> <strong>Periode:</strong> 1 Jan - 30 Juni 2025</li>
                                <li class="mb-3"><i class="fas fa-address-card text-primary me-2"></i> <strong>Syarat:</strong> Usia 12-18 tahun</li>
                                <li class="mb-3"><i class="fas fa-money-bill-wave text-primary me-2"></i> <strong>Biaya:</strong> Rp 2.500.000/semester</li>
                                <li class="mb-3"><i class="fas fa-file text-primary me-2"></i> <strong>Dokumen:</strong> FC Akte, KK, Raport</li>
                                <li><i class="fas fa-clock text-primary me-2"></i> <strong>Test Masuk:</strong> 15 Juli 2025</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section class="py-5" id="kontak">
        <div class="container">
            <h2 class="text-center section-title" data-aos="fade-up">Hubungi Kami</h2>
            <div class="row mt-5">
                <div class="col-md-4 mb-4" data-aos="fade-up">
                    <div class="text-center p-4">
                        <i class="fas fa-map-marker-alt fa-3x mb-3 text-primary"></i>
                        <h4>Alamat</h4>
                        <p>Jl. Pesantren No. 123, Kec. Sumbersari, Kab. Jember, Jawa Timur 68121</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-center p-4">
                        <i class="fas fa-phone-alt fa-3x mb-3 text-primary"></i>
                        <h4>Telepon</h4>
                        <p>(0331) 1234567</p>
                        <p>081234567890 (Ustadz Ahmad)</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-center p-4">
                        <i class="fas fa-envelope fa-3x mb-3 text-primary"></i>
                        <h4>Email</h4>
                        <p>info@miftahululum-jember.sch.id</p>
                        <p>pendaftaran@miftahululum-jember.sch.id</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="text-white mb-4">
                        <i class="fas fa-mosque me-2"></i>Miftahul Ulum
                    </h4>
                    <p>Pondok Pesantren Miftahul Ulum Jember - Membentuk generasi Qur'ani yang berakhlak mulia, berilmu, dan bermanfaat bagi umat.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-youtube"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-white mb-4">Menu</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#beranda" class="text-white-50">Beranda</a></li>
                        <li class="mb-2"><a href="#profil" class="text-white-50">Profil</a></li>
                        <li class="mb-2"><a href="#program" class="text-white-50">Program</a></li>
                        <li class="mb-2"><a href="#galeri" class="text-white-50">Galeri</a></li>
                        <li><a href="#pendaftaran" class="text-white-50">Pendaftaran</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-white mb-4">Program Unggulan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-white-50">Tahfidz Qur'an</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Madrasah Diniyah</a></li>
                        <li class="mb-2"><a href="#" class="text-white-50">Bahasa Arab</a></li>
                        <li><a href="#" class="text-white-50">Pendidikan Karakter</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h5 class="text-white mb-4">Kontak</h5>
                    <ul class="list-unstyled text-white-50 contact-info">
                        <li class="mb-3"><i class="fas fa-map-marker-alt"></i> Jl. Pesantren No. 123, Jember</li>
                        <li class="mb-3"><i class="fas fa-phone-alt"></i> (0331) 1234567</li>
                        <li class="mb-3"><i class="fas fa-envelope"></i> info@miftahululum-jember.sch.id</li>
                        <li><i class="fas fa-clock"></i> Senin-Jumat: 08.00-16.00</li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,0.1);">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="mb-0">&copy; 2025 Pondok Pesantren Miftahul Ulum Jember. All rights reserved.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0">Developed with <i class="fas fa-heart text-danger"></i> by IT Team</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to Top Button -->
    <a href="#" class="btn btn-primary btn-lg back-to-top" id="backToTop">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true
        });
        
        // Back to top button
        window.addEventListener('scroll', function() {
            var backToTop = document.getElementById('backToTop');
            if (window.pageYOffset > 300) {
                backToTop.style.display = 'block';
            } else {
                backToTop.style.display = 'none';
            }
        });
        
        document.getElementById('backToTop').addEventListener('click', function(e) {
            e.preventDefault();
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
        
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            var navbar = document.querySelector('.navbar');
            if (window.pageYOffset > 100) {
                navbar.classList.add('navbar-scrolled', 'shadow');
            } else {
                navbar.classList.remove('navbar-scrolled', 'shadow');
            }
        });
    </script>
    <style>
        .navbar-scrolled {
            background-color: rgba(44, 62, 80, 0.9) !important;
            transition: background-color 0.3s ease;
        }
        
        .back-to-top {
            position: fixed;
            bottom: 20px;
            right: 20px;
            display: none;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
            z-index: 99;
        }
    </style>
</body>
</html>