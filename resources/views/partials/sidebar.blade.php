<!-- Sidebar -->
<aside id="sidebar" class="shadow-sm p-3 sidebar d-md-block"
    style="width: 300px; height: 100vh; position: fixed; background-color: #EFF0F5; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); overflow-y: auto; transform: translateX(-100%); z-index: 999;">

    <!-- Logo with animation -->
    <div class="mb-4 text-center logo-container">
        <div class="logo-inner" style="display: inline-block; transition: all 0.3s ease;">
            <h5 class="fw-bold" style="color: #449098; position: relative; display: inline-block;">
                <span style="position: relative; z-index: 2;">Logo</span>
                <span class="logo-highlight"
                    style="position: absolute; bottom: 2px; left: 0; height: 8px; width: 100%; background-color: rgba(68, 144, 152, 0.3); z-index: 1; border-radius: 4px;"></span>
            </h5>
        </div>
    </div>

    <!-- Navigasi -->
    <nav class="nav flex-column position-relative px-2 gap-3">
        @php
            $routes = [
                'dashboard' => ['icon' => 'dashboard.png', 'label' => 'Dashboard'],
                'management.index' => ['icon' => 'dashboard.png', 'label' => 'Manajemen Data'],
                'attendance.index' => ['icon' => 'report.png', 'label' => 'Laporan Kehadiran'],
                'chat.index' => ['icon' => 'dashboard.png', 'label' => 'Chat'],
                'pengumuman.index' => ['icon' => 'dashboard.png', 'label' => 'Pengumuman/FAQ'],
                'perizinan.index' => ['icon' => 'dashboard.png', 'label' => 'Perizinan'],
                'profile.index' => ['icon' => 'dashboard.png', 'label' => 'Profil'],
            ];
        @endphp

        @foreach ($routes as $route => $data)
            @php
                try {
                    $url = route($route);
                } catch (Exception $e) {
                    $url = '#'; // fallback
                }
            @endphp

            <a href="{{ $url }}"
                class="d-flex align-items-center gap-3 py-3 px-3 position-relative sidebar-link {{ request()->routeIs($route) ? 'active' : '' }}"
                style="color: {{ request()->routeIs($route) ? '#449098' : '#3A3541' }};">
                <div class="icon-container" style="position: relative;">
                    <img src="{{ asset('image/' . $data['icon']) }}" alt="{{ $data['label'] }}"
                        style="width: 24px; height: 24px; transition: transform 0.3s ease; z-index: 2; position: relative;">
                    <span class="icon-bg"
                        style="position: absolute; width: 40px; height: 40px; border-radius: 50%; top: 50%; left: 50%; transform: translate(-50%, -50%) scale(0); background-color: rgba(68, 144, 152, 0.1); transition: transform 0.3s ease; z-index: 1;"></span>
                </div>
                <span class="nav-label" style="transition: all 0.3s ease 0.1s;">{{ $data['label'] }}</span>
                <span class="active-indicator"
                    style="position: absolute; right: 15px; width: 8px; height: 8px; background-color: #449098; border-radius: 50%; opacity: 0; transform: scale(0); transition: all 0.3s ease;"></span>
            </a>
        @endforeach
    </nav>

    <!-- Profil Admin with animation -->
    @php
        $user = Auth::user();
        // Ambil nama dari relasi staff, fallback ke 'Admin Dummy' jika staff tidak ada
        $name = $user && $user->staff ? $user->staff->nama : 'Admin Dummy';

        // Ambil initial dari nama staff jika ada, atau dari email user, atau default 'A'
        if ($user && $user->staff && $user->staff->nama) {
            $initial = strtoupper(substr($user->staff->nama, 0, 1));
        } elseif ($user && $user->email) {
            $initial = strtoupper(substr($user->email, 0, 1));
        } else {
            $initial = 'A';
        }
    @endphp

    <div class="position-absolute bottom-0 start-50 translate-middle-x w-100 text-center p-3 admin-profile"
        style="background-color: #449098; color: white; border-top-left-radius: 20px; border-top-right-radius: 20px; transition: all 0.4s ease;">
        <div class="avatar-container mb-2"
            style="width: 50px; height: 50px; margin: 0 auto; border-radius: 50%; background-color: white; display: flex; align-items: center; justify-content: center; overflow: hidden; transition: transform 0.3s ease;">
            <span style="color: #449098; font-size: 20px; font-weight: bold;">{{ $initial }}</span>
        </div>
        <p class="mb-1 fw-bold admin-name" style="transition: all 0.3s ease;">
            {{ $name }}
        </p>
        <p class="mb-0 small admin-email" style="transition: all 0.3s ease 0.1s;">
            {{ $user->email ?? 'admin@gmail.com' }}
        </p>
    </div>


