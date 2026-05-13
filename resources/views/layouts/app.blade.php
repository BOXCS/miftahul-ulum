<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Miftahul Ulum</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    <style>
        /* ─── CSS Variables ─────────────────────────────────────────── */
        :root {
            --sidebar-w: 240px;
            --sidebar-w-col: 68px;
            --topbar-h: 72px;
            --teal-900: #134e4a;
            --teal-800: #115e59;
            --teal-700: #0f766e;
            --teal-600: #0d9488;
            --teal-500: #14b8a6;
            --teal-400: #2dd4bf;
            --teal-100: #ccfbf1;
            --teal-50: #f0fdfa;
            --sidebar-bg: #134e4a;
            /* teal-900, gelap elegan */
            --sidebar-surface: #0f3d3a;
            /* lebih gelap sedikit */
            --sidebar-border: rgba(255, 255, 255, .08);
            --sidebar-text: rgba(255, 255, 255, 1);
            --sidebar-hover: rgba(255, 255, 255, .12);
            --transition: all .22s cubic-bezier(.4, 0, .2, 1);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Geist', 'DM Sans', ui-sans-serif, system-ui, sans-serif;
            background: #f4f6f9;
            color: #1e293b;
            overflow-x: hidden;
        }

        /* ─── Layout Shell ───────────────────────────────────────────── */
        .app-shell {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* ─── SIDEBAR ────────────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-w);
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 50;
            transition: width .25s cubic-bezier(.4, 0, .2, 1);
            overflow: hidden;
            border-right: 1px solid var(--sidebar-border);
        }

        /* Top teal glow blob */
        .sidebar::after {
            content: '';
            position: absolute;
            top: -60px;
            left: -40px;
            width: 200px;
            height: 200px;
            background: radial-gradient(circle, rgba(255, 255, 255, .15) 0%, transparent 70%);
            pointer-events: none;
            border-radius: 50%;
        }

        .sidebar.collapsed {
            width: var(--sidebar-w-col);
        }

        /* ── Logo Area ──────────────────────────────────────────────── */
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 18px 16px 16px;
            border-bottom: 1px solid var(--sidebar-border);
            min-height: 68px;
            position: relative;
            flex-shrink: 0;
        }

        .logo-mark {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            overflow: visible;
            background: transparent;
            box-shadow: none;
        }

        .logo-mark img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            border-radius: 8px;
        }

        .logo-text {
            overflow: hidden;
            white-space: nowrap;
            transition: var(--transition);
            opacity: 1;
        }

        .collapsed .logo-text {
            opacity: 0;
            width: 0;
        }

        .logo-text-primary {
            font-size: .875rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.02em;
            line-height: 1.2;
        }

        .logo-text-secondary {
            font-size: .65rem;
            font-weight: 500;
            color: rgba(255, 255, 255, .35);
            letter-spacing: .06em;
            text-transform: uppercase;
            margin-top: 1px;
        }

        /* Collapse toggle inside logo */
        .collapse-btn {
            margin-left: auto;
            width: 26px;
            height: 26px;
            border-radius: 7px;
            border: 1px solid var(--sidebar-border);
            background: var(--sidebar-hover);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(255, 255, 255, .3);
            transition: var(--transition);
            flex-shrink: 0;
        }

        .collapse-btn:hover {
            background: rgba(255, 255, 255, .1);
            color: #fff;
        }

        .collapsed .collapse-btn {
            margin-left: 0;
        }

        /* ── Nav ───────────────────────────────────────────────────── */
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 10px 10px;
            display: flex;
            flex-direction: column;
            gap: 1px;
            scrollbar-width: none;
        }

        .sidebar-nav::-webkit-scrollbar {
            display: none;
        }

        .nav-section-label {
            font-size: .58rem;
            font-weight: 800;
            letter-spacing: .14em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, .55);
            padding: 14px 8px 5px;
            white-space: nowrap;
            overflow: hidden;
            transition: var(--transition);
        }

        .collapsed .nav-section-label {
            opacity: 0;
            height: 0;
            padding: 0;
        }

        /* Nav Item */
        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--sidebar-text);
            font-size: .82rem;
            font-weight: 600;
            white-space: nowrap;
            position: relative;
            transition: var(--transition);
            cursor: pointer;
            overflow: hidden;
            border: 1px solid transparent;
        }

        /* Collapsed: center icon perfectly dalam 68px */
        .collapsed .nav-item {
            padding: 9px 0;
            justify-content: center;
            gap: 0;
        }

        .nav-item::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 10px;
            background: var(--sidebar-hover);
            opacity: 0;
            transition: opacity .18s;
        }

        .nav-item:hover::before {
            opacity: 1;
        }

        .nav-item:hover {
            color: rgba(255, 255, 255, .85);
        }

        /* Active state */
        .nav-item.active {
            background: #ffffff;
            color: #134e4a;
            border-color: #ffffff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .12);
        }

        .nav-item.active::before {
            color: #0f766e;
        }

        .nav-item.active:hover {
            color: #5eead4;
        }

        /* Active left accent bar */
        .nav-item.active::after {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            background: linear-gradient(180deg, #0d9488, #134e4a);
            border-radius: 0 3px 3px 0;
        }

        .nav-icon {
            width: 18px;
            height: 18px;
            min-width: 18px;
            min-height: 18px;
            flex-shrink: 0;
            stroke-width: 1.7;
            transition: var(--transition);
            display: block;
        }

        .nav-item.active .nav-icon {
            color: #134e4a;
        }

        .nav-label {
            flex: 1;
            overflow: hidden;
            transition: var(--transition);
            font-size: .8rem;
        }

        .collapsed .nav-label {
            opacity: 0;
            width: 0;
            flex: 0;
        }

        /* Badge */
        .nav-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            border-radius: 999px;
            font-size: .6rem;
            font-weight: 800;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: #fff;
            flex-shrink: 0;
            transition: var(--transition);
            box-shadow: 0 2px 6px rgba(239, 68, 68, .4);
            animation: badge-pulse 2s ease-in-out infinite;
        }

        @keyframes badge-pulse {

            0%,
            100% {
                box-shadow: 0 2px 6px rgba(239, 68, 68, .4);
            }

            50% {
                box-shadow: 0 2px 12px rgba(239, 68, 68, .6);
            }
        }

        .collapsed .nav-badge {
            position: absolute;
            top: 6px;
            right: 6px;
            min-width: 8px;
            height: 8px;
            padding: 0;
            font-size: 0;
        }

        /* ── Sidebar Divider ────────────────────────────────────────── */
        .sidebar-divider {
            height: 1px;
            background: var(--sidebar-border);
            margin: 6px 8px;
        }

        /* ── Sidebar Footer ─────────────────────────────────────────── */
        .sidebar-footer {
            padding: 10px;
            border-top: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }

        .user-card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 12px;
            background: var(--sidebar-surface);
            border: 1px solid var(--sidebar-border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .collapsed .user-card {
            justify-content: center;
            padding: 10px 0;
            gap: 0;
        }

        .user-card:hover {
            background: rgba(255, 255, 255, .07);
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 9px;
            background: linear-gradient(135deg, var(--teal-600), var(--teal-800));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .6rem;
            font-weight: 900;
            color: #fff;
            flex-shrink: 0;
            letter-spacing: .04em;
            box-shadow: 0 0 0 1px rgba(45, 212, 191, .2);
        }

        .user-info {
            flex: 1;
            min-width: 0;
            overflow: hidden;
            transition: var(--transition);
        }

        .collapsed .user-info {
            opacity: 0;
            width: 0;
            flex: 0;
        }

        .user-name {
            font-size: .78rem;
            font-weight: 700;
            color: rgba(255, 255, 255, .9);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }

        .user-role {
            font-size: .65rem;
            color: rgba(255, 255, 255, .3);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            margin-top: 1px;
        }

        .logout-btn {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, .08);
            background: transparent;
            color: rgba(255, 255, 255, .3);
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            flex-shrink: 0;
            text-decoration: none;
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, .15);
            color: #f87171;
            border-color: rgba(239, 68, 68, .2);
        }

        /* Tooltip for collapsed mode */
        .sidebar-tooltip {
            position: absolute;
            left: calc(100% + 14px);
            top: 50%;
            transform: translateY(-50%);
            background: #1e2d2c;
            color: rgba(255, 255, 255, .9);
            font-size: .72rem;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 8px;
            white-space: nowrap;
            pointer-events: none;
            opacity: 0;
            transition: opacity .15s, transform .15s;
            transform: translateY(-50%) translateX(-6px);
            border: 1px solid var(--sidebar-border);
            box-shadow: 0 4px 12px rgba(0, 0, 0, .3);
            z-index: 200;
        }

        .collapsed .nav-item:hover .sidebar-tooltip {
            opacity: 1;
            transform: translateY(-50%) translateX(0);
        }

        /* ── MAIN CONTENT ────────────────────────────────────────────── */
        .main-wrapper {
            flex: 1;
            min-width: 0;
            width: 100%;
            margin-left: var(--sidebar-w);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin-left .25s cubic-bezier(.4, 0, .2, 1);
        }

        .main-wrapper.sidebar-collapsed {
            margin-left: var(--sidebar-w-col);
        }

        /* ── TOPBAR ──────────────────────────────────────────────────── */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 40;
            height: var(--topbar-h);
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 20px;
            background: rgba(244, 246, 249, .92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, .8);
            box-shadow: 0 1px 3px rgba(15, 23, 42, .04);
        }

        .topbar-search {
            flex: 1;
            max-width: 360px;
            position: relative;
        }

        .topbar-search input {
            width: 100%;
            padding: 8px 14px 8px 36px;
            border-radius: 10px;
            border: 1.5px solid #e2e8f0;
            font-size: .8rem;
            background: #fff;
            font-family: inherit;
            outline: none;
            color: #334155;
            transition: border .2s, box-shadow .2s;
        }

        .topbar-search input:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 3px rgba(13, 148, 136, .1);
        }

        .topbar-search .search-icon {
            position: absolute;
            left: 11px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: none;
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s, color .15s;
            color: #64748b;
        }

        .btn-icon:hover {
            background: #f1f5f9;
            color: #334155;
        }

        .notif-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #ef4444;
            border: 2px solid #f4f6f9;
        }

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .breadcrumb-item {
            font-size: .78rem;
            color: #94a3b8;
            font-weight: 600;
            text-decoration: none;
            transition: color .15s;
        }

        .breadcrumb-item:hover {
            color: #0d9488;
        }

        .breadcrumb-item.active {
            color: #1e293b;
            font-weight: 700;
        }

        .breadcrumb-sep {
            color: #cbd5e1;
            font-size: .78rem;
        }

        /* Dropdown */
        .dropdown-menu {
            position: absolute;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 8px 30px rgba(15, 23, 42, .12);
            overflow: hidden;
            z-index: 200;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 16px;
            font-size: .8rem;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            transition: background .15s, color .15s;
            cursor: pointer;
            border: none;
            background: none;
            font-family: inherit;
            width: 100%;
        }

        .dropdown-item:hover {
            background: #f0fdfa;
            color: #0d9488;
        }

        .dropdown-item.danger {
            color: #e11d48;
        }

        .dropdown-item.danger:hover {
            background: #fff1f2;
            color: #e11d48;
        }

        .avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9px;
            font-weight: 800;
            flex-shrink: 0;
        }

        .avatar-sm {
            width: 32px;
            height: 32px;
            font-size: .6rem;
        }

        /* ── Content ───────────────────────────────────────────────── */
        .content-area {
            flex: 1;
            padding: 24px 28px;
        }

        /* ── Mobile ─────────────────────────────────────────────────── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform .25s cubic-bezier(.4, 0, .2, 1), width .25s cubic-bezier(.4, 0, .2, 1);
                width: var(--sidebar-w) !important;
            }

            .sidebar.mobile-open {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0 !important;
                width: 100% !important;
            }

            .content-area {
                padding: 16px;
            }

            .collapse-btn {
                display: none;
            }
        }

        /* ── Animations ─────────────────────────────────────────────── */
        .animate-fade-in {
            animation: fadeIn .25s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 5px;
            height: 5px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 999px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }

        /* ── Global button teal ─────────────────────────────────────── */
        .btn-teal {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: 12px;
            background: linear-gradient(135deg, #059669, #047857);
            color: #fff;
            font-size: .825rem;
            font-weight: 700;
            border: none;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 4px 14px rgba(5, 150, 105, .28);
            transition: background .2s, transform .2s, box-shadow .2s;
            font-family: inherit;
        }

        .btn-teal:hover {
            background: linear-gradient(135deg, #047857, #065f46);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(5, 150, 105, .35);
            color: #fff;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            /* ⬅️ INI KUNCINYA */
        }

        /* Bigger text */
        .topbar .breadcrumb-item {
            font-size: .9rem;
        }

        .topbar .btn-icon {
            width: 40px;
            height: 40px;
        }

        .db {
            padding: 8px 8px;
        }
    </style>
</head>

<body>
    <div class="app-shell" x-data="{
        collapsed: localStorage.getItem('sidebar_collapsed') === 'true',
        mobileOpen: false,
        toggle() {
            if (window.innerWidth <= 768) {
                this.mobileOpen = !this.mobileOpen;
            } else {
                this.collapsed = !this.collapsed;
                localStorage.setItem('sidebar_collapsed', this.collapsed);
            }
        },
        closeMobile() { this.mobileOpen = false; }
    }">

        <aside class="sidebar" :class="{ 'collapsed': collapsed, 'mobile-open': mobileOpen }"
            aria-label="Sidebar navigasi">

            <div class="sidebar-logo">
                <div class="logo-mark">
                    <img src="{{ asset('images/logo_miftahul.png') }}" alt="Logo Miftahul Ulum">
                </div>
                <div class="logo-text">
                    <div class="logo-text-primary">Miftahul Ulum</div>
                    <div class="logo-text-secondary">Monitoring Santri</div>
                </div>
            </div>

            <nav class="sidebar-nav">
                <a href="{{ route('dashboard') }}"
                    class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}" @click="closeMobile()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="3" width="7" height="7" rx="1" />
                        <rect x="14" y="14" width="7" height="7" rx="1" />
                        <rect x="3" y="14" width="7" height="7" rx="1" />
                    </svg>
                    <span class="nav-label">Dashboard</span>
                </a>

                <div class="nav-section-label">Santri</div>

                <a href="{{ route('students.index') }}"
                    class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}" @click="closeMobile()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <span class="nav-label">Data Santri</span>
                </a>

                <a href="{{ route('parents.index') }}"
                    class="nav-item {{ request()->routeIs('parents.*') ? 'active' : '' }}" @click="closeMobile()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <polyline points="16 11 18 13 22 9" />
                    </svg>
                    <span class="nav-label">Data Wali</span>
                </a>

                <a href="{{ route('attendance.index') }}"
                    class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}" @click="closeMobile()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                        <line x1="16" y1="2" x2="16" y2="6" />
                        <line x1="8" y1="2" x2="8" y2="6" />
                        <line x1="3" y1="10" x2="21" y2="10" />
                        <polyline points="9 16 11 18 15 14" />
                    </svg>
                    <span class="nav-label">Absensi</span>
                </a>

                <a href="{{ route('permissions.index') }}"
                    class="nav-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}"
                    @click="closeMobile()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2" />
                        <rect x="9" y="3" width="6" height="4" rx="2" />
                        <line x1="9" y1="12" x2="15" y2="12" />
                        <line x1="9" y1="16" x2="13" y2="16" />
                    </svg>
                    <span class="nav-label">Perizinan</span>
                </a>

                <div class="nav-section-label">Manajemen</div>

                <a href="{{ route('staff.index') }}"
                    class="nav-item {{ request()->routeIs('staff.*') ? 'active' : '' }}" @click="closeMobile()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                    </svg>
                    <span class="nav-label">Data Staff</span>
                </a>

                <div class="nav-section-label">Komunikasi</div>

                <a href="{{ route('chat.index') }}"
                    class="nav-item {{ request()->routeIs('chat.*') ? 'active' : '' }}" @click="closeMobile()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                    <span class="nav-label">Chat Wali</span>
                </a>

                <a href="{{ route('announcements.index') }}"
                    class="nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}"
                    @click="closeMobile()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5" />
                        <path d="M19.07 4.93a10 10 0 0 1 0 14.14" />
                        <path d="M15.54 8.46a5 5 0 0 1 0 7.07" />
                    </svg>
                    <span class="nav-label">Pengumuman</span>
                </a>

                <a href="{{ route('faqs.index') }}"
                    class="nav-item {{ request()->routeIs('faqs.*') ? 'active' : '' }}" @click="closeMobile()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                    <span class="nav-label">FAQ</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <div class="user-card flex items-center gap-3 px-3 py-2 rounded-xl"
                    style="background: rgb(255 255 255 / 0.05);">

                    {{-- Avatar + User Info → link ke profil --}}
                    <a href="{{ route('profile.index') }}"
                        class="flex items-center gap-3 min-w-0 flex-1 rounded-lg transition-opacity hover:opacity-80"
                        title="Profil Saya" @click="closeMobile()">
                        <div class="avatar avatar-sm shrink-0"
                            style="background: linear-gradient(135deg, #16a34a, #15803d);
                                color: #fff; font-size: 0.6rem; font-weight: 800;
                                letter-spacing: 0.02em;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                        </div>

                        {{-- User Info (hidden on collapse) --}}
                        <div class="nav-label flex flex-col min-w-0 flex-1">
                            <span
                                class="text-white text-xs font-semibold leading-tight truncate">{{ auth()->user()->name ?? '-' }}</span>
                            <span class="text-xs leading-tight truncate"
                                style="color: rgb(255 255 255 / 0.4);">{{ auth()->user()->roleLabel() ?? 'Staff' }}</span>
                        </div>
                    </a>

                    {{-- Logout Button (sidebar) --}}
                    <button type="button" onclick="openLogoutModal()"
                        class="shrink-0 rounded-lg p-1.5 transition-colors" style="color: rgb(255 255 255 / 0.4);"
                        title="Keluar"
                        onmouseover="this.style.color='#ef4444'; this.style.background='rgb(239 68 68 / 0.1)'"
                        onmouseout="this.style.color='rgb(255 255 255 / 0.4)'; this.style.background='transparent'">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 md:hidden" x-show="mobileOpen"
            @click="closeMobile()" style="display:none;">
        </div>

        <div class="main-wrapper" :class="{ 'sidebar-collapsed': collapsed }">
            <header class="topbar">
                <div class="flex items-center gap-4">
                    <button type="button" class="btn-icon" @click="toggle()">☰</button>

                    <nav class="breadcrumb hidden md:flex">
                        <a href="{{ route('dashboard') }}" class="breadcrumb-item">Beranda</a>
                        <span class="breadcrumb-sep">/</span>
                        <span class="breadcrumb-item active">@yield('breadcrumb', 'Dashboard')</span>
                    </nav>
                </div>

                {{-- Global Search --}}
                <div class="topbar-search">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="search-icon"
                        width="16" height="16" {{-- tambahkan ini --}}
                        viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                    </svg>
                    <input type="search"
                        placeholder="Cari santri, absensi…"
                        aria-label="Cari">
                </div>

                {{-- Right Actions --}}
                <div class="flex items-center gap-1 shrink-0 ml-auto ">

                    {{-- Notification Bell --}}
                    <button type="button"
                        class="btn-icon relative"
                        style="color: #64748b;"
                        aria-label="Notifikasi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                            <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                        </svg>
                        <span class="notif-dot" aria-hidden="true"></span>
                    </button>

                    {{-- Divider --}}
                    <div class="w-px h-6 mx-1" style="background: #e2e8f0;"></div>

                    {{-- User Profile Dropdown --}}
                    <div class="relative" x-data="dropdown">

                        <button type="button"
                            class="flex items-center gap-2.5 rounded-xl px-2 py-1.5 transition-colors"
                            style="color: #334155;"
                            :class="open ? 'bg-slate-100' : 'hover:bg-slate-50'"
                            @click="toggle()"
                            aria-haspopup="true"
                            :aria-expanded="open">
                            <div class="avatar avatar-sm shrink-0"
                                style="background: linear-gradient(135deg, #16a34a, #15803d);
                                    color: #fff; font-size: 0.6rem; font-weight: 800;">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                            </div>
                            <div class="hidden sm:flex flex-col items-start leading-tight">
                                <span class="text-xs font-semibold" style="color: #1e293b;">{{ auth()->user()->name ?? '-' }}</span>
                                <span class="text-xs" style="color: #94a3b8;">{{ auth()->user()->roleLabel() ?? 'Staff' }}</span>
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-3.5 h-3.5 hidden sm:block transition-transform"
                                width="14" height="14"
                                :style="open ? 'transform:rotate(180deg)' : ''"
                                style="color: #94a3b8;"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="6 9 12 15 18 9" />
                            </svg>
                        </button>

                        {{-- Dropdown Panel --}}
                        <div class="dropdown-menu right-0 mt-2 w-56"
                            x-show="open"
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                            style="display:none;"
                            role="menu">

                            {{-- User Info Header --}}
                            <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100">
                                <div class="avatar avatar-sm shrink-0"
                                    style="background: linear-gradient(135deg, #16a34a, #15803d);
                                        color: #fff; font-size: 0.6rem; font-weight: 800;">
                                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-semibold truncate" style="color: #1e293b;">{{ auth()->user()->name ?? '-' }}</p>
                                    <p class="text-xs truncate" style="color: #94a3b8;">{{ auth()->user()->email ?? '' }}</p>
                                </div>
                            </div>

                            {{-- Menu Items --}}
                            <div class="py-1">
                                <a href="{{ route('profile.index') }}" class="dropdown-item" role="menuitem">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                    Profil Saya
                                </a>
                                <a href="{{ route('profile.settings') }}" class="dropdown-item" role="menuitem">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="3" />
                                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                                    </svg>
                                    Pengaturan
                                </a>
                            </div>

                            {{-- Logout --}}
                            <div class="border-t border-slate-100 py-1">
                                <button type="button" onclick="openLogoutModal()" class="dropdown-item danger w-full text-left" role="menuitem">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                                        <polyline points="16 17 21 12 16 7" />
                                        <line x1="21" y1="12" x2="9" y2="12" />
                                    </svg>
                                    Keluar
                                </button>
                            </div>

                        </div>{{-- end dropdown-menu --}}
                    </div>{{-- end dropdown x-data --}}

                </div>{{-- end right actions --}}
            </header>

            <main class="@yield('main-class', 'content-area')" id="main-content">
                @yield('content')
            </main>

            @unless(View::hasSection('hide-footer'))
            <footer
                style="padding:14px 28px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                <p style="font-size:.72rem;color:#94a3b8;">
                    &copy; {{ date('Y') }} Pondok Pesantren Miftahul Ulum. Hak cipta dilindungi.
                </p>
                <p style="font-size:.72rem;color:#cbd5e1;">Santri Monitoring Dashboard v2.0</p>
            </footer>
            @endunless
        </div>

        @stack('scripts')
        {{-- ═══════════════════════════════════════════════════════════════ --}}
        @if (Route::has('logout'))
        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display:none;">
            @csrf
        </form>
        @endif

        <div id="logout-modal"
            style="display:none; position:fixed; inset:0; z-index:9999;
                background: rgba(15,23,42,0.55); backdrop-filter: blur(4px);
                align-items:center; justify-content:center;"
            onclick="handleModalBackdrop(event)">

            <div id="logout-card"
                style="background:#fff; border-radius:20px; padding:32px 28px; width:100%; max-width:360px;
                    margin:0 16px; box-shadow: 0 25px 60px -10px rgba(15,23,42,0.35), 0 0 0 1px rgba(226,232,240,0.6);
                    transform: scale(0.88) translateY(24px); opacity:0;
                    transition: transform 0.32s cubic-bezier(0.34,1.56,0.64,1), opacity 0.22s ease;">

                <div
                    style="width:56px; height:56px; border-radius:16px; margin:0 auto 16px;
                        background: linear-gradient(135deg, #fee2e2, #fecaca);
                        display:flex; align-items:center; justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;color:#ef4444;"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                </div>

                <h3 style="text-align:center; font-size:1.05rem; font-weight:700; color:#1e293b; margin:0 0 6px;">
                    Yakin Ingin Keluar?
                </h3>
                <p style="text-align:center; font-size:0.8rem; color:#94a3b8; margin:0 0 24px; line-height:1.5;">
                    Sesi Anda akan diakhiri dan Anda perlu masuk kembali untuk mengakses sistem.
                </p>

                <div style="display:flex; gap:10px;">
                    <button type="button" onclick="closeLogoutModal()"
                        style="flex:1; padding:10px 16px; border-radius:12px; border:1.5px solid #e2e8f0;
                               background:#f8fafc; color:#475569; font-size:0.85rem; font-weight:600;
                               cursor:pointer; transition:all 0.15s ease;"
                        onmouseover="this.style.background='#f1f5f9'; this.style.borderColor='#cbd5e1';"
                        onmouseout="this.style.background='#f8fafc'; this.style.borderColor='#e2e8f0';">
                        Batal
                    </button>
                    <button type="button" onclick="confirmLogout()" id="confirm-logout-btn"
                        style="flex:1; padding:10px 16px; border-radius:12px; border:none;
                               background:linear-gradient(135deg, #ef4444, #dc2626);
                               color:#fff; font-size:0.85rem; font-weight:600;
                               cursor:pointer; transition:opacity 0.15s ease; display:flex; align-items:center; justify-content:center; gap:6px;"
                        onmouseover="this.style.opacity='0.88';" onmouseout="this.style.opacity='1';">
                        <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Ya, Keluar
                    </button>
                </div>

            </div>
        </div>

        <style>
            @keyframes spin {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }
        </style>

        <script>
            const logoutModal = document.getElementById('logout-modal');
            const logoutCard = document.getElementById('logout-card');
            const logoutForm = document.getElementById('logout-form');

            function openLogoutModal() {
                logoutModal.style.display = 'flex';
                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        logoutCard.style.transform = 'scale(1) translateY(0)';
                        logoutCard.style.opacity = '1';
                    });
                });
            }

            function closeLogoutModal() {
                logoutCard.style.transform = 'scale(0.88) translateY(24px)';
                logoutCard.style.opacity = '0';
                setTimeout(() => {
                    logoutModal.style.display = 'none';
                }, 260);
            }

            function handleModalBackdrop(e) {
                if (e.target === logoutModal) closeLogoutModal();
            }

            function confirmLogout() {
                const btn = document.getElementById('confirm-logout-btn');
                btn.innerHTML =
                    '<svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;animation:spin 0.7s linear infinite;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>&nbsp;Keluar...';
                btn.style.opacity = '0.75';
                btn.disabled = true;
                if (logoutForm) {
                    logoutForm.submit();
                } else {
                    window.location.href = '/logout';
                }
            }

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && logoutModal && logoutModal.style.display === 'flex') {
                    closeLogoutModal();
                }
            });
        </script>

</body>

</html>