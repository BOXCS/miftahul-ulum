@extends('layouts.app')

@section('title', 'Edit Wali Santri')
@section('breadcrumb', 'Edit Wali Santri')
@push('styles')
<style>
    /* ── TEAL TOKENS ── */
    :root {
        --teal-50: #f0fdfa;
        --teal-100: #ccfbf1;
        --teal-600: #0d9488;
        --teal-700: #0f766e;
        --teal-800: #115e59;
        --sh-teal: 0 8px 28px rgba(13, 148, 136, .22);
        --sh-teal-sm: 0 4px 14px rgba(13, 148, 136, .18);
    }

    /* ── PAGE HEADER ── */
    .ph-back {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-size: .78rem;
        font-weight: 700;
        color: #64748b;
        text-decoration: none;
        transition: color .2s;
        margin-bottom: 6px;
    }

    .ph-back:hover {
        color: #0d9488;
    }

    /* ── FORM CARD ── */
    .form-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 10px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .form-card-header {
        padding: 20px 28px 18px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .form-card-header-ico {
        width: 42px;
        height: 42px;
        border-radius: 13px;
        background: #fef3c7;
        color: #b45309;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .form-card-title {
        font-size: 1.05rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -.02em;
    }

    .form-card-sub {
        font-size: .75rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* ── IDENTITY STRIP ── */
    .identity-strip {
        padding: 14px 28px;
        background: #f0fdfa;
        border-bottom: 1px solid #ccfbf1;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .identity-ava {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: #ccfbf1;
        color: #0d9488;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .identity-name {
        font-size: .9rem;
        font-weight: 800;
        color: #0f172a;
    }

    .identity-sub {
        font-size: .72rem;
        color: #64748b;
        margin-top: 2px;
    }

    .identity-badge {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        font-size: .65rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 999px;
        background: #ccfbf1;
        color: #0f766e;
        letter-spacing: .04em;
    }

    .form-card-body {
        padding: 24px 28px;
    }

    /* ── FORM GRID ── */
    .fg {
        display: grid;
        gap: 18px;
    }

    .fg-2 {
        grid-template-columns: 1fr 1fr;
    }

    .flabel {
        display: block;
        font-size: .72rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 7px;
    }

    .flabel .req {
        color: #e11d48;
        margin-left: 3px;
    }

    .flabel .opt {
        color: #94a3b8;
        font-weight: 500;
        text-transform: none;
        letter-spacing: 0;
        font-size: .68rem;
    }

    .finput,
    .fselect,
    .ftextarea {
        width: 100%;
        padding: 10px 14px;
        border-radius: 11px;
        border: 1.5px solid #e2e8f0;
        font-size: .83rem;
        font-family: inherit;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        transition: border .2s, box-shadow .2s, background .2s;
        box-sizing: border-box;
    }

    .finput:focus,
    .fselect:focus,
    .ftextarea:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);
        background: #fff;
    }

    .finput::placeholder,
    .ftextarea::placeholder {
        color: #cbd5e1;
    }

    .ftextarea {
        resize: vertical;
        min-height: 90px;
    }

    .fselect {
        appearance: none;
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
        padding-right: 36px;
        cursor: pointer;
    }

    /* ── INVALID ── */
    .finput.is-invalid,
    .fselect.is-invalid,
    .ftextarea.is-invalid {
        border-color: #e11d48;
        background: #fff5f5;
    }

    .invalid-feedback {
        display: block;
        font-size: .72rem;
        color: #e11d48;
        font-weight: 600;
        margin-top: 5px;
    }

    /* ── DIVIDER ── */
    .form-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 4px 0 6px;
    }

    .form-section-label {
        font-size: .65rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .12em;
        color: #cbd5e1;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-section-label::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #f1f5f9;
    }

    /* ── FOOTER ── */
    .form-card-footer {
        padding: 16px 28px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    /* ── BUTTONS ── */
    .btn-teal {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 22px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0d9488, #0f766e);
        color: #fff;
        font-size: .83rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        box-shadow: var(--sh-teal-sm);
        transition: all .25s ease;
        font-family: inherit;
    }

    .btn-teal:hover {
        background: linear-gradient(135deg, #0f766e, #115e59);
        transform: translateY(-1px);
        box-shadow: var(--sh-teal);
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 10px 18px;
        border-radius: 12px;
        background: #fff;
        color: #475569;
        font-size: .83rem;
        font-weight: 700;
        border: 1.5px solid #e2e8f0;
        cursor: pointer;
        transition: all .2s;
        font-family: inherit;
        text-decoration: none;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #334155;
    }

    /* ── RESPONSIVE ── */
    @media (max-width: 640px) {
        .fg-2 {
            grid-template-columns: 1fr;
        }

        .form-card-body {
            padding: 18px 16px;
        }

        .form-card-header {
            padding: 16px;
        }

        .form-card-footer {
            padding: 14px 16px;
        }

        .identity-strip {
            padding: 12px 16px;
        }
    }
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div style="margin-bottom:22px;">
    <a href="{{ route('parents.index') }}" class="ph-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Kembali ke Data Wali
    </a>
    <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Edit Wali Santri</h1>
    <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Perbarui informasi wali / orang tua santri</p>
</div>

<div class="form-card">
            {{-- Header --}}
            <div class="form-card-header">
                <div class="form-card-header-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </div>
                <div>
                    <div class="form-card-title">Edit Data Wali Santri</div>
                    <div class="form-card-sub">Perubahan akan langsung tersimpan setelah submit</div>
                </div>
            </div>

            {{-- Identity Strip --}}
            <div class="identity-strip">
                <div class="identity-ava">
                    @php
                    $initials = collect(explode(' ', $parent['name']))->take(2)->map(fn($w) => strtoupper($w[0]))->join('');
                    @endphp
                    {{ $initials }}
                </div>
                <div>
                    <div class="identity-name">{{ $parent['name'] }}</div>
                    <div class="identity-sub">{{ $parent['relationship'] }} &nbsp;·&nbsp; {{ $parent['phone'] }}</div>
                </div>
                <span class="identity-badge">Sedang Diedit</span>
            </div>

            {{-- Body --}}
            <div class="form-card-body">
                <form action="{{ route('parents.update', $parent['id']) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')

                    <div class="fg">
                        {{-- Informasi Wali --}}
                        <div class="form-section-label">Informasi Wali</div>

                        {{-- Nama --}}
                        <div>
                            <label for="nama" class="flabel">Nama Lengkap <span class="req">*</span></label>
                            <input type="text" id="nama" name="name"
                                class="finput @error('nama') is-invalid @enderror"
                                value="{{ old('name', $parent['name']) }}"
                                placeholder="Nama lengkap wali santri" required>
                            @error('nama')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Hubungan & Santri --}}
                        <div class="fg fg-2">
                            <div>
                                <label for="hubungan" class="flabel">Hubungan <span class="req">*</span></label>
                                <select id="hubungan" name="relationship"
                                    class="fselect @error('hubungan') is-invalid @enderror" required>
                                    <option value="">-- Pilih Hubungan --</option>
                                    @foreach($hubunganOptions as $option)
                                    <option value="{{ $option }}" {{ old('relationship', $parent['relationship']) == $option ? 'selected' : '' }}>
                                        {{ $option }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('hubungan')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-divider"></div>
                        <div class="form-section-label">Kontak</div>

                        {{-- HP & Email --}}
                        <div class="fg fg-2">
                            <div>
                                <label for="no_hp" class="flabel">Nomor HP / WhatsApp <span class="req">*</span></label>
                                <input type="text" id="no_hp" name="phone"
                                    class="finput @error('no_hp') is-invalid @enderror"
                                    value="{{ old('phone', $parent['phone']) }}"
                                    placeholder="08xxxxxxxxxx" required>
                                @error('no_hp')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div>
                                <label for="email" class="flabel">Email <span class="opt">(opsional)</span></label>
                                <input type="email" id="email" name="email"
                                    class="finput @error('email') is-invalid @enderror"
                                    value="{{ old('email', $parent['email']) }}"
                                    placeholder="email@example.com">
                                @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-divider"></div>
                        <div class="form-section-label">Alamat</div>

                        {{-- Alamat --}}
                        <div>
                            <label for="alamat" class="flabel">Alamat Lengkap <span class="req">*</span></label>
                            <textarea id="alamat" name="address" rows="3"
                                class="ftextarea @error('alamat') is-invalid @enderror"
                                placeholder="Jl. Contoh No. 1, Kelurahan, Kecamatan, Kota"
                                required>{{ old('address', $parent['address']) }}</textarea>
                            @error('alamat')
                            <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div class="form-card-footer">
                <a href="{{ route('parents.index') }}" class="btn-cancel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                    Batal
                </a>
                <button type="submit" form="editForm" class="btn-teal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Update Data
                </button>
            </div>
        </div>

        @endsection