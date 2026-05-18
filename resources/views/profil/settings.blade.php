@extends('layouts.app')

@section('title', 'Pengaturan')
@section('breadcrumb', 'Pengaturan')

@push('styles')
<style>
    .pg-settings {
        max-width: 640px;
        margin: 0 auto;
        padding: 28px 20px 40px;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .flash-success {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        font-size: .83rem;
        font-weight: 600;
        color: #15803d;
    }

    .pg-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e8edf3;
        box-shadow: 0 2px 10px rgba(15,23,42,.055), 0 1px 3px rgba(15,23,42,.035);
        overflow: hidden;
    }

    .pg-header {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 24px;
    }

    .pg-header-icon {
        width: 50px;
        height: 50px;
        border-radius: 14px;
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(22,163,74,.15);
    }

    .pg-header-body { flex: 1; min-width: 0; }

    .pg-header-body h1 {
        font-size: 1.15rem;
        font-weight: 800;
        color: #1e293b;
        margin: 0 0 2px;
        letter-spacing: -.02em;
    }

    .pg-header-body p {
        font-size: .78rem;
        color: #94a3b8;
        margin: 0;
    }

    .pg-header-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        font-size: .75rem;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .pg-header-link:hover {
        border-color: #16a34a;
        color: #16a34a;
        background: #f0fdf4;
    }

    .pg-section { padding: 24px; }

    .pg-section-head {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 22px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f1f5f9;
    }

    .pg-section-ico {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #f0fdf4;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #16a34a;
    }

    .pg-section-head h2 {
        font-size: .93rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 2px;
    }

    .pg-section-head p {
        font-size: .72rem;
        color: #94a3b8;
        margin: 0;
    }

    .pg-form { display: flex; flex-direction: column; gap: 16px; }

    .form-group { display: flex; flex-direction: column; gap: 6px; }

    .form-label {
        font-size: .8rem;
        font-weight: 600;
        color: #475569;
    }

    .form-input-wrap { position: relative; }

    .form-input {
        width: 100%;
        padding: 11px 44px 11px 14px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #1e293b;
        font-size: .85rem;
        font-family: inherit;
        transition: border-color .2s, box-shadow .2s, background .2s;
        outline: none;
        box-sizing: border-box;
    }

    .form-input:focus {
        border-color: #16a34a;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(22,163,74,.12);
    }

    .form-input.is-error { border-color: #ef4444; }

    .form-eye {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 42px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: none;
        border: none;
        cursor: pointer;
        color: #94a3b8;
        transition: color .2s;
        padding: 0;
    }

    .form-eye:hover { color: #16a34a; }

    .form-error {
        font-size: .72rem;
        color: #ef4444;
        font-weight: 500;
    }

    .btn-submit {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        border-radius: 10px;
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: #fff;
        font-size: .84rem;
        font-weight: 700;
        font-family: inherit;
        border: none;
        cursor: pointer;
        box-shadow: 0 3px 12px rgba(22,163,74,.28);
        transition: all .22s;
        margin-top: 4px;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #15803d, #166534);
        box-shadow: 0 5px 18px rgba(22,163,74,.38);
        transform: translateY(-1px);
    }

    .btn-submit:active { transform: translateY(0); }

    .strength-bar-wrap {
        display: flex;
        gap: 4px;
        margin-top: 6px;
    }

    .strength-seg {
        height: 3px;
        flex: 1;
        border-radius: 2px;
        background: #e2e8f0;
        transition: background .3s;
    }

    .strength-label {
        font-size: .68rem;
        font-weight: 600;
        color: #94a3b8;
        margin-top: 4px;
        min-height: 14px;
    }

    .pg-tips {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        border-radius: 12px;
        border: 1px solid #fde68a;
        background: #fffbeb;
    }

    .pg-tips-icon { color: #d97706; flex-shrink: 0; margin-top: 1px; }

    .pg-tips-title {
        font-size: .75rem;
        font-weight: 700;
        color: #92400e;
        margin-bottom: 3px;
    }

    .pg-tips-text {
        font-size: .73rem;
        color: #a16207;
        line-height: 1.55;
        margin: 0;
    }
</style>
@endpush

@section('content')
<div class="pg-settings">

    {{-- Flash --}}
    @if(session('success'))
        <div class="flash-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="pg-card">
        <div class="pg-header">
            <div class="pg-header-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                     stroke="#16a34a" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="3"/>
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
            </div>
            <div class="pg-header-body">
                <h1>Pengaturan</h1>
                <p>Kelola keamanan dan preferensi akun Anda.</p>
            </div>
            <a href="{{ route('profile.index') }}" class="pg-header-link">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Profil Saya
            </a>
        </div>
    </div>

    {{-- Form Ganti Password --}}
    <div class="pg-card">
        <div class="pg-section">
            <div class="pg-section-head">
                <div class="pg-section-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>
                <div>
                    <h2>Keamanan Akun</h2>
                    <p>Ganti kata sandi secara berkala untuk keamanan akun.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.password', $user->id) }}" class="pg-form">
                @csrf
                @method('PATCH')

                {{-- Saat Ini --}}
                <div class="form-group">
                    <label class="form-label" for="current_password">Kata Sandi Saat Ini</label>
                    <div class="form-input-wrap">
                        <input id="current_password" type="password" name="current_password"
                               class="form-input @error('current_password') is-error @enderror"
                               placeholder="Masukkan kata sandi saat ini" required>
                        <button type="button" class="form-eye" onclick="togglePwd('current_password','ico-cur')" tabindex="-1">
                            <svg id="ico-cur" xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Baru --}}
                <div class="form-group">
                    <label class="form-label" for="new_password">Kata Sandi Baru</label>
                    <div class="form-input-wrap">
                        <input id="new_password" type="password" name="password"
                               class="form-input @error('password') is-error @enderror"
                               placeholder="Minimal 8 karakter" required minlength="8"
                               oninput="checkStrength(this.value)">
                        <button type="button" class="form-eye" onclick="togglePwd('new_password','ico-new')" tabindex="-1">
                            <svg id="ico-new" xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    <div class="strength-bar-wrap">
                        <div class="strength-seg" id="seg1"></div>
                        <div class="strength-seg" id="seg2"></div>
                        <div class="strength-seg" id="seg3"></div>
                        <div class="strength-seg" id="seg4"></div>
                    </div>
                    <div class="strength-label" id="strength-label"></div>
                    @error('password')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Konfirmasi --}}
                <div class="form-group">
                    <label class="form-label" for="confirm_password">Konfirmasi Kata Sandi Baru</label>
                    <div class="form-input-wrap">
                        <input id="confirm_password" type="password" name="password_confirmation"
                               class="form-input" placeholder="Ulangi kata sandi baru" required>
                        <button type="button" class="form-eye" onclick="togglePwd('confirm_password','ico-con')" tabindex="-1">
                            <svg id="ico-con" xmlns="http://www.w3.org/2000/svg" width="17" height="17"
                                 viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
                                <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                        Ganti Kata Sandi
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Tips --}}
    <div class="pg-tips">
        <svg class="pg-tips-icon" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/>
            <line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
        <div>
            <div class="pg-tips-title">Tips Keamanan</div>
            <p class="pg-tips-text">
                Gunakan kata sandi minimal 8 karakter yang mengandung kombinasi huruf besar, huruf kecil, angka, dan simbol.
                Jangan gunakan kata sandi yang sama di berbagai platform.
            </p>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
const eyeOpen   = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;

function togglePwd(inputId, iconId) {
    const inp = document.getElementById(inputId);
    const ico = document.getElementById(iconId);
    if (!inp || !ico) return;
    const showing = inp.type === 'text';
    inp.type = showing ? 'password' : 'text';
    ico.innerHTML = showing ? eyeClosed : eyeOpen;
}

function checkStrength(val) {
    const segs  = [1,2,3,4].map(i => document.getElementById('seg'+i));
    const label = document.getElementById('strength-label');
    if (!segs[0]) return;

    let score = 0;
    if (val.length >= 8)             score++;
    if (/[A-Z]/.test(val))          score++;
    if (/[0-9]/.test(val))          score++;
    if (/[^A-Za-z0-9]/.test(val))  score++;

    const colors    = ['', '#ef4444', '#f59e0b', '#3b82f6', '#16a34a'];
    const labels    = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];
    const txtColors = ['', '#ef4444', '#d97706', '#2563eb', '#15803d'];

    segs.forEach((seg, i) => {
        seg.style.background = i < score ? colors[score] : '#e2e8f0';
    });
    label.textContent = val.length ? labels[score] : '';
    label.style.color = txtColors[score];
}
</script>
@endpush
