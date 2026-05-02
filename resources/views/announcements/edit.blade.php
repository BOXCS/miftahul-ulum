@extends('layouts.app')

@section('title', 'Edit Pengumuman')
@section('breadcrumb', 'Pengumuman / Edit')

@push('styles')
<style>
    :root {
        --sh-teal: 0 8px 28px rgba(13, 148, 136, .22);
        --sh-teal-sm: 0 4px 14px rgba(13, 148, 136, .18);
    }

    .btn-teal {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0d9488, #0f766e);
        color: #fff;
        font-size: .825rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        text-decoration: none;
        box-shadow: var(--sh-teal-sm);
        transition: all .25s ease;
        font-family: inherit;
    }

    .btn-teal:hover {
        background: linear-gradient(135deg, #0f766e, #115e59);
        transform: translateY(-2px);
        box-shadow: var(--sh-teal);
        color: #fff;
    }

    .btn-secondary-outline {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 12px;
        background: #fff;
        color: #475569;
        font-size: .825rem;
        font-weight: 700;
        border: 1.5px solid #e2e8f0;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
        font-family: inherit;
    }

    .btn-secondary-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #334155;
    }

    .btn-danger-outline {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 16px;
        border-radius: 12px;
        background: #fff;
        color: #e11d48;
        font-size: .825rem;
        font-weight: 700;
        border: 1.5px solid #fecaca;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
        font-family: inherit;
    }

    .btn-danger-outline:hover {
        background: #fff1f2;
        border-color: #fca5a5;
    }

    .form-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .form-card-hdr {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 20px 24px 16px;
        border-bottom: 1px solid #f1f5f9;
    }

    .form-card-body {
        padding: 24px;
    }

    .form-card-ftr {
        padding: 16px 24px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
    }

    .form-section-title {
        font-size: .68rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .1em;
        margin-bottom: 14px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-size: .72rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 7px;
    }

    .form-label .req {
        color: #e11d48;
        margin-left: 2px;
    }

    .form-control {
        width: 100%;
        padding: 10px 14px;
        border-radius: 11px;
        border: 1.5px solid #e2e8f0;
        font-size: .85rem;
        font-family: inherit;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        transition: border .2s, box-shadow .2s, background .2s;
        box-sizing: border-box;
    }

    .form-control:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);
        background: #fff;
    }

    .form-control.is-invalid {
        border-color: #e11d48;
        box-shadow: 0 0 0 3px rgba(225, 29, 72, .1);
    }

    textarea.form-control {
        resize: vertical;
        min-height: 140px;
        line-height: 1.6;
    }

    .form-select {
        width: 100%;
        padding: 10px 36px 10px 14px;
        border-radius: 11px;
        border: 1.5px solid #e2e8f0;
        font-size: .85rem;
        font-family: inherit;
        color: #1e293b;
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none;
        outline: none;
        transition: border .2s, box-shadow .2s, background .2s;
        box-sizing: border-box;
        cursor: pointer;
    }

    .form-select:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);
        background-color: #fff;
    }

    .invalid-feedback {
        font-size: .72rem;
        color: #e11d48;
        margin-top: 5px;
        font-weight: 600;
    }

    .form-hint {
        font-size: .7rem;
        color: #94a3b8;
        margin-top: 5px;
    }

    /* Toggle switch */
    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1.5px solid #e2e8f0;
        cursor: pointer;
        transition: border .2s;
        user-select: none;
    }

    .toggle-wrap:hover {
        border-color: #cbd5e1;
    }

    .toggle-wrap.checked {
        border-color: #0d9488;
        background: #f0fdfa;
    }

    .toggle-switch {
        position: relative;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }

    .toggle-switch input {
        display: none;
    }

    .toggle-slider {
        position: absolute;
        inset: 0;
        border-radius: 999px;
        background: #cbd5e1;
        transition: background .2s;
        cursor: pointer;
    }

    .toggle-slider::before {
        content: '';
        position: absolute;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: #fff;
        top: 3px;
        left: 3px;
        transition: transform .2s cubic-bezier(.22, .68, 0, 1.2);
        box-shadow: 0 1px 4px rgba(0, 0, 0, .2);
    }

    input:checked+.toggle-slider {
        background: #0d9488;
    }

    input:checked+.toggle-slider::before {
        transform: translateX(20px);
    }

    .kategori-preview {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 999px;
        font-size: .75rem;
        font-weight: 700;
        margin-top: 8px;
        transition: all .2s;
    }

    .char-counter {
        font-size: .68rem;
        color: #94a3b8;
        text-align: right;
        margin-top: 4px;
        font-weight: 600;
    }

    /* Preview sidebar */
    .preview-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        overflow: hidden;
        position: sticky;
        top: 20px;
    }

    .preview-card-hdr {
        padding: 14px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: .72rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .08em;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .preview-card-body {
        padding: 18px;
    }

    .preview-announcement {
        border-radius: 14px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }

    .preview-ann-bar {
        height: 4px;
        width: 100%;
    }

    .preview-ann-content {
        padding: 14px 16px;
    }

    /* Meta card */
    .meta-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        overflow: hidden;
        margin-bottom: 16px;
    }

    .meta-card-hdr {
        padding: 12px 18px;
        border-bottom: 1px solid #f1f5f9;
        font-size: .72rem;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .08em;
    }

    .meta-card-body {
        padding: 16px 18px;
    }

    .meta-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f8fafc;
        font-size: .78rem;
    }

    .meta-row:last-child {
        border-bottom: none;
    }

    .meta-lbl {
        color: #94a3b8;
        font-weight: 600;
    }

    .meta-val {
        color: #334155;
        font-weight: 700;
        text-align: right;
    }

    /* Modal */
    .smodal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, .5);
        backdrop-filter: blur(5px);
        z-index: 9900;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        opacity: 0;
        pointer-events: none;
        transition: opacity .25s ease;
    }

    .smodal-overlay.open {
        opacity: 1;
        pointer-events: all;
    }

    .smodal-box {
        background: #fff;
        border-radius: 22px;
        width: 100%;
        box-shadow: 0 24px 64px rgba(15, 23, 42, .18);
        transform: translateY(22px) scale(.96);
        transition: transform .28s cubic-bezier(.22, .68, 0, 1.2);
        overflow: hidden;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
    }

    .smodal-overlay.open .smodal-box {
        transform: translateY(0) scale(1);
    }

    .smodal-body {
        overflow-y: auto;
        flex: 1;
        padding: 32px 24px 20px;
        text-align: center;
    }

    .smodal-ftr {
        padding: 14px 24px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        background: #f8fafc;
    }

    .status-pill {
        font-size: .72rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
    }

    .status-pill.active {
        background: #dcfce7;
        color: #15803d;
    }

    .status-pill.draft {
        background: #f1f5f9;
        color: #475569;
    }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
    <div>
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
            <a href="{{ route('announcements.index') }}" style="display:flex;align-items:center;gap:5px;font-size:.78rem;font-weight:700;color:#64748b;text-decoration:none;transition:color .2s;" onmouseover="this.style.color='#0d9488'" onmouseout="this.style.color='#64748b'">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6" />
                </svg>
                Kembali
            </a>
            <span style="color:#e2e8f0;font-size:.8rem;">/</span>
            <span style="font-size:.78rem;color:#94a3b8;">Edit Pengumuman</span>
        </div>
        <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Edit Pengumuman</h1>
        <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Perbarui informasi pengumuman</p>
    </div>
    {{-- ID badge --}}
    <div style="padding:8px 16px;background:#f0fdfa;border:1px solid #99f6e4;border-radius:12px;">
        <div style="font-size:.65rem;font-weight:800;color:#0d9488;text-transform:uppercase;letter-spacing:.08em;">ID Pengumuman</div>
        <div style="font-size:1rem;font-weight:800;color:#0f172a;">#{{ str_pad($announcement->id, 4, '0', STR_PAD_LEFT) }}</div>
    </div>
