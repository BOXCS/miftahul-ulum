<!-- Sidebar -->
<aside id="sidebar" class="shadow-sm p-3 sidebar d-md-block d-none" 
       style="width: 300px; height: 100vh; position: fixed; background-color: #EFF0F5; transition: transform 0.3s ease;">
    
    <!-- Logo -->
    <div class="mb-4 text-center">
        <h5 class="fw-bold">Logo</h5>
    </div>

    <!-- Navigasi -->
    <nav class="nav flex-column position-relative">
        @php
            $routes = [
                'dashboard' => ['icon' => 'dashboard.png', 'label' => 'Dashboard'],
                'management' => ['icon' => 'management.png', 'label' => 'Manajemen Data'],
                'attendance' => ['icon' => 'attendance.png', 'label' => 'Laporan Kehadiran'],
                'chat' => ['icon' => 'chat.png', 'label' => 'Chat'],
                'announcement' => ['icon' => 'announcement.png', 'label' => 'Pengumuman/FAQ']
            ];
        @endphp

        @foreach ($routes as $route => $data)
            <a href="{{ route($route) }}" 
               class="nav-link d-flex align-items-center py-4 px-3 position-relative {{ request()->routeIs($route) ? 'active' : '' }}" 
               style="color: {{ request()->routeIs($route) ? '#449098' : '#3A3541' }};">
               
                @if (request()->routeIs($route))
                    <div class="active-indicator"></div>
                @endif

                <img src="{{ asset('icons/' . $data['icon']) }}" alt="{{ $data['label'] }}" class="me-2" style="width: 24px; height: 24px;">
                {{ $data['label'] }}
            </a>
        @endforeach
    </nav>

    <!-- Profil Admin -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x w-100 text-center p-3 bg-success text-white rounded">
        <p class="mb-1 fw-bold">Admin</p>
        <p class="mb-0">admin@gmail.com</p>
    </div>
</aside>

<!-- Tombol Hamburger -->
<button id="toggleSidebar" class="btn btn-primary d-md-none position-fixed top-0 start-0 m-3">
    ☰
</button>

<!-- Overlay (untuk menutup sidebar di mobile) -->
<div id="overlay" class="overlay"></div>
