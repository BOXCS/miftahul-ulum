@extends('layouts.app')

@section('title', 'Pengaturan')
@section('breadcrumb', 'Pengaturan')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="mb-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700 flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 shrink-0" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- ── Header Pengaturan ────────────────────────────────────────── --}}
    <div class="card p-6 mb-5 flex items-center gap-4">
        <div class="shrink-0 w-13 h-13 rounded-2xl flex items-center justify-center shadow-md"
             style="width:52px;height:52px;background: linear-gradient(135deg, #f0fdf4, #dcfce7);">
            <svg xmlns="http://www.w3.org/2000/svg" style="width:26px;height:26px;color:#16a34a;" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="3"/>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <h1 class="text-xl font-bold" style="color: #1e293b;">Pengaturan</h1>
            <p class="text-sm mt-0.5" style="color: #94a3b8;">Kelola keamanan dan preferensi akun Anda.</p>
        </div>
        <a href="{{ route('profile.index') }}"
           class="shrink-0 inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-xs font-semibold transition"
           style="border: 1.5px solid #e2e8f0; color: #475569; background: #f8fafc;"
           onmouseover="this.style.borderColor='#16a34a'; this.style.color='#16a34a'; this.style.background='#f0fdf4';"
           onmouseout="this.style.borderColor='#e2e8f0'; this.style.color='#475569'; this.style.background='#f8fafc';">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profil Saya
        </a>
    </div>

    {{-- ── Keamanan Akun — Ganti Password ──────────────────────────── --}}
    <div class="card p-7 mb-5">
        <div class="flex items-center gap-3 mb-6">
            <div class="shrink-0 w-9 h-9 rounded-xl flex items-center justify-center"
                 style="background: #f0fdf4;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" style="color: #16a34a;" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold" style="color: #1e293b;">Keamanan Akun</h2>
                <p class="text-xs" style="color: #94a3b8;">Ganti kata sandi secara berkala untuk keamanan akun.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.password', $user->id) }}" class="space-y-4">
            @csrf
            @method('PATCH')

            {{-- Kata Sandi Saat Ini --}}
            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color: #475569;">Kata Sandi Saat Ini</label>
                <div class="relative">
                    <input id="current_password" type="password" name="current_password"
                           class="w-full rounded-xl border px-4 py-3 pr-11 text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"
                           style="border-color: #e2e8f0; background: #f8fafc; color: #1e293b;" required>
                    <button type="button" onclick="togglePassword('current_password', 'eye-current')"
                            class="absolute inset-y-0 right-0 flex items-center px-3.5 transition-colors"
                            style="color: #94a3b8;" tabindex="-1"
                            onmouseover="this.style.color='#16a34a'" onmouseout="this.style.color='#94a3b8'">
                        <svg id="eye-current" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5" style="width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                @error('current_password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kata Sandi Baru --}}
            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color: #475569;">Kata Sandi Baru</label>
                <div class="relative">
                    <input id="new_password" type="password" name="password"
                           class="w-full rounded-xl border px-4 py-3 pr-11 text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"
                           style="border-color: #e2e8f0; background: #f8fafc; color: #1e293b;" required minlength="8">
                    <button type="button" onclick="togglePassword('new_password', 'eye-new')"
                            class="absolute inset-y-0 right-0 flex items-center px-3.5 transition-colors"
                            style="color: #94a3b8;" tabindex="-1"
                            onmouseover="this.style.color='#16a34a'" onmouseout="this.style.color='#94a3b8'">
                        <svg id="eye-new" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Konfirmasi Kata Sandi Baru --}}
            <div>
                <label class="block text-sm font-semibold mb-1.5" style="color: #475569;">Konfirmasi Kata Sandi Baru</label>
                <div class="relative">
                    <input id="confirm_password" type="password" name="password_confirmation"
                           class="w-full rounded-xl border px-4 py-3 pr-11 text-sm transition focus:outline-none focus:ring-2 focus:ring-green-500/30 focus:border-green-500"
                           style="border-color: #e2e8f0; background: #f8fafc; color: #1e293b;" required>
                    <button type="button" onclick="togglePassword('confirm_password', 'eye-confirm')"
                            class="absolute inset-y-0 right-0 flex items-center px-3.5 transition-colors"
                            style="color: #94a3b8;" tabindex="-1"
                            onmouseover="this.style.color='#16a34a'" onmouseout="this.style.color='#94a3b8'">
                        <svg id="eye-confirm" xmlns="http://www.w3.org/2000/svg" style="width:18px;height:18px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                            <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="pt-1">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl px-6 py-2.5 text-sm font-semibold text-white transition hover:opacity-90"
                        style="background: linear-gradient(135deg, #16a34a, #15803d);">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Ganti Kata Sandi
                </button>
            </div>
        </form>
    </div>

    {{-- ── Tips Keamanan ────────────────────────────────────────────── --}}
    <div class="rounded-xl border px-5 py-4" style="border-color: #fde68a; background: #fffbeb;">
        <div class="flex items-start gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mt-0.5 shrink-0" style="color: #d97706;" viewBox="0 0 24 24"
                 fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>
            <div>
                <p class="text-xs font-semibold mb-1" style="color: #92400e;">Tips Keamanan</p>
                <p class="text-xs leading-relaxed" style="color: #a16207;">
                    Gunakan kata sandi minimal 8 karakter yang mengandung kombinasi huruf besar, huruf kecil, angka, dan simbol.
                    Jangan gunakan kata sandi yang sama di berbagai platform.
                </p>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script>
// SVG icon: mata terbuka (visible)
const eyeOpenSVG = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
// SVG icon: mata coret (hidden)
const eyeClosedSVG = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;

function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);
    if (!input || !icon) return;

    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = eyeOpenSVG;
    } else {
        input.type = 'password';
        icon.innerHTML = eyeClosedSVG;
    }
}
</script>
@endpush

@endsection