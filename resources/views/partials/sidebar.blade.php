<!-- resources/views/partials/sidebar.blade.php -->
<aside class="shadow-sm p-3" style="width: 300px; height: 100vh; position: fixed; background-color: #EFF0F5">
    <div class="mb-4 text-center">
        <h5 class="fw-bold">Logo</h5>
    </div>
    <nav class="nav flex-column position-relative">
        <!-- Indikator -->
        <div id="indicator" class="indicator"></div>

        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" data-target="dashboard">🏠 Dashboard</a>
        <a href="{{ route('management') }}" class="nav-link {{ request()->routeIs('management') ? 'active' : '' }}" data-target="management">📂 Manajemen Data</a>
        <a href="{{ route('attendance') }}" class="nav-link {{ request()->routeIs('attendance') ? 'active' : '' }}" data-target="attendance">📊 Laporan Kehadiran</a>
        <a href="{{ route('chat') }}" class="nav-link {{ request()->routeIs('chat') ? 'active' : '' }}" data-target="chat">💬 Chat</a>
        <a href="{{ route('announcement') }}" class="nav-link {{ request()->routeIs('announcement') ? 'active' : '' }}" data-target="announcement">❓ Pengumuman/FAQ</a>
    </nav>

    <!-- Profil Admin -->
    <div class="position-absolute bottom-0 start-50 translate-middle-x w-100 text-center p-3 bg-success text-white rounded">
        <p class="mb-1 fw-bold">Admin</p>
        <p class="mb-0">admin@gmail.com</p>
    </div>
</aside>