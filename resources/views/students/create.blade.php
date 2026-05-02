@extends('layouts.app')

@section('title', 'Tambah Santri Baru')
@section('breadcrumb', 'Tambah Santri')

@push('styles')
<style>
    /* ── Teal palette variables ── */
    :root {
        --teal-50: #e1f5ee;
        --teal-100: #9fe1cb;
        --teal-200: #5dcaa5;
        --teal-400: #1d9e75;
        --teal-600: #0f6e56;
        --teal-800: #085041;
        --teal-900: #04342c;

        --form-bg: #ffffff;
        --section-gap: 0;
        --input-radius: 10px;
        --card-radius: 0px;
    }

    /* ── Page layout: full-bleed, no card container ── */
    .sf-page {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0;
        min-height: 100vh;
        background: #f8faf9;
    }

    /* ── Main content area ── */
    .sf-main {
        display: flex;
        flex-direction: column;
        width: 100%;
    }

    /* ── Header bar ── */
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

    .sf-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: #e1f5ee;
        color: #0f6e56;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        flex-shrink: 0;
        margin-top: 4px;
    }

    .sf-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #1d9e75;
    }

    /* ── Form body ── */
    .sf-body {
        flex: 1;
        padding: 0 40px 60px;
        max-width: none;
        width: 100%;
        box-sizing: border-box;
    }

    /* ── Sections ── */
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

    /* ── Field layout ── */
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

    /* ── Inputs ── */
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
        background: #f8faf9;
        color: #94a3b8;
        cursor: not-allowed;
        border-color: #e2ece8;
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

    /* phone input */
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
        user-select: none;
        border-radius: var(--input-radius) 0 0 var(--input-radius);
        background: #f0fdf7;
    }

    .sf-phone-input {
        padding-left: 64px !important;
    }

    /* ── Foto upload ── */
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

    .sf-photo-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }

    .sf-photo-placeholder svg {
        color: #9fe1cb;
    }

    .sf-photo-placeholder span {
        font-size: 10px;
        color: #9fe1cb;
        font-weight: 600;
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
        transition: background .15s;
    }

    .sf-photo-remove:hover {
        background: #dc2626;
    }

    /* ── Error text ── */
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

    /* ── Validation alert ── */
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

    /* ── Buttons ── */
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


    {{-- ── Main area ── --}}
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
                <div class="sf-title">Tambah Santri Baru</div>
                <div class="sf-subtitle">Isi formulir berikut untuk mendaftarkan santri baru</div>
            </div>
            <span class="sf-badge">
                <span class="sf-badge-dot"></span>
                Formulir Pendaftaran
            </span>
        </div>

        {{-- Form body --}}
        <div class="sf-body">
            <form action="{{ route('students.store') }}"
                method="POST"
                enctype="multipart/form-data"
                x-ref="form">
                @csrf

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
                            <div class="sf-section-desc">Data identitas dasar santri</div>
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
                                    <div class="sf-photo-placeholder">
                                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" />
                                            <circle cx="8.5" cy="8.5" r="1.5" />
                                            <polyline points="21 15 16 10 5 21" />
                                        </svg>
                                        <span>Foto</span>
                                    </div>
                                </template>
                                <button type="button" x-show="fotoPreview" @click="removeFoto()" class="sf-photo-remove" aria-label="Hapus foto">✕</button>
                            </div>
                            <div style="padding-top: 4px;">
                                <label for="foto_input" class="btn-outline" style="cursor:pointer; font-size:13px; padding: 8px 14px;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                    Pilih Foto
                                </label>
                                <input id="foto_input" type="file" name="foto" accept="image/jpeg,image/png,image/webp" class="sr-only" @change="handleFoto($event)">
                                <p class="sf-hint">Format: JPG, PNG, WebP · Maks. 2 MB</p>
                                @error('foto')<p class="sf-err">{{ $message }}</p>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- NIS + Nama --}}
                    <div class="sf-grid-2" style="margin-bottom: 16px;">
                        <div class="sf-field">
                            <label for="nis" class="sf-label">NIS <span class="sf-label-req">*</span></label>
                            <input id="nis" type="text" name="nis"
                                class="sf-input mono @error('nis') is-error @enderror"
                                placeholder="Contoh: 2024001"
                                value="{{ old('nis') }}" required autocomplete="off">
                            @error('nis')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="sf-field">
                            <label for="nama" class="sf-label">Nama Lengkap <span class="sf-label-req">*</span></label>
                            <input id="nama" type="text" name="nama"
                                class="sf-input @error('nama') is-error @enderror"
                                placeholder="Nama lengkap sesuai akta"
                                value="{{ old('nama') }}" required autocomplete="off">
                            @error('nama')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- Kelas + JK --}}
                    <div class="sf-grid-2" style="margin-bottom: 16px;">
                        <div class="sf-field">
                            <label for="kelas" class="sf-label">Kelas <span class="sf-label-req">*</span></label>
                            <select id="kelas" name="kelas" class="sf-select @error('kelas') is-error @enderror" required>
                                <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>Pilih kelas</option>
                                @foreach (['7A','7B','8A','8B','9A','9B'] as $kelas)
                                <option value="{{ $kelas }}" {{ old('kelas') === $kelas ? 'selected' : '' }}>Kelas {{ $kelas }}</option>
                                @endforeach
                            </select>
                            @error('kelas')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="sf-field">
                            <label for="jenis_kelamin" class="sf-label">Jenis Kelamin <span class="sf-label-req">*</span></label>
                            <select id="jenis_kelamin" name="jenis_kelamin" class="sf-select @error('jenis_kelamin') is-error @enderror" required>
                                <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>Pilih jenis kelamin</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                                value="{{ old('tanggal_lahir') }}" required>
                            @error('tanggal_lahir')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="sf-field">
                            <label for="tempat_lahir" class="sf-label">Tempat Lahir <span class="sf-label-req">*</span></label>
                            <input id="tempat_lahir" type="text" name="tempat_lahir"
                                class="sf-input @error('tempat_lahir') is-error @enderror"
                                placeholder="Kota tempat lahir"
                                value="{{ old('tempat_lahir') }}" required autocomplete="off">
                            @error('tempat_lahir')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>{{-- end identitas --}}


                {{-- ── Section 2: Alamat & Kontak ── --}}
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
                            <div class="sf-section-desc">Alamat tempat tinggal dan informasi kontak</div>
                        </div>
                    </div>

                    <div class="sf-field" style="margin-bottom: 16px;">
                        <label for="alamat" class="sf-label">Alamat Lengkap <span class="sf-label-req">*</span></label>
                        <textarea id="alamat" name="alamat" class="sf-textarea @error('alamat') is-error @enderror"
                            placeholder="Jl. Contoh No. 1, RT 01/RW 01, Desa/Kel., Kecamatan…" required>{{ old('alamat') }}</textarea>
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
                                value="{{ old('no_hp_wali') }}" autocomplete="off">
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
                                <option value="Aktif" {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="Cuti" {{ old('status') === 'Cuti' ? 'selected' : '' }}>Cuti</option>
                                <option value="Keluar" {{ old('status') === 'Keluar' ? 'selected' : '' }}>Keluar</option>
                            </select>
                            @error('status')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="sf-field">
                            <label for="tahun_masuk" class="sf-label">Tahun Masuk</label>
                            <input id="tahun_masuk" type="number" name="tahun_masuk"
                                class="sf-input @error('tahun_masuk') is-error @enderror"
                                placeholder="{{ date('Y') }}"
                                value="{{ old('tahun_masuk', date('Y')) }}"
                                min="2000" max="{{ date('Y') }}">
                            @error('tahun_masuk')<p class="sf-err">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="sf-field">
                        <label for="catatan" class="sf-label">Catatan <span class="sf-label-opt">(Opsional)</span></label>
                        <textarea id="catatan" name="catatan" class="sf-textarea"
                            placeholder="Catatan khusus tentang santri…">{{ old('catatan') }}</textarea>
                    </div>
                </div>{{-- end info --}}


                {{-- Error alert --}}
                @if ($errors->any())
                <div class="sf-alert-err" style="margin-top: 8px;">
                    <svg width="18" height="18" style="flex-shrink:0;margin-top:1px;" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="12" y1="8" x2="12" y2="12" />
                        <line x1="12" y1="16" x2="12.01" y2="16" />
                    </svg>
                    <div>
                        <p>Terdapat kesalahan pada formulir:</p>
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

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
                <button type="reset" form="studentForm" class="btn-ghost"
                    @click="fotoPreview = null">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10" />
                        <path d="M3.51 15a9 9 0 1 0 .49-3.72" />
                    </svg>
                    Reset
                </button>
                <button type="submit" form="studentForm" class="btn-teal"
                    onclick="document.querySelector('[x-ref=form]').submit()">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Simpan Santri
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