@extends('layouts.app')

@section('title', 'Edit Permintaan Izin')
@section('breadcrumb', 'Edit Permintaan Izin')
@push('styles')
<style>
    :root {
        --sh-purple-sm: 0 4px 14px rgba(124, 58, 237, .2);
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
        color: #7c3aed;
    }

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

    .identity-strip {
        padding: 14px 28px;
        background: #faf5ff;
        border-bottom: 1px solid #ede9fe;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .identity-ava {
        width: 46px;
        height: 46px;
        border-radius: 14px;
        background: #ede9fe;
        color: #7c3aed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .85rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    .identity-badge {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        font-size: .65rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 999px;
        background: #ede9fe;
        color: #6d28d9;
        letter-spacing: .04em;
    }

    .form-card-body {
        padding: 24px 28px;
    }

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
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, .1);
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
        cursor: pointer;
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
        padding-right: 36px;
    }

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

    .form-card-footer {
        padding: 16px 28px;
        background: #f8fafc;
        border-top: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
    }

    .btn-purple {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 22px;
        border-radius: 12px;
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        color: #fff;
        font-size: .83rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        box-shadow: var(--sh-purple-sm);
        transition: all .25s ease;
        font-family: inherit;
    }

    .btn-purple:hover {
        background: linear-gradient(135deg, #6d28d9, #5b21b6);
        transform: translateY(-1px);
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

    @media (max-width:640px) {
        .fg-2 {
            grid-template-columns: 1fr;
        }

        .form-card-body,
        .form-card-header,
        .form-card-footer,
        .identity-strip {
            padding: 16px;
        }
    }
</style>
@endpush

@section('content')

<div style="margin-bottom:22px;">
    <a href="{{ route('permissions.index') }}" class="ph-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Kembali ke Perizinan
    </a>
    <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Edit Permintaan Izin</h1>
    <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Perbarui data permohonan izin santri</p>
</div>

<div class="form-card">
    <div class="form-card-header">
        <div class="form-card-header-ico">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
            </svg>
        </div>
        <div>
            <div class="form-card-title">Edit Data Permohonan Izin</div>
            <div class="form-card-sub">Perubahan akan langsung tersimpan setelah submit</div>
        </div>
    </div>

    <div class="identity-strip">
        <div class="identity-ava">
            {{ strtoupper(substr($permission->student?->name ?? 'N', 0, 2)) }}
        </div>
        <div>
            <div style="font-size:.9rem;font-weight:800;color:#0f172a;">{{ $permission->student?->name ?? 'N/A' }}</div>
            <div style="font-size:.72rem;color:#64748b;margin-top:2px;">{{ ucfirst($permission->jenis) }} &nbsp;·&nbsp; {{ $permission->tanggal_mulai->format('d/m/Y') }}</div>
        </div>
        <span class="identity-badge">Sedang Diedit</span>
    </div>

    <div class="form-card-body">
        <form action="{{ route('permissions.update', $permission->id) }}" method="POST" id="editForm">
            @csrf
            @method('PUT')
            <div class="fg">
                <div class="form-section-label">Data Santri & Izin</div>

                <div class="fg fg-2">
                    <div>
                        <label for="student_id" class="flabel">Santri <span class="req">*</span></label>
                        <select id="student_id" name="student_id" class="fselect @error('student_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Santri --</option>
                            @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id', $permission->student_id) == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->nis }})
                            </option>
                            @endforeach
                        </select>
                        @error('student_id')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="jenis" class="flabel">Jenis Izin <span class="req">*</span></label>
                        <select id="jenis" name="jenis" class="fselect @error('jenis') is-invalid @enderror" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="keluar" {{ old('jenis', $permission->jenis) == 'keluar'   ? 'selected' : '' }}>Keluar</option>
                            <option value="pulang" {{ old('jenis', $permission->jenis) == 'pulang'   ? 'selected' : '' }}>Pulang</option>
                            <option value="kegiatan" {{ old('jenis', $permission->jenis) == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                            <option value="sakit" {{ old('jenis', $permission->jenis) == 'sakit'    ? 'selected' : '' }}>Sakit</option>
                        </select>
                        @error('jenis')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-divider"></div>
                <div class="form-section-label">Tanggal</div>

                <div class="fg fg-2">
                    <div>
                        <label for="tanggal_mulai" class="flabel">Tanggal Mulai <span class="req">*</span></label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                            class="finput @error('tanggal_mulai') is-invalid @enderror"
                            value="{{ old('tanggal_mulai', $permission->tanggal_mulai->format('Y-m-d')) }}" required>
                        @error('tanggal_mulai')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label for="tanggal_selesai" class="flabel">Tanggal Selesai <span class="req">*</span></label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                            class="finput @error('tanggal_selesai') is-invalid @enderror"
                            value="{{ old('tanggal_selesai', $permission->tanggal_selesai->format('Y-m-d')) }}" required>
                        @error('tanggal_selesai')<span class="invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-divider"></div>
                <div class="form-section-label">Keterangan</div>

                <div>
                    <label for="keterangan" class="flabel">Keterangan <span class="opt">(opsional)</span></label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="ftextarea @error('keterangan') is-invalid @enderror"
                        placeholder="Jelaskan alasan permintaan izin ini...">{{ old('keterangan', $permission->keterangan) }}</textarea>
                    @error('keterangan')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>
            </div>
        </form>
    </div>

    <div class="form-card-footer">
        <a href="{{ route('permissions.index') }}" class="btn-cancel">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
            Batal
        </a>
        <button type="submit" form="editForm" class="btn-purple">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                <polyline points="17 21 17 13 7 13 7 21" />
                <polyline points="7 3 7 8 15 8" />
            </svg>
            Update Izin
        </button>
    </div>
</div>

@endsection