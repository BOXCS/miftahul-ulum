@extends('layouts.app')

@section('title', 'Edit Data Santri')
@section('breadcrumb', 'Edit Santri')

@push('styles')
<style>
    :root {
        --teal-50: #e1f5ee;
        --teal-100: #9fe1cb;
        --teal-200: #5dcaa5;
        --teal-400: #1d9e75;
        --teal-600: #0f6e56;
        --teal-800: #085041;
        --teal-900: #04342c;

        --input-radius: 10px;
    }

    .sf-page {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0;
        min-height: 100vh;
        background: #f8faf9;
    }

    .sf-nav {
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        padding: 28px 0 28px 24px;
        border-right: 1px solid #e2ece8;
        background: #fff;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .sf-nav-label {
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #94a3b8;
        padding: 0 0 6px 12px;
        margin-top: 16px;
    }

    .sf-nav-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 12px;
        border-radius: 8px 0 0 8px;
        font-size: 13px;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        transition: background .15s, color .15s;
        text-decoration: none;
        border: none;
        background: transparent;
        text-align: left;
        width: 100%;
    }

    .sf-nav-item:hover {
        background: #f0fdf7;
        color: #0d9488;
    }

    .sf-nav-item.active {
        background: #e1f5ee;
        color: #0d9488;
        font-weight: 600;
    }

    .sf-nav-item .nav-num {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        font-size: 10px;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .sf-nav-item.active .nav-num {
        background: #0d9488;
        color: #fff;
    }

    .sf-nav-item:not(.active) .nav-num {
        background: #e2e8f0;
        color: #64748b;
    }

    .sf-nav-danger .nav-num {
        background: #fee2e2 !important;
        color: #dc2626 !important;
    }

    .sf-nav-danger {
        color: #dc2626 !important;
    }

    .sf-nav-danger:hover {
        background: #fff5f5 !important;
    }

    .sf-main {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    .sf-header {
        padding: 28px 40px 20px;
        background: #fff;
        border-bottom: 1px solid #e2ece8;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }

    .sf-back {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 12px;
        color: #94a3b8;
        text-decoration: none;
        font-weight: 500;
        margin-bottom: 6px;
        transition: color .15s;
    }

    .sf-back:hover {
        color: #0d9488;
    }

    .sf-title {
        font-size: 22px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -.02em;
        line-height: 1.2;
    }

    .sf-subtitle {
        font-size: 13px;
        color: #64748b;
        margin-top: 3px;
    }

    .sf-student-card {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f8faf9;
        border: 1px solid #d1e8de;
        border-radius: 12px;
        padding: 10px 14px;
        flex-shrink: 0;
        margin-top: 4px;
    }

    .sf-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 800;
        flex-shrink: 0;
        background: linear-gradient(135deg, #1d9e75, #0f6e56);
        color: #fff;
    }

    .sf-body {
        flex: 1;
        padding: 0 40px 60px;
        max-width: none;
        width: 100%;
        box-sizing: border-box;
    }

    .sf-section {
        padding: 36px 0;
        border-bottom: 1px solid #e8f0ec;
    }

    .sf-section:last-of-type {
        border-bottom: none;
    }

    .sf-section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .sf-section-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .sf-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #0f172a;
        letter-spacing: -.01em;
    }

    .sf-section-desc {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 1px;
    }

    .sf-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .sf-field {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .sf-label {
        font-size: 12.5px;
        font-weight: 600;
        color: #374151;
        letter-spacing: .01em;
    }

    .sf-label-opt {
        font-size: 11px;
        font-weight: 400;
        color: #94a3b8;
        margin-left: 4px;
    }

    .sf-label-req {
        color: #ef4444;
        margin-left: 2px;
    }

    .sf-input,
    .sf-select,
    .sf-textarea {
        width: 100%;
        padding: 10px 14px;
        font-size: 13.5px;
        color: #0f172a;
        background: #fff;
        border: 1.5px solid #d1e8de;
        border-radius: var(--input-radius);
        outline: none;
        transition: border-color .15s, box-shadow .15s;
        box-sizing: border-box;
        font-family: inherit;
    }

    .sf-input::placeholder,
    .sf-textarea::placeholder {
        color: #c0cdd9;
    }

    .sf-input:focus,
    .sf-select:focus,
    .sf-textarea:focus {
        border-color: #1d9e75;
        box-shadow: 0 0 0 3px rgba(29, 158, 117, .12);
    }

    .sf-input.mono {
        font-family: 'Courier New', monospace;
    }

    .sf-input.is-error,
    .sf-select.is-error,
    .sf-textarea.is-error {
        border-color: #fca5a5;
        background: #fff5f5;
    }

    .sf-input-locked {
        background: #f8faf9 !important;
        color: #94a3b8 !important;
        cursor: not-allowed !important;
        border-color: #e2ece8 !important;
    }

    .sf-textarea {
        resize: vertical;
        min-height: 88px;
    }

    .sf-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        padding-right: 36px;
        cursor: pointer;
    }

    .sf-locked-wrap {
        position: relative;
    }

    .sf-lock-icon {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .sf-phone-wrap {
        position: relative;
    }

    .sf-phone-prefix {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        display: flex;
        align-items: center;
        padding: 0 12px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        border-right: 1.5px solid #d1e8de;
        pointer-events: none;
        border-radius: var(--input-radius) 0 0 var(--input-radius);
        background: #f0fdf7;
    }

    .sf-phone-input {
        padding-left: 64px !important;
    }

    .sf-photo-row {
        display: flex;
        align-items: flex-start;
        gap: 20px;
    }

    .sf-photo-preview {
        position: relative;
        width: 88px;
        height: 88px;
        border-radius: 14px;
        border: 2px dashed #9fe1cb;
        background: #f0fdf7;
        overflow: hidden;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .sf-photo-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .sf-photo-remove {
        position: absolute;
        top: -8px;
        right: -8px;
        width: 22px;
        height: 22px;
        background: #ef4444;
        color: #fff;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .sf-err {
        font-size: 11.5px;
        color: #ef4444;
        font-weight: 500;
        margin-top: 2px;
    }

    .sf-hint {
        font-size: 11.5px;
        color: #94a3b8;
        margin-top: 2px;
        line-height: 1.5;
    }

    /* ── Alert ── */
    .sf-alert-success {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f0fdf7;
        border: 1px solid #9fe1cb;
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 8px;
        font-size: 13px;
        font-weight: 500;
        color: #0f6e56;
    }

    .sf-alert-err {
        background: #fff5f5;
        border: 1px solid #fca5a5;
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        gap: 12px;
        margin-bottom: 8px;
    }

    .sf-alert-err ul {
        margin: 6px 0 0 16px;
        padding: 0;
    }

    .sf-alert-err li {
        font-size: 13px;
        color: #7f1d1d;
        margin-bottom: 2px;
    }

    .sf-alert-err p {
        font-size: 13px;
        font-weight: 600;
        color: #7f1d1d;
        margin: 0;
    }

    /* ── Action bar ── */
    .sf-actions {
        position: sticky;
        bottom: 0;
        background: rgba(255, 255, 255, .95);
        backdrop-filter: blur(8px);
        border-top: 1px solid #e2ece8;
        padding: 14px 40px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        z-index: 10;
    }

    .btn-teal {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 22px;
        background: #0d9488;
        color: #fff;
        border: none;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .2s;
        text-decoration: none;
    }

    .btn-teal:hover {
        background: #0f766e;
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(13, 148, 136, .28);
        color: #fff;
    }

    .btn-teal:active {
        transform: translateY(0);
        box-shadow: none;
    }

    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        background: transparent;
        color: #475569;
        border: 1.5px solid #d1e8de;
        border-radius: 10px;
        font-size: 13.5px;
        font-weight: 500;
        cursor: pointer;
        transition: background .15s, border-color .15s, color .15s;
        text-decoration: none;
    }

    .btn-outline:hover {
        background: #f0fdf7;
        border-color: #9fe1cb;
        color: #0d9488;
    }

    .btn-ghost {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 9px 14px;
        background: transparent;
        color: #64748b;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        cursor: pointer;
        transition: background .15s, color .15s;
    }

    .btn-ghost:hover {
        background: #f1f5f9;
        color: #0d9488;
    }

    /* ── Modal ── */
    .sf-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .4);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 999;
        padding: 20px;
    }

    .sf-modal {
        background: #fff;
        border-radius: 16px;
        width: 100%;
        max-width: 440px;
        overflow: hidden;
    }

    .sf-modal-header {
        padding: 20px 24px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #fecaca;
        background: #fff7f7;
    }

    .sf-modal-body {
        padding: 20px 24px;
    }

    .sf-modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        gap: 8px;
    }

    .btn-icon {
        width: 30px;
        height: 30px;
        background: transparent;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        transition: background .15s, color .15s;
    }

    .btn-icon:hover {
        background: #f1f5f9;
        color: #64748b;
    }

    @media (max-width: 768px) {
        .sf-page {
            grid-template-columns: 1fr;
        }

        .sf-nav {
            display: none;
        }

        .sf-body {
            padding: 0 20px 60px;
        }

        .sf-header {
            padding: 20px;
        }

        .sf-actions {
            padding: 12px 20px;
        }

        .sf-grid-2 {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="sf-page"
    x-data="{
        fotoPreview: null,
        handleFoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => { this.fotoPreview = e.target.result; };
            reader.readAsDataURL(file);
        },
        removeFoto() {
            this.fotoPreview = null;
            document.getElementById('foto_input').value = '';
        }
    }">
    {{-- ── Main ── --}}
    <div class="sf-main">

        {{-- Header --}}
        <div class="sf-header">
            <div>
                <a href="{{ route('students.index') }}" class="sf-back">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                    Kembali ke Data Santri
                </a>
                <div class="sf-title">Edit Data Santri</div>
                <div class="sf-subtitle">
                    Perbarui informasi santri
                    @isset($student)
                    <span style="font-weight:600; color:#0d9488;">{{ $student->nama ?? '' }}</span>
                    @endisset
                </div>
            </div>
            @isset($student)
            <div class="sf-student-card">
                <div class="sf-avatar">
                    {{ strtoupper(substr($student->nama ?? 'S', 0, 1)) }}{{ strtoupper(substr(strstr($student->nama ?? '', ' '), 1, 1)) }}
                </div>
                <div>
                    <div style="font-size:13px;font-weight:700;color:#0f172a;">{{ $student->nama ?? 'Nama Santri' }}</div>
                    <div style="font-size:12px;color:#94a3b8;">NIS: {{ $student->nis ?? '-' }} · Kelas {{ $student->kelas ?? '-' }}</div>
                </div>
            </div>
            @endisset
        </div>

        {{-- Session alerts --}}
        <div style="padding: 16px 40px 0;">
            @if(session('success'))
            <div class="sf-alert-success">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
                {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="sf-alert-err">
                <svg width="18" height="18" style="flex-shrink:0;margin-top:1px;" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10" />
                    <line x1="12" y1="8" x2="12" y2="12" />
                    <line x1="12" y1="16" x2="12.01" y2="16" />
                </svg>
                <div>
                    <p>Terdapat kesalahan pada formulir:</p>
                    <ul>
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

        {{-- Form body --}}
        <div class="sf-body">
            <form id="editForm"
                action="{{ route('students.update', $student->id ?? $id ?? 1) }}"
                method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" id="oldProvinsi" value="{{ old('provinsi', $student->provinsi ?? '') }}">
                <input type="hidden" id="oldKota" value="{{ old('kota', $student->kota ?? '') }}">
                {{-- ── Section 1: Identitas ── --}}
                <div class="sf-section" id="s-identitas">
                    <div class="sf-section-header">
                        <div class="sf-section-icon" style="background:#e1f5ee;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#1d9e75" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                <circle cx="12" cy="7" r="4" />
                            </svg>
                        </div>
                        <div>
                            <div class="sf-section-title">Identitas Santri</div>
                            <div class="sf-section-desc">Edit data identitas dasar santri</div>
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div class="sf-field" style="margin-bottom: 20px;">
                        <label class="sf-label">Foto Santri <span class="sf-label-opt">(Opsional)</span></label>
                        <div class="sf-photo-row">
                            <div class="sf-photo-preview">
                                <template x-if="fotoPreview">
                                    <img :src="fotoPreview" alt="Preview foto santri">
                                </template>
                                <template x-if="!fotoPreview">
                                    @isset($student->foto)
                                    <img src="{{ asset('storage/' . $student->foto) }}" alt="Foto {{ $student->nama ?? '' }}">
                                    @else
                                    <div style="display:flex;flex-direction:column;align-items:center;gap:4px;">
                                        <div class="sf-avatar" style="width:44px;height:44px;font-size:16px;">
                                            {{ strtoupper(substr($student->nama ?? 'S', 0, 1)) }}
                                        </div>
                                    </div>
                                    @endisset
                                </template>
                                <button type="button" x-show="fotoPreview" @click="removeFoto()" class="sf-photo-remove" aria-label="Hapus foto baru">✕</button>
                            </div>
                            <div style="padding-top: 4px;">
                                <label for="foto_input" class="btn-outline" style="cursor:pointer; font-size:13px; padding: 8px 14px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                    Ganti Foto
                                </label>
                                <input id="foto_input" type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="handleFoto($event)">
                                <p class="sf-hint">Format: JPG, PNG, WebP · Maks. 2 MB<br>Biarkan kosong untuk mempertahankan foto saat ini.</p>
                                @error('foto')<p class="sf-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- NIS (readonly) + Nama --}}
                    <div class="sf-grid-2" style="margin-bottom: 16px;">
                        <div class="sf-field">
                            <label for="nis" class="sf-label">NIS</label>
                            <div class="sf-locked-wrap">
                                <input id="nis" type="text" name="nis"
                                    class="sf-input mono sf-input-locked"
                                    value="{{ old('nis', $student->nis ?? '') }}"
                                    readonly>
                                <span class="sf-lock-icon">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="11" width="18" height="11" rx="2" />
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                                    </svg>
                                </span>
                            </div>
                            <p class="sf-hint">NIS tidak dapat diubah</p>
                        </div>
                        <div class="sf-field">
                            <label for="nama" class="sf-label">Nama Lengkap <span class="sf-label-req">*</span></label>
                            <input id="nama" type="text" name="nama"
                                class="sf-input @error('nama') is-error @enderror"
                                placeholder="Nama lengkap sesuai akta"
                                value="{{ old('nama', $student->nama ?? '') }}"
                                required autocomplete="off">
                            @error('nama')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Kelas + JK --}}
                    <div class="sf-grid-2" style="margin-bottom: 16px;">
                        <div class="sf-field">
                            <label for="kelas" class="sf-label">Kelas <span class="sf-label-req">*</span></label>
                            <select id="kelas" name="kelas" class="sf-select @error('kelas') is-error @enderror" required>
                                <option value="" disabled>Pilih kelas</option>
                                @foreach(['7A','7B','8A','8B','9A','9B'] as $kelas)
                                <option value="{{ $kelas }}" @selected(old('kelas', $student->kelas ?? '') === $kelas)>Kelas {{ $kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="sf-field">
                            <label for="jenis_kelamin" class="sf-label">Jenis Kelamin <span class="sf-label-req">*</span></label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="sf-select @error('jenis_kelamin') is-error @enderror" required>
                                <option value="" disabled>Pilih jenis kelamin</option>
                                <option value="Laki-laki" @selected(old('jenis_kelamin', $student->jenis_kelamin ?? '') === 'Laki-laki')>Laki-laki</option>
                                <option value="Perempuan" @selected(old('jenis_kelamin', $student->jenis_kelamin ?? '') === 'Perempuan')>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Tanggal + Tempat Lahir --}}
                    <div class="sf-grid-2">
                        <div class="sf-field">
                            <label for="tanggal_lahir" class="sf-label">Tanggal Lahir <span class="sf-label-req">*</span></label>
                            <input id="tanggal_lahir" type="date" name="tanggal_lahir"
                                class="sf-input @error('tanggal_lahir') is-error @enderror"
                                value="{{ old('tanggal_lahir', isset($student->tanggal_lahir) ? \Carbon\Carbon::parse($student->tanggal_lahir)->format('Y-m-d') : '') }}"
                                required>
                            @error('tanggal_lahir')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="sf-field">
                            <label for="tempat_lahir" class="sf-label">Tempat Lahir <span class="sf-label-req">*</span></label>
                            <input id="tempat_lahir" type="text" name="tempat_lahir"
                                class="sf-input @error('tempat_lahir') is-error @enderror"
                                placeholder="Kota tempat lahir"
                                value="{{ old('tempat_lahir', $student->tempat_lahir ?? '') }}"
                                required autocomplete="off">
                            @error('tempat_lahir')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>{{-- end identitas --}}


                {{-- ── Section 2: Alamat ── --}}
                <div class="sf-section" id="s-alamat">
                    <div class="sf-section-header">
                        <div class="sf-section-icon" style="background:#dbeafe;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                        </div>
                        <div>
                            <div class="sf-section-title">Alamat &amp; Kontak</div>
                            <div class="sf-section-desc">Perbarui alamat dan informasi kontak</div>
                        </div>
                    </div>

                    <div class="sf-field" style="margin-bottom: 16px;">
                        <label for="alamat" class="sf-label">Alamat Lengkap <span class="sf-label-req">*</span></label>
                        <textarea id="alamat" name="alamat" class="sf-textarea @error('alamat') is-error @enderror"
                            placeholder="Jl. Contoh No. 1, RT 01/RW 01, Desa/Kel., Kecamatan…" required>{{ old('alamat', $student->alamat ?? '') }}</textarea>
                        @error('alamat')<p class="sf-err">{{ $message }}</p>@enderror
                    </div>

                    <div class="sf-grid-2" style="margin-bottom: 16px;">
                        <div class="sf-field">
                            <label for="provinsi" class="sf-label">Provinsi</label>
                            <select id="provinsi" name="provinsi"
                                class="sf-select @error('provinsi') is-error @enderror">
                                <option value="">Memuat provinsi...</option>
                            </select>
                            @error('provinsi')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>

                        <div class="sf-field">
                            <label for="kota" class="sf-label">Kabupaten / Kota</label>
                            <select id="kota" name="kota"
                                class="sf-select @error('kota') is-error @enderror"
                                disabled>
                                <option value="">Pilih provinsi dahulu</option>
                            </select>
                            @error('kota')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="sf-field">
                        <label for="no_hp_wali" class="sf-label">No. HP Wali <span class="sf-label-opt">(Opsional)</span></label>
                        <div class="sf-phone-wrap">
                            <span class="sf-phone-prefix">+62</span>
                            <input id="no_hp_wali" type="tel" name="no_hp_wali"
                                class="sf-input sf-phone-input @error('no_hp_wali') is-error @enderror"
                                placeholder="812 3456 7890"
                                value="{{ old('no_hp_wali', $student->no_hp_wali ?? '') }}" autocomplete="off">
                        </div>
                        @error('no_hp_wali')<p class="sf-err">{{ $message }}</p>@enderror
                    </div>
                </div>{{-- end alamat --}}


                {{-- ── Section 3: Informasi Tambahan ── --}}
                <div class="sf-section" id="s-info">
                    <div class="sf-section-header">
                        <div class="sf-section-icon" style="background:#ede9fe;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="12" y1="8" x2="12" y2="12" />
                                <line x1="12" y1="16" x2="12.01" y2="16" />
                            </svg>
                        </div>
                        <div>
                            <div class="sf-section-title">Informasi Tambahan</div>
                            <div class="sf-section-desc">Status dan catatan santri</div>
                        </div>
                    </div>

                    <div class="sf-grid-2" style="margin-bottom: 16px;">
                        <div class="sf-field">
                            <label for="status" class="sf-label">Status <span class="sf-label-req">*</span></label>
                            <select id="status" name="status" class="sf-select @error('status') is-error @enderror" required>
                                <option value="Aktif" @selected(old('status', $student->status ?? 'Aktif') === 'Aktif')>Aktif</option>
                                <option value="Cuti" @selected(old('status', $student->status ?? '') === 'Cuti')>Cuti</option>
                                <option value="Keluar" @selected(old('status', $student->status ?? '') === 'Keluar')>Keluar</option>
                            </select>
                            @error('status')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="sf-field">
                            <label for="tahun_masuk" class="sf-label">Tahun Masuk</label>
                            <input id="tahun_masuk" type="number" name="tahun_masuk"
                                class="sf-input @error('tahun_masuk') is-error @enderror"
                                placeholder="{{ date('Y') }}"
                                value="{{ old('tahun_masuk', $student->tahun_masuk ?? date('Y')) }}"
                                min="2000" max="{{ date('Y') }}">
                            @error('tahun_masuk')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="sf-field">
                        <label for="catatan" class="sf-label">Catatan <span class="sf-label-opt">(Opsional)</span></label>
                        <textarea id="catatan" name="catatan" class="sf-textarea"
                            placeholder="Catatan khusus tentang santri…">{{ old('catatan', $student->catatan ?? '') }}</textarea>
                    </div>
                </div>{{-- end info --}}

            </form>

        </div>{{-- end sf-body --}}

        {{-- ── Sticky action bar ── --}}
        <div class="sf-actions">
            <a href="{{ route('students.index') }}" class="btn-outline">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12" />
                    <polyline points="12 19 5 12 12 5" />
                </svg>
                Batal
            </a>
            <div style="display:flex; align-items:center; gap:10px;">
                <button type="button" class="btn-ghost" onclick="window.location.reload()">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10" />
                        <path d="M3.51 15a9 9 0 1 0 .49-3.72" />
                    </svg>
                    Reset
                </button>
                <button type="submit" form="editForm" class="btn-teal">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </div>{{-- end sf-main --}}
