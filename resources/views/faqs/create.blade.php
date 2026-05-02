@extends('layouts.app')

@section('title', 'Tambah FAQ')
@section('breadcrumb', 'FAQ / Tambah')

@push('styles')
<style>
    .form-card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .form-card-header {
        padding: 20px 28px 18px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .form-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 13px;
        background: #ccfbf1;
        color: #0d9488;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .form-card-body {
        padding: 24px 28px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-card-footer {
        padding: 16px 28px;
        border-top: 1px solid #f1f5f9;
        background: #f8fafc;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    .form-group label {
        display: block;
        font-size: .75rem;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .06em;
        margin-bottom: 7px;
    }

    .form-group label span.req {
        color: #e11d48;
        margin-left: 3px;
    }

    .form-control-styled {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .875rem;
        font-family: inherit;
        color: #334155;
        background: #f8fafc;
        outline: none;
        transition: border .2s, box-shadow .2s, background .2s;
        box-sizing: border-box;
    }

    .form-control-styled:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);

        background: #fff;
    }

    .form-control-styled.error {
        border-color: #e11d48;
        box-shadow: 0 0 0 3px rgba(225, 29, 72, .1);
    }

    textarea.form-control-styled {
        resize: vertical;
        min-height: 130px;
        line-height: 1.6;
    }

    .error-msg {
        font-size: .72rem;
        color: #e11d48;
        margin-top: 5px;
        font-weight: 600;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .toggle-wrap {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .toggle {
        position: relative;
        width: 44px;
        height: 24px;
        flex-shrink: 0;
    }

    .toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .toggle-slider {
        position: absolute;
        inset: 0;
        background: #e2e8f0;
        border-radius: 24px;
        cursor: pointer;
        transition: .25s;
    }

    .toggle-slider::before {
        content: '';
        position: absolute;
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background: #fff;
        border-radius: 50%;
        transition: .25s;
        box-shadow: 0 1px 4px rgba(0, 0, 0, .2);
    }

    .toggle input:checked+.toggle-slider {
        background: #0d9488;
    }

    .toggle input:checked+.toggle-slider::before {
        transform: translateX(20px);
    }

    .toggle-label {
        font-size: .82rem;
        font-weight: 600;
        color: #475569;
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
        transform: translateY(-1px);
        box-shadow: var(--sh-teal);
        color: #fff;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 9px 18px;
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

    .btn-cancel:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #334155;
    }

    .hint-text {
        font-size: .72rem;
        color: #94a3b8;
        margin-top: 5px;
    }

    @media (max-width: 600px) {
        .form-row {
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
    }

    /* TAMBAHKAN di paling atas blok <style> */
    :root {
        --sh-teal: 0 8px 28px rgba(13, 148, 136, .22);
        --sh-teal-sm: 0 4px 14px rgba(13, 148, 136, .18);
    }

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
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div style="margin-bottom:22px;">
    <a href="{{ route('faqs.index') }}" class="ph-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Kembali ke FAQ
    </a>
    <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Tambah FAQ</h1>
    <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Buat pertanyaan dan jawaban baru</p>
</div>

<div style="width:100%;max-width:none;">
    <form action="{{ route('faqs.store') }}" method="POST">
        @csrf

        <div class="form-card">
            <div class="form-card-header">
                <div class="form-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>
                <div>
                    <div style="font-size:.95rem;font-weight:800;color:#0f172a;">Informasi FAQ</div>
                    <div style="font-size:.75rem;color:#94a3b8;margin-top:2px;">Isi pertanyaan dan jawaban dengan jelas</div>
                </div>
            </div>

            <div class="form-card-body">

                {{-- Pertanyaan --}}
                <div class="form-group">
                    <label>Pertanyaan <span class="req">*</span></label>
                    <input type="text" name="pertanyaan"
                        class="form-control-styled {{ $errors->has('pertanyaan') ? 'error' : '' }}"
                        value="{{ old('pertanyaan') }}"
                        placeholder="Contoh: Bagaimana cara mendaftar?"
                        required>
                    @error('pertanyaan')
                    <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Kategori & Urutan --}}
                <div class="form-row">
                    <div class="form-group">
                        <label>Kategori <span class="req">*</span></label>
                        <input type="text" name="kategori"
                            class="form-control-styled {{ $errors->has('kategori') ? 'error' : '' }}"
                            value="{{ old('kategori', 'Umum') }}"
                            placeholder="Contoh: Pendaftaran, Umum"
                            list="kategori-list"
                            required>
                        @if(count($kategoris))
                        <datalist id="kategori-list">
                            @foreach($kategoris as $kat)
                            <option value="{{ $kat }}">
                                @endforeach
                        </datalist>
                        @endif
                        @error('kategori')
                        <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Urutan Tampil</label>
                        <input type="number" name="urutan"
                            class="form-control-styled {{ $errors->has('urutan') ? 'error' : '' }}"
                            value="{{ old('urutan', 1) }}"
                            min="0" placeholder="1">
                        <div class="hint-text">Angka kecil tampil lebih dulu</div>
                        @error('urutan')
                        <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Jawaban --}}
                <div class="form-group">
                    <label>Jawaban <span class="req">*</span></label>
                    <textarea name="jawaban"
                        class="form-control-styled {{ $errors->has('jawaban') ? 'error' : '' }}"
                        placeholder="Tulis jawaban yang jelas dan informatif…"
                        rows="6"
                        required>{{ old('jawaban') }}</textarea>
                    @error('jawaban')
                    <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="form-group">
                    <label>Status</label>
                    <div class="toggle-wrap">
                        <label class="toggle">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label">Aktifkan FAQ ini</span>
                    </div>
                </div>

            </div>

            <div class="form-card-footer">
                <a href="{{ route('faqs.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-teal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Simpan FAQ
                </button>
            </div>
        </div>

    </form>
</div>

@endsection