</div>

@if ($errors->any())
<div style="display:flex;align-items:flex-start;gap:12px;padding:14px 18px;background:#fff7f7;border:1px solid #fecaca;border-radius:14px;margin-bottom:20px;">
    <div style="width:32px;height:32px;border-radius:10px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <line x1="15" y1="9" x2="9" y2="15" />
            <line x1="9" y1="9" x2="15" y2="15" />
        </svg>
    </div>
    <div style="flex:1;">
        <p style="font-size:.82rem;font-weight:700;color:#dc2626;">Terdapat kesalahan pada form</p>
        <ul style="margin:6px 0 0 16px;font-size:.75rem;color:#b91c1c;line-height:1.7;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div x-data="{
    judul: '{{ old('judul', $announcement->judul) }}',
    kategori: '{{ old('kategori', $announcement->kategori) }}',
    konten: @js(old('konten', $announcement->konten)),
    publish: {{ old('publish', $announcement->is_published ? '1' : '0') == '1' ? 'true' : 'false' }},
    deleteModal: false,
    get kontenLen() { return this.konten.length; },
    get judulLen()  { return this.judul.length; },
    kategoriLabel(k) {
        const m = { umum: 'Umum', kegiatan: 'Kegiatan', akademik: 'Akademik', darurat: 'Darurat ⚠️' };
        return m[k] || k;
    },
    kategoriStyle(k) {
        const m = {
            umum:     'background:#dbeafe;color:#1d4ed8',
            kegiatan: 'background:#ccfbf1;color:#0f766e',
            akademik: 'background:#ede9fe;color:#6d28d9',
            darurat:  'background:#fee2e2;color:#dc2626',
        };
        return m[k] || 'background:#f1f5f9;color:#475569';
    },
    barColor(k) {
        const m = { umum: '#3b82f6', kegiatan: '#0d9488', akademik: '#7c3aed', darurat: '#dc2626' };
        return m[k] || '#94a3b8';
    },
    submitDelete() {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '/announcements/{{ $announcement->id }}';
        const t = document.createElement('input'); t.type='hidden'; t.name='_token'; t.value=document.querySelector('meta[name=csrf-token]').content; f.appendChild(t);
        const m = document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='DELETE'; f.appendChild(m);
        document.body.appendChild(f); f.submit();
    }
}" style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

    {{-- MAIN FORM --}}
    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-card">
            <div class="form-card-hdr">
                <div style="width:38px;height:38px;border-radius:11px;background:#fef3c7;color:#b45309;display:flex;align-items:center;justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:.95rem;font-weight:800;color:#0f172a;">Edit Pengumuman</div>
                    <div style="font-size:.72rem;color:#94a3b8;margin-top:1px;">Perbarui informasi yang diperlukan lalu simpan</div>
                </div>
            </div>

            <div class="form-card-body">
                <div class="form-section-title">Detail Pengumuman</div>

                {{-- Judul --}}
                <div class="form-group">
                    <label class="form-label">Judul <span class="req">*</span></label>
                    <input type="text" name="judul" x-model="judul" maxlength="150"
                        class="form-control {{ $errors->has('judul') ? 'is-invalid' : '' }}"
                        placeholder="Masukkan judul pengumuman..." required>
                    <div class="char-counter"><span x-text="judulLen"></span>/150</div>
                    @error('judul')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kategori --}}
                <div class="form-group">
                    <label class="form-label">Kategori <span class="req">*</span></label>
                    <select name="kategori" x-model="kategori"
                        class="form-select {{ $errors->has('kategori') ? 'is-invalid' : '' }}" required>
                        <option value="umum">Umum</option>
                        <option value="kegiatan">Kegiatan</option>
                        <option value="akademik">Akademik</option>
                        <option value="darurat">Darurat</option>
                    </select>
                    <div>
                        <span class="kategori-preview" :style="kategoriStyle(kategori)" x-text="'Kategori: ' + kategoriLabel(kategori)"></span>
                    </div>
                    @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Konten --}}
                <div class="form-group">
                    <label class="form-label">Konten <span class="req">*</span></label>
                    <textarea name="konten" x-model="konten" maxlength="5000"
                        class="form-control {{ $errors->has('konten') ? 'is-invalid' : '' }}"
                        placeholder="Tuliskan isi pengumuman secara lengkap dan jelas..." required></textarea>
                    <div class="char-counter"><span x-text="kontenLen"></span>/5000</div>
                    <div class="form-hint">Tulis informasi yang jelas, padat, dan mudah dipahami oleh santri dan wali.</div>
                    @error('konten')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-section-title" style="margin-top:24px;">Pengaturan Publikasi</div>

                {{-- Publish Toggle --}}
                <div class="form-group">
                    <label class="form-label">Status Publikasi</label>
                    <label class="toggle-wrap" :class="{ checked: publish }">
                        <div class="toggle-switch">
                            <input type="checkbox" name="publish" value="1" x-model="publish">
                            <div class="toggle-slider"></div>
                        </div>
                        <div>
                            <div style="font-size:.85rem;font-weight:700;color:#1e293b;" x-text="publish ? 'Publikasikan Sekarang' : 'Simpan sebagai Draft'"></div>
                            <div style="font-size:.72rem;color:#64748b;margin-top:2px;" x-text="publish ? 'Pengumuman terlihat oleh santri dan wali' : 'Pengumuman disimpan, belum tampil ke publik'"></div>
                        </div>
                        <div style="margin-left:auto;">
                            <span style="font-size:.68rem;font-weight:800;padding:3px 10px;border-radius:999px;transition:all .2s;"
                                :style="publish ? 'background:#dcfce7;color:#15803d' : 'background:#f1f5f9;color:#475569'"
                                x-text="publish ? 'AKTIF' : 'DRAFT'"></span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="form-card-ftr">
                <button type="button" class="btn-danger-outline" @click="deleteModal = true">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                        <path d="M10 11v6" />
                        <path d="M14 11v6" />
                        <path d="M9 6V4h6v2" />
                    </svg>
                    Hapus
                </button>
                <div style="display:flex;gap:10px;">
                    <a href="{{ route('announcements.index') }}" class="btn-secondary-outline">Batal</a>
                    <button type="submit" class="btn-teal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>

        {{-- MODAL HAPUS --}}
        <div class="smodal-overlay" :class="{ open: deleteModal }" @click.self="deleteModal=false">
            <div class="smodal-box" style="max-width:420px;">
                <div class="smodal-body">
                    <div style="width:60px;height:60px;border-radius:18px;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                            <path d="M10 11v6" />
                            <path d="M14 11v6" />
                            <path d="M9 6V4h6v2" />
                        </svg>
                    </div>
                    <div style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">Hapus Pengumuman?</div>
                    <p style="font-size:.82rem;color:#64748b;margin-bottom:14px;">Tindakan ini tidak dapat dibatalkan. Pengumuman berikut akan dihapus permanen:</p>
                    <div style="padding:12px 14px;background:#fff7f7;border:1px solid #fecaca;border-radius:12px;text-align:left;">
                        <div style="font-size:.85rem;font-weight:700;color:#0f172a;">{{ $announcement->judul }}</div>
                        <div style="font-size:.75rem;color:#64748b;margin-top:3px;">{{ $announcement->kategori }}</div>
                    </div>
                </div>
                <div class="smodal-ftr">
                    <button type="button" @click="deleteModal=false"
                        style="padding:8px 18px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.8rem;font-weight:700;color:#475569;cursor:pointer;font-family:inherit;">
                        Batal
                    </button>
                    <button type="button" @click="submitDelete()"
                        style="padding:8px 20px;border-radius:10px;background:#e11d48;color:#fff;font-size:.8rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6" />
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                        </svg>
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- SIDEBAR --}}
    <div>
        {{-- Meta info --}}
        <div class="meta-card">
            <div class="meta-card-hdr">Informasi Pengumuman</div>
            <div class="meta-card-body">
                <div class="meta-row">
                    <span class="meta-lbl">ID</span>
                    <span class="meta-val">#{{ str_pad($announcement->id, 4, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-lbl">Dibuat</span>
                    <span class="meta-val">{{ $announcement->created_at?->format('d M Y') ?? '—' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-lbl">Diperbarui</span>
                    <span class="meta-val">{{ $announcement->updated_at?->format('d M Y') ?? '—' }}</span>
                </div>
                <div class="meta-row">
                    <span class="meta-lbl">Status Saat Ini</span>
                    <span class="status-pill {{ $announcement->is_published ? 'active' : 'draft' }}">
                        {{ $announcement->is_published ? 'Aktif' : 'Draft' }}
                    </span>
                </div>
                @if($announcement->published_at)
                <div class="meta-row">
                    <span class="meta-lbl">Terbit Pada</span>
                    <span class="meta-val">{{ \Carbon\Carbon::parse($announcement->published_at)->format('d M Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Preview --}}
        <div class="preview-card">
            <div class="preview-card-hdr">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                    <circle cx="12" cy="12" r="3" />
                </svg>
                Preview Live
            </div>
            <div class="preview-card-body">
                <div class="preview-announcement">
                    <div class="preview-ann-bar" :style="{ background: barColor(kategori) }"></div>
                    <div class="preview-ann-content">
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                            <span style="font-size:.68rem;font-weight:700;padding:2px 9px;border-radius:999px;transition:all .2s;"
                                :style="kategoriStyle(kategori)" x-text="kategoriLabel(kategori)"></span>
                            <span style="font-size:.65rem;color:#94a3b8;font-weight:600;">
                                {{ now()->format('d M Y') }}
                            </span>
                        </div>
                        <div style="font-size:.88rem;font-weight:700;color:#1e293b;margin-bottom:8px;line-height:1.4;min-height:22px;"
                            x-text="judul || 'Judul pengumuman...'"></div>
                        <div style="font-size:.75rem;color:#64748b;line-height:1.6;min-height:48px;display:-webkit-box;-webkit-line-clamp:4;line-clamp: 2;-webkit-box-orient:vertical;overflow:hidden;"
                            x-text="konten || 'Konten akan tampil di sini...'"></div>
                        <div style="margin-top:12px;padding-top:10px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
                            <span style="font-size:.65rem;font-weight:800;padding:2px 9px;border-radius:999px;transition:all .2s;"
                                :style="publish ? 'background:#dcfce7;color:#15803d' : 'background:#f1f5f9;color:#475569'"
                                x-text="publish ? 'Aktif' : 'Draft'"></span>
                            <span style="font-size:.65rem;color:#94a3b8;font-weight:600;">Admin</span>
                        </div>
                    </div>
                </div>

                <div style="margin-top:14px;padding:10px 12px;background:#f8fafc;border-radius:10px;border:1px solid #f1f5f9;">
                    <div style="font-size:.65rem;font-weight:800;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin-bottom:6px;">Ringkasan</div>
                    <div style="font-size:.75rem;color:#475569;display:flex;flex-direction:column;gap:5px;">
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:#94a3b8;">Kategori</span>
                            <span style="font-weight:700;color:#334155;" x-text="kategoriLabel(kategori)"></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:#94a3b8;">Karakter Judul</span>
                            <span style="font-weight:700;color:#334155;" x-text="judulLen + '/150'"></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:#94a3b8;">Karakter Konten</span>
                            <span style="font-weight:700;color:#334155;" x-text="kontenLen + '/5000'"></span>
                        </div>
                        <div style="display:flex;justify-content:space-between;">
                            <span style="color:#94a3b8;">Status</span>
                            <span style="font-weight:700;" :style="publish ? 'color:#15803d' : 'color:#475569'" x-text="publish ? 'Akan terbit' : 'Draft'"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>{{-- end grid --}}

@endsection