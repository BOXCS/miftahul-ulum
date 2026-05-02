<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Miftahul Ulum</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body x-data="sidebar">

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- SIDEBAR                                                          --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <aside class="sidebar"
           :class="{ 'collapsed': collapsed, 'mobile-open': mobileOpen }"
           aria-label="Sidebar navigasi">

        {{-- ── Logo ─────────────────────────────────────────────────── --}}
        <div class="sidebar-logo">
            <div class="flex items-center gap-3 min-w-0">

                 {{-- Logo Image --}}
        <div class="shrink-0 w-9 h-9 rounded-xl overflow-hidden shadow-lg">
            <img src="{{ asset('assets/logo2.png') }}"
                 alt="Logo"
                 class="w-full h-full object-cover">
        </div>

                {{-- Text (fades on collapse) --}}
                <div class="sidebar-logo-text min-w-0">
                    <div class="text-white font-bold text-sm leading-tight">Miftahul Ulum</div>
                    <div class="text-xs font-medium leading-tight"
                         style="color: rgb(255 255 255 / 0.4);">Monitoring Santri</div>
                </div>
            </div>
        </div>

        {{-- ── Navigation ────────────────────────────────────────────── --}}
        <nav class="sidebar-nav">

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               @click="closeMobile()"
               title="Dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="3" width="7" height="7" rx="1"/>
                    <rect x="14" y="14" width="7" height="7" rx="1"/>
                    <rect x="3" y="14" width="7" height="7" rx="1"/>
                </svg>
                <span class="nav-label">Dashboard</span>
            </a>

            {{-- ── SANTRI Section ─────────────────────────────────────── --}}
            <div class="sidebar-section-label">Santri</div>

            {{-- Data Santri --}}
            <a href="{{ route('students.index') }}"
               class="nav-item {{ request()->routeIs('students.*') ? 'active' : '' }}"
               @click="closeMobile()"
               title="Data Santri">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span class="nav-label">Data Santri</span>
            </a>

            {{-- Data Wali --}}
            <a href="{{ route('parents.index') }}"
               class="nav-item {{ request()->routeIs('parents.*') ? 'active' : '' }}"
               @click="closeMobile()"
               title="Data Wali">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <polyline points="16 11 18 13 22 9"/>
                </svg>
                <span class="nav-label">Data Wali</span>
            </a>

            {{-- Absensi --}}
            <a href="{{ route('attendance.index') }}"
               class="nav-item {{ request()->routeIs('attendance.*') ? 'active' : '' }}"
               @click="closeMobile()"
               title="Absensi">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                    <polyline points="9 16 11 18 15 14"/>
                </svg>
                <span class="nav-label">Absensi</span>
            </a>

            {{-- Perizinan --}}
            <a href="{{ route('permissions.index') }}"
               class="nav-item {{ request()->routeIs('permissions.*') ? 'active' : '' }}"
               @click="closeMobile()"
               title="Perizinan">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/>
                    <rect x="9" y="3" width="6" height="4" rx="2"/>
                    <line x1="9" y1="12" x2="15" y2="12"/>
                    <line x1="9" y1="16" x2="13" y2="16"/>
                </svg>
                <span class="nav-label">Perizinan</span>
            </a>

            {{-- ── MANAJEMEN Section ───────────────────────────────────── --}}
            <div class="sidebar-section-label">Manajemen</div>

            {{-- Data Staff --}}
            <a href="{{ route('staff.index') }}"
               class="nav-item {{ request()->routeIs('staff.*') ? 'active' : '' }}"
               @click="closeMobile()"
               title="Data Staff">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
                <span class="nav-label">Data Staff</span>
            </a>

            {{-- ── KOMUNIKASI Section ──────────────────────────────────── --}}
            <div class="sidebar-section-label">Komunikasi</div>

            {{-- Chat Wali --}}
            <a href="{{ route('chat.index') }}"
               class="nav-item {{ request()->routeIs('chat.*') ? 'active' : '' }}"
               @click="closeMobile()"
               title="Chat Wali">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
                <span class="nav-label">Chat Wali</span>
                <span class="nav-badge">3</span>
            </a>

            {{-- Pengumuman --}}
            <a href="{{ route('announcements.index') }}"
               class="nav-item {{ request()->routeIs('announcements.*') ? 'active' : '' }}"
               @click="closeMobile()"
               title="Pengumuman">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"/>
                    <path d="M19.07 4.93a10 10 0 0 1 0 14.14"/>
                    <path d="M15.54 8.46a5 5 0 0 1 0 7.07"/>
                </svg>
                <span class="nav-label">Pengumuman</span>
            </a>

            {{-- FAQ --}}
            <a href="{{ route('faqs.index') }}"
               class="nav-item {{ request()->routeIs('faqs.*') ? 'active' : '' }}"
               @click="closeMobile()"
               title="FAQ">
                <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                    <line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <span class="nav-label">FAQ</span>
            </a>

        </nav>

        {{-- ── Sidebar Footer / User & Logout ──────────────────────────── --}}
        <div class="sidebar-footer">
            <div class="flex items-center gap-3 px-3 py-2 rounded-xl"
                 style="background: rgb(255 255 255 / 0.05);">

                {{-- Avatar + User Info → link ke profil --}}
                <a href="{{ route('profile.index') }}"
                   class="flex items-center gap-3 min-w-0 flex-1 rounded-lg transition-opacity hover:opacity-80"
                   title="Profil Saya"
                   @click="closeMobile()">
                    <div class="avatar avatar-sm shrink-0"
                         style="background: linear-gradient(135deg, #16a34a, #15803d);
                                color: #fff; font-size: 0.6rem; font-weight: 800;
                                letter-spacing: 0.02em;">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}
                    </div>

                    {{-- User Info (hidden on collapse) --}}
                    <div class="nav-label flex flex-col min-w-0 flex-1">
                        <span class="text-white text-xs font-semibold leading-tight truncate">{{ auth()->user()->name ?? '-' }}</span>
                        <span class="text-xs leading-tight truncate"
                              style="color: rgb(255 255 255 / 0.4);">{{ auth()->user()->roleLabel() ?? 'Staff' }}</span>
                    </div>
                </a>

                {{-- Logout Button (sidebar) --}}
                <button type="button"
                        onclick="openLogoutModal()"
                        class="shrink-0 rounded-lg p-1.5 transition-colors"
                        style="color: rgb(255 255 255 / 0.4);"
                        title="Keluar"
                        onmouseover="this.style.color='#ef4444'; this.style.background='rgb(239 68 68 / 0.1)'"
                        onmouseout="this.style.color='rgb(255 255 255 / 0.4)'; this.style.background='transparent'">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                </button>
            </div>
        </div>

    </aside>

    {{-- Mobile Backdrop --}}
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 md:hidden"
         x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeMobile()"
         style="display: none;"
         aria-hidden="true">
    </div>

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- MAIN WRAPPER                                                     --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div class="main-wrapper" :class="{ 'sidebar-collapsed': collapsed }">

        {{-- ── TOPBAR ──────────────────────────────────────────────── --}}
        <header class="topbar" role="banner">

            {{-- Hamburger / Collapse Toggle --}}
            <button type="button"
                    class="btn-icon shrink-0"
                    style="color: #64748b;"
                    @click="toggle()"
                    aria-label="Toggle sidebar"
                    aria-expanded="true"
                    :aria-expanded="!collapsed">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="6"  x2="21" y2="6"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="18" x2="21" y2="18"/>
                </svg>
            </button>

            {{-- Breadcrumb --}}
            <nav class="breadcrumb hidden md:flex" aria-label="Breadcrumb">
                <a href="{{ route('dashboard') }}" class="breadcrumb-item hover:text-green-600 transition-colors">
                    Beranda
                </a>
                <span class="breadcrumb-sep" aria-hidden="true">/</span>
                <span class="breadcrumb-item active" aria-current="page">
                    @yield('breadcrumb', 'Dashboard')
                </span>
            </nav>

            {{-- Global Search --}}
            <div class="topbar-search">
                <svg xmlns="http://www.w3.org/2000/svg" class="search-icon w-4 h-4" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="search"
                       placeholder="Cari santri, absensi…"
                       aria-label="Cari">
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-1 shrink-0 ml-auto">

                {{-- Notification Bell --}}
                <button type="button"
                        class="btn-icon relative"
                        style="color: #64748b;"
                        aria-label="Notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 hidden sm:block transition-transform"
                             :style="open ? 'transform:rotate(180deg)' : ''"
                             style="color: #94a3b8;"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"/>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.5"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                Profil Saya
                            </a>
                            <a href="{{ route('profile.settings') }}" class="dropdown-item" role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.5"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="3"/>
                                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                                </svg>
                                Pengaturan
                            </a>
                        </div>

                        {{-- Logout --}}
                        <div class="border-t border-slate-100 py-1">
                            <button type="button" onclick="openLogoutModal()" class="dropdown-item danger w-full text-left" role="menuitem">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="1.5"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                    <polyline points="16 17 21 12 16 7"/>
                                    <line x1="21" y1="12" x2="9" y2="12"/>
                                </svg>
                                Keluar
                            </button>
                        </div>

                    </div>{{-- end dropdown-menu --}}
                </div>{{-- end dropdown x-data --}}

            </div>{{-- end right actions --}}
        </header>{{-- end .topbar --}}

        {{-- ── PAGE CONTENT ────────────────────────────────────────── --}}
        <main class="@yield('main-class', 'content-area')" id="main-content">
            @yield('content')
        </main>

        {{-- ── FOOTER ──────────────────────────────────────────────── --}}
        <footer class="mt-auto px-6 py-4 border-t border-slate-100/80">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs" style="color: #94a3b8;">
                    &copy; {{ date('Y') }} Pondok Pesantren Miftahul Ulum. Hak cipta dilindungi.
                </p>
                <p class="text-xs" style="color: #cbd5e1;">
                    Santri Monitoring Dashboard v2.0
                </p>
            </div>
        </footer>

    </div>{{-- end .main-wrapper --}}


    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- TOAST NOTIFICATION CONTAINER                                     --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    <div x-data="toast"
         class="fixed bottom-6 right-6 z-50 flex flex-col gap-3 pointer-events-none"
         aria-live="polite"
         aria-atomic="false"
         @notify.window="show($event.detail.message, $event.detail.type, $event.detail.duration)">
        <template x-for="t in toasts" :key="t.id">
            <div class="pointer-events-auto flex items-start gap-3 rounded-xl border
                        px-4 py-3 shadow-xl min-w-72 max-w-sm animate-fade-in"
                 :class="colorFor(t.type)"
                 role="alert">
                <span class="mt-0.5 shrink-0" x-html="iconFor(t.type)"></span>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium leading-snug" x-text="t.message"></p>
                </div>
                <button type="button"
                        @click="remove(t.id)"
                        class="shrink-0 opacity-50 hover:opacity-100 transition-opacity"
                        aria-label="Tutup notifikasi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>

    {{-- ── Extra scripts from child views ─────────────────────────────── --}}
    @stack('scripts')

    {{-- ═══════════════════════════════════════════════════════════════ --}}
    {{-- MODAL KONFIRMASI KELUAR                                          --}}
    {{-- ═══════════════════════════════════════════════════════════════ --}}
    @if(Route::has('logout'))
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

            <div style="width:56px; height:56px; border-radius:16px; margin:0 auto 16px;
                        background: linear-gradient(135deg, #fee2e2, #fecaca);
                        display:flex; align-items:center; justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;color:#ef4444;" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
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
                <button type="button" onclick="confirmLogout()"
                        id="confirm-logout-btn"
                        style="flex:1; padding:10px 16px; border-radius:12px; border:none;
                               background:linear-gradient(135deg, #ef4444, #dc2626);
                               color:#fff; font-size:0.85rem; font-weight:600;
                               cursor:pointer; transition:opacity 0.15s ease; display:flex; align-items:center; justify-content:center; gap:6px;"
                        onmouseover="this.style.opacity='0.88';"
                        onmouseout="this.style.opacity='1';">
                    <svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Ya, Keluar
                </button>
            </div>

        </div>
    </div>

    <style>
        @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    </style>

    <script>
        const logoutModal = document.getElementById('logout-modal');
        const logoutCard  = document.getElementById('logout-card');
        const logoutForm  = document.getElementById('logout-form');

        function openLogoutModal() {
            logoutModal.style.display = 'flex';
            requestAnimationFrame(() => {
                requestAnimationFrame(() => {
                    logoutCard.style.transform = 'scale(1) translateY(0)';
                    logoutCard.style.opacity   = '1';
                });
            });
        }

        function closeLogoutModal() {
            logoutCard.style.transform = 'scale(0.88) translateY(24px)';
            logoutCard.style.opacity   = '0';
            setTimeout(() => { logoutModal.style.display = 'none'; }, 260);
        }

        function handleModalBackdrop(e) {
            if (e.target === logoutModal) closeLogoutModal();
        }

        function confirmLogout() {
            const btn = document.getElementById('confirm-logout-btn');
            btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" style="width:14px;height:14px;animation:spin 0.7s linear infinite;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>&nbsp;Keluar...';
            btn.style.opacity = '0.75';
            btn.disabled = true;
            if (logoutForm) { logoutForm.submit(); }
            else { window.location.href = '/logout'; }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && logoutModal && logoutModal.style.display === 'flex') {
                closeLogoutModal();
            }
        });
    </script>

</body>
</html>
