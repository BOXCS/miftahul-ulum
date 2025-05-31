<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Miftahul-Ulum</title>
    <link href="https://fonts.cdnfonts.com/css/caros" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body {
            font-family: 'Caros', sans-serif !important;
        }
    </style>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- jQuery (wajib) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        body {
            font-family: 'Caros', sans-serif;
            background-color: #DFE7F5;
            overflow-x: hidden;
        }
        
        /* Sidebar styling */
        .sidebar {
            width: 300px;
            min-height: 100vh;
            transition: all 0.3s;
            position: fixed;
            z-index: 1000;
        }
        
        /* Main content area */
        .main-content {
            margin-left: 300px;
            transition: all 0.3s;
            min-height: 100vh;
        }
        
        /* Toggle button for mobile */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
        }
        
        /* Responsive adjustments */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            
            .sidebar.active {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .sidebar-toggle {
                display: block;
            }
            
            .main-content.shifted {
                margin-left: 300px;
            }
        }
        
        @media (max-width: 576px) {
            .sidebar {
                width: 250px;
            }
            
            .main-content.shifted {
                margin-left: 250px;
            }
        }
    </style>

    @php
        use App\Helpers\ViteHelper;
    @endphp

    @vite(ViteHelper::allJsFiles())
    @stack('styles')
</head>

<body class="d-flex">
    <!-- Sidebar Toggle Button (Mobile Only) -->
    <button class="btn btn-primary sidebar-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar bg-white shadow">
        @include('partials.sidebar')
    </aside>

    <!-- Content -->
    <main class="main-content flex-grow-1 p-3 p-md-4">
        @yield('content')
        @stack('scripts')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Sidebar toggle functionality
        $(document).ready(function() {
            $('.sidebar-toggle').click(function() {
                $('.sidebar').toggleClass('active');
                $('.main-content').toggleClass('shifted');
                
                // Change icon based on state
                const icon = $(this).find('i');
                if ($('.sidebar').hasClass('active')) {
                    icon.removeClass('fa-bars').addClass('fa-times');
                } else {
                    icon.removeClass('fa-times').addClass('fa-bars');
                }
            });
            
            // Close sidebar when clicking outside on mobile
            $(document).click(function(e) {
                if ($(window).width() <= 992) {
                    if (!$(e.target).closest('.sidebar').length && 
                        !$(e.target).closest('.sidebar-toggle').length && 
                        $('.sidebar').hasClass('active')) {
                        $('.sidebar').removeClass('active');
                        $('.main-content').removeClass('shifted');
                        $('.sidebar-toggle i').removeClass('fa-times').addClass('fa-bars');
                    }
                }
            });
        });
        
        // Adjust content padding when alert is shown
        $(document).on('shown.bs.alert', '.alert', function() {
            const alertHeight = $(this).outerHeight();
            $('.main-content').css('padding-top', alertHeight + 20);
        });
        
        $(document).on('closed.bs.alert', '.alert', function() {
            $('.main-content').css('padding-top', '');
        });
    </script>
    
    @yield('scripts')
</body>

</html>