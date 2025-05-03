<!-- Sidebar -->
<aside id="sidebar" class="shadow-sm p-3 sidebar d-md-block d-none"
    style="width: 300px; height: 100vh; position: fixed; background-color: #EFF0F5; transition: transform 0.3s ease; overflow-y: auto;">

    <!-- Logo -->
    <div class="mb-4 text-center">
        <h5 class="fw-bold">Logo</h5>
    </div>

    <!-- Navigasi -->
    <nav class="nav flex-column position-relative px-2">
        @php
            $routes = [
                'dashboard' => ['icon' => 'dashboard.png', 'label' => 'Dashboard'],
                'management.index' => ['icon' => 'management.png', 'label' => 'Manajemen Data'],
                'report.index' => ['icon' => 'report.png', 'label' => 'Laporan Kehadiran'],
                'Chat.index' => ['icon' => 'chat.png', 'label' => 'Chat'],
                'announcement' => ['icon' => 'announcement.png', 'label' => 'Pengumuman/FAQ'],
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

                <img src="{{ asset('image/' . $data['icon']) }}" alt="{{ $data['label'] }}"
                    style="width: 24px; height: 24px;">
                <span>{{ $data['label'] }}</span>
            </a>
        @endforeach
    </nav>

    <!-- Profil Admin -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x w-100 text-center p-3"
        style="background-color: #449098; color: white; border-top-left-radius: 20px; border-top-right-radius: 20px;">
        <p class="mb-1 fw-bold">Admin</p>
        <p class="mb-0 small">admin@gmail.com</p>
    </div>
</aside>

<!-- Tombol Hamburger -->
<button id="toggleSidebar" class="btn btn-primary d-md-none position-fixed top-0 start-0 m-3">
    ☰
</button>

<!-- Overlay (untuk menutup sidebar di mobile) -->
<div id="overlay" class="overlay"></div>

<!-- Tambahkan CSS khusus -->
<style>
    .sidebar-link {
        border-radius: 12px;
        transition: background-color 0.3s, transform 0.2s;
    }

    .sidebar-link:hover {
        background-color: #d4e5e8;
        transform: translateX(5px);
    }

    .sidebar-link.active {
        background-color: #c9f1f6;
        color: #449098 !important;
        font-weight: bold;
    }

    .sidebar-link.active:hover {
        transform: none;
    }

    /* Untuk overlay (mobile) */
    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 998;
    }
</style>
