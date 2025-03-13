<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miftahul-Ulum</title>
    <link href="https://fonts.googleapis.com/css2?family=Caros&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Caros', sans-serif;
        }
    </style>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chart.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"> --}}

    <style>
        body {
            font-family: 'Caros', sans-serif;
            background-color: #DFE7F5;
        }

        .main-content {
            padding: 1rem; /* Padding untuk konten */
            flex-grow: 1;
        }
    </style>

    @php
        use App\Helpers\ViteHelper;
    @endphp

    @vite(ViteHelper::allJsFiles())

</head>

<body class="d-flex flex-column flex-lg-row">
    <!-- Sidebar -->
    <aside class="sidebar flex-shrink-0" style="width: 300px; z-index: 1;">
        @include('partials.sidebar')
    </aside>

    <!-- Main Content -->
    <main class="main-content flex-grow-1 p-4">
        @yield('content')
        @stack('scripts')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
