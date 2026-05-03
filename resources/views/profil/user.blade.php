@extends('layouts.app')

@section('title', 'Profil Saya')
@section('breadcrumb', 'Profil Saya')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .profile-wrapper {
        font-family: 'Plus Jakarta Sans', sans-serif;
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 24px;
        padding: 24px;
        min-height: calc(100vh - 80px);
        align-items: start;
        max-width: 1100px;
        margin: 0 auto;
    }

    /* ── LEFT PANEL ── */
    .profile-left {
        display: flex;
        flex-direction: column;
        gap: 16px;
        position: sticky;
        top: 24px;
    }

    .identity-card {
        background: linear-gradient(145deg, #0f4c2a, #16a34a);
        border-radius: 20px;
        padding: 32px 24px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        position: relative;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(22, 163, 74, 0.25);
    }

    .identity-card::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,0.06);
    }

    .identity-card::after {
        content: '';
        position: absolute;
        bottom: -30px; left: -30px;
        width: 120px; height: 120px;
        border-radius: 50%;
        background: rgba(255,255,255,0.04);
    }

    .avatar-ring {
        width: 88px; height: 88px;
        border-radius: 22px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255,255,255,0.3);
        display: flex; align-items: center; justify-content: center;
        font-size: 45px; font-weight: 800; color: #fff;
        letter-spacing: 1px;
        margin-bottom: 16px;
        position: relative; z-index: 1;
    }

    .identity-card h1 {
        font-size: 25px; font-weight: 800;
        color: #fff; margin: 0 0 4px;
        position: relative; z-index: 1;
    }

    .identity-card p {
        font-size: 15px; color: rgba(255,255,255,0.65);
        margin: 0 0 16px;
        position: relative; z-index: 1;
    }

    .role-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,0.18);
        border: 1px solid rgba(255,255,255,0.25);
        color: #fff;
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 15px; font-weight: 700;
        letter-spacing: 0.3px;
        position: relative; z-index: 1;
    }

    .meta-card {
        background: #fff;
        border-radius: 18px;
        padding: 20px 22px;
        border: 1px solid #e8f0e8;
        box-shadow: 0 2px 16px rgba(0,0,0,0.04);
    }

    .meta-card h3 {
        font-size: 15px; font-weight: 700; color: #94a3b8;
        text-transform: uppercase; letter-spacing: 1px;
        margin: 0 0 14px;
    }

    .meta-grid {
        display: grid; grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .meta-item p:first-child {
        font-size: 15px; font-weight: 600; color: #b0bec5;
        text-transform: uppercase; letter-spacing: 0.5px;
        margin: 0 0 3px;
    }

    .meta-item p:last-child {
        font-size: 15px; font-weight: 700; color: #334155;
        margin: 0;
    }

    .status-dot { color: #16a34a; }

    /* ── RIGHT PANEL ── */
    .profile-right {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .flash-msg {
        display: flex; align-items: center; gap: 10px;
        background: #f0fdf4; border: 1px solid #bbf7d0;
        border-radius: 14px; padding: 14px 18px;
        font-size: 15px; font-weight: 600; color: #15803d;
    }

    .form-card {
        background: #fff;
        border-radius: 20px;
        padding: 28px 28px;
        border: 1px solid #e8f0e8;
        box-shadow: 0 2px 16px rgba(0,0,0,0.04);
        flex: 1;
    }

    .form-card-header {
        margin-bottom: 24px;
    }

    .form-card-header h2 {
        font-size: 25px; font-weight: 800; color: #1e293b;
        margin: 0 0 4px;
    }

    .form-card-header p {
        font-size: 17px; color: #94a3b8; margin: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .form-group { display: flex; flex-direction: column; }
    .form-group.full { grid-column: 1 / -1; }

    .form-group label {
        font-size: 15px; font-weight: 700; color: #475569;
        margin-bottom: 7px; letter-spacing: 0.3px;
    }

    .form-group input {
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 11px 15px;
        font-size: 17px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: #1e293b;
        background: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s;
        outline: none;
    }

    .form-group input:focus {
        border-color: #16a34a;
        box-shadow: 0 0 0 3px rgba(22,163,74,0.12);
        background: #fff;
    }

    .form-group input:disabled {
        background: #f1f5f9;
        color: #b0bec5;
        cursor: not-allowed;
    }

    .form-group .hint {
        font-size: 15px; color: #cbd5e1; margin-top: 5px;
    }

    .form-group .err {
        font-size: 15px; color: #ef4444; margin-top: 5px;
    }

    .form-actions {
        margin-top: 22px;
        display: flex; align-items: center; gap: 12px;
    }

    .btn-save {
        display: inline-flex; align-items: center; gap-8px;
        gap: 8px;
        background: linear-gradient(135deg, #16a34a, #15803d);
        color: #fff;
        border: none; cursor: pointer;
        border-radius: 12px;
        padding: 11px 22px;
        font-size: 17px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: opacity 0.2s, transform 0.15s;
        box-shadow: 0 4px 16px rgba(22,163,74,0.3);
    }

    .btn-save:hover { opacity: 0.9; transform: translateY(-1px); }
    .btn-save:active { transform: translateY(0); }

    .saved-note {
        font-size: 15px; color: #94a3b8; font-weight: 500;
    }

    @media (max-width: 768px) {
        .profile-wrapper { grid-template-columns: 1fr; }
        .profile-left { position: static; }
        .form-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="profile-wrapper">

    {{-- ════ LEFT ════ --}}
    <div class="profile-left">

        {{-- Identity Card --}}
        <div class="identity-card">
            <div class="avatar-ring">
                {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
            </div>
            <h1>{{ $user->name }}</h1>
            <p>{{ $user->email }}</p>
            <span class="role-badge">
                @if($user->role === 'superadmin')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    Kepala Pondok
                @elseif($user->role === 'admin')
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    Pengurus
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Staff
                @endif
            </span>
        </div>

        {{-- Account Info --}}
        <div class="meta-card">
            <h3>Informasi Akun</h3>
            <div class="meta-grid">
                <div class="meta-item">
                    <p>ID Pengguna</p>
                    <p>#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div class="meta-item">
                    <p>Status</p>
                    <p class="status-dot">● Aktif</p>
                </div>
                <div class="meta-item">
                    <p>Bergabung</p>
                    <p>{{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}</p>
                </div>
                <div class="meta-item">
                    <p>Diperbarui</p>
                    <p>{{ $user->updated_at ? $user->updated_at->diffForHumans() : '-' }}</p>
                </div>
            </div>
        </div>

    </div>

    {{-- ════ RIGHT ════ --}}
    <div class="profile-right">

        {{-- Flash --}}
        @if(session('success'))
        <div class="flash-msg">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            {{ session('success') }}
        </div>
        @endif

        {{-- Form --}}
        <div class="form-card">
            <div class="form-card-header">
                <h2>Informasi Pribadi</h2>
                <p>Perbarui nama dan email akun Anda.</p>
            </div>

            <form method="POST" action="{{ route('profile.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')<span class="err">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label>Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')<span class="err">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group full">
                        <label>Jabatan</label>
                        <input type="text" value="{{ $user->roleLabel() }}" disabled>
                        <span class="hint">Jabatan tidak dapat diubah sendiri.</span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Simpan Perubahan
                    </button>
                    <span class="saved-note">Perubahan disimpan secara aman</span>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