</div>
@endsection
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const provinsiSelect = document.getElementById('provinsi');
        const kotaSelect = document.getElementById('kota');

        if (!provinsiSelect || !kotaSelect) return;

        const oldProvinsi = document.getElementById('oldProvinsi')?.value || '';
        const oldKota = document.getElementById('oldKota')?.value || '';

        async function fetchJson(url) {
            const response = await fetch(url, {
                cache: 'no-store'
            });

            if (!response.ok) {
                throw new Error(`Gagal mengambil data: ${url}`);
            }

            return response.json();
        }

        function sama(a, b) {
            return String(a || '').trim().toLowerCase() === String(b || '').trim().toLowerCase();
        }

        function resetKota(text = 'Pilih provinsi dahulu') {
            kotaSelect.innerHTML = `<option value="">${text}</option>`;
            kotaSelect.disabled = true;
        }

        async function loadProvinsi() {
            try {
                provinsiSelect.innerHTML = '<option value="">Memuat provinsi...</option>';
                resetKota();

                const dataProvinsi = await fetchJson('/data-wilayah/provinces.json');

                provinsiSelect.innerHTML = '<option value="">Pilih provinsi</option>';

                dataProvinsi.forEach((provinsi) => {
                    const option = document.createElement('option');
                    option.value = provinsi.name;
                    option.textContent = provinsi.name;
                    option.dataset.kode = provinsi.id;

                    if (sama(oldProvinsi, provinsi.name)) {
                        option.selected = true;
                    }

                    provinsiSelect.appendChild(option);
                });

                if (oldProvinsi) {
                    await loadKabupatenKota(true);
                }
            } catch (error) {
                console.error(error);
                provinsiSelect.innerHTML = '<option value="">Gagal memuat provinsi</option>';
                resetKota('Gagal memuat kabupaten/kota');
            }
        }

        async function loadKabupatenKota(useOldValue = false) {
            const selectedOption = provinsiSelect.options[provinsiSelect.selectedIndex];
            const kodeProvinsi = selectedOption?.dataset?.kode;

            if (!kodeProvinsi) {
                resetKota();
                return;
            }

            try {
                kotaSelect.disabled = true;
                kotaSelect.innerHTML = '<option value="">Memuat kabupaten/kota...</option>';

                const dataKota = await fetchJson(`/data-wilayah/regencies/${kodeProvinsi}.json`);

                kotaSelect.innerHTML = '<option value="">Pilih kabupaten/kota</option>';

                dataKota.forEach((kota) => {
                    const option = document.createElement('option');
                    option.value = kota.name;
                    option.textContent = kota.name;
                    option.dataset.kode = kota.id;

                    if (useOldValue && sama(oldKota, kota.name)) {
                        option.selected = true;
                    }

                    kotaSelect.appendChild(option);
                });

                kotaSelect.disabled = false;
            } catch (error) {
                console.error(error);
                kotaSelect.innerHTML = '<option value="">Data kabupaten/kota belum tersedia</option>';
                kotaSelect.disabled = true;
            }
        }

        provinsiSelect.addEventListener('change', () => {
            loadKabupatenKota(false);
        });

        loadProvinsi();
    });
</script>
@endpush