</aside>

<!-- Tombol Hamburger with animation -->
<button id="toggleSidebar" class="btn btn-primary d-md-none position-fixed top-0 start-0 m-3 hamburger-btn"
    style="z-index: 997; transition: all 0.3s ease;">
    <span class="hamburger-line"
        style="display: block; width: 25px; height: 3px; background: white; margin: 5px 0; transition: all 0.3s ease;"></span>
    <span class="hamburger-line"
        style="display: block; width: 25px; height: 3px; background: white; margin: 5px 0; transition: all 0.3s ease;"></span>
    <span class="hamburger-line"
        style="display: block; width: 25px; height: 3px; background: white; margin: 5px 0; transition: all 0.3s ease;"></span>
</button>

<!-- Overlay (untuk menutup sidebar di mobile) -->
<div id="overlay" class="overlay"
    style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); display: none; z-index: 998; opacity: 0; transition: opacity 0.3s ease;">
</div>

<!-- Tambahkan CSS khusus -->
<style>
    /* Sidebar animation when opening */
    .sidebar-open #sidebar {
        transform: translateX(0) !important;
        box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
    }

    /* Logo animation */
    .logo-container:hover .logo-inner {
        transform: scale(1.05);
    }

    .logo-container:hover .logo-highlight {
        height: 12px;
        background-color: rgba(68, 144, 152, 0.4);
    }

    /* Navigation items */
    .sidebar-link {
        border-radius: 12px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }

    .sidebar-link:hover {
        background-color: #d4e5e8;
        transform: translateX(8px);
    }

    .sidebar-link:hover .icon-bg {
        transform: translate(-50%, -50%) scale(1);
    }

    .sidebar-link:hover img {
        transform: scale(1.1);
    }

    .sidebar-link:hover .nav-label {
        transform: translateX(5px);
    }

    .sidebar-link.active {
        background-color: #c9f1f6;
        color: #449098 !important;
        font-weight: bold;
        box-shadow: inset 4px 0 0 #449098;
    }

    .sidebar-link.active .active-indicator {
        opacity: 1;
        transform: scale(1.5);
    }

    .sidebar-link.active:hover {
        transform: none;
    }

    /* Admin profile animation */
    .admin-profile:hover {
        transform: translate(-50%, -5px) !important;
    }

    .admin-profile:hover .avatar-container {
        transform: scale(1.1);
    }

    .admin-profile:hover .admin-name {
        letter-spacing: 0.5px;
    }

    .admin-profile:hover .admin-email {
        letter-spacing: 0.5px;
    }

    /* Hamburger button animation */
    .sidebar-open .hamburger-btn {
        transform: translateX(310px);
    }

    .sidebar-open .hamburger-btn .hamburger-line:nth-child(1) {
        transform: translateY(8px) rotate(45deg);
    }

    .sidebar-open .hamburger-btn .hamburger-line:nth-child(2) {
        opacity: 0;
    }

    .sidebar-open .hamburger-btn .hamburger-line:nth-child(3) {
        transform: translateY(-8px) rotate(-45deg);
    }

    /* Overlay animation */
    .sidebar-open #overlay {
        display: block;
        opacity: 1;
    }

    /* Smooth scroll for sidebar */
    #sidebar::-webkit-scrollbar {
        width: 6px;
    }

    #sidebar::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    #sidebar::-webkit-scrollbar-thumb {
        background: #449098;
        border-radius: 10px;
    }

    #sidebar::-webkit-scrollbar-thumb:hover {
        background: #3a7a80;
    }

    /* Responsive behavior */
    @media (min-width: 768px) {
        #sidebar {
            transform: translateX(0) !important;
        }

        .hamburger-btn {
            display: none !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.innerWidth >= 768) {
            document.body.classList.add('sidebar-open');
        }
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        // Toggle sidebar
        toggleSidebar.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-open');
        });

        // Close sidebar when clicking overlay
        overlay.addEventListener('click', function() {
            document.body.classList.remove('sidebar-open');
        });

        // Add ripple effect to sidebar links
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                // Remove any existing ripples
                const existingRipples = this.querySelectorAll('.ripple');
                existingRipples.forEach(ripple => ripple.remove());

                // Create new ripple
                const ripple = document.createElement('span');
                ripple.classList.add('ripple');

                // Position the ripple
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = `${size}px`;
                ripple.style.left = `${x}px`;
                ripple.style.top = `${y}px`;
                ripple.style.backgroundColor = 'rgba(68, 144, 152, 0.3)';

                this.appendChild(ripple);

                // Remove ripple after animation
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add ripple effect styles
        const style = document.createElement('style');
        style.textContent = `
            .ripple {
                position: absolute;
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s linear;
                pointer-events: none;
            }
            
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    });
</script>
