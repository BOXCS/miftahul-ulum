@extends('layouts.app')

@section('title', 'Absensi Baru')
@section('breadcrumb', 'Presensi / Absensi Baru')

@push('styles')
<style>
    :root {
        --teal: #0d9488;
        --teal-dark: #0f766e;
        --teal-darker: #115e59;
        --sh-teal: 0 8px 28px rgba(13, 148, 136, .22);
        --sh-teal-sm: 0 4px 14px rgba(13, 148, 136, .18);
    }

    .btn-teal {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 22px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0d9488, #0f766e);
        color: #fff;
        font-size: .84rem;
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

    .btn-outline {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 10px 18px;
        border-radius: 12px;
        background: #fff;
        color: #475569;
        font-size: .84rem;
        font-weight: 700;
        border: 1.5px solid #e2e8f0;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s;
        font-family: inherit;
    }

    .btn-outline:hover {
        background: #f0fdfa;
        border-color: #0d9488;
        color: #0d9488;
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

    /* ── LAYOUT ── */
    .create-layout {
        display: grid;
        grid-template-columns: 340px 1fr;
        gap: 20px;
        align-items: start;
    }

    @media (max-width: 900px) {
        .create-layout {
            grid-template-columns: 1fr;
        }
    }

    /* ── PANEL ── */
    .panel {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .panel-hdr {
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        background: #f8fafc;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .panel-hdr-ico {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #ccfbf1;
        color: #0d9488;
        flex-shrink: 0;
    }

    .panel-hdr-title {
        font-size: .82rem;
        font-weight: 800;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .panel-hdr-sub {
        font-size: .68rem;
        color: #94a3b8;
        margin-top: 1px;
    }

    /* ── STUDENT SEARCH ── */
    .student-search-wrap {
        padding: 14px 16px;
        border-bottom: 1px solid #f1f5f9;
        position: relative;
    }

    .student-search-wrap svg {
        position: absolute;
        left: 28px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
    }

    .student-search {
        width: 100%;
        padding: 9px 14px 9px 36px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .82rem;
        font-family: inherit;
        color: #1e293b;
        background: #fff;
        outline: none;
        box-sizing: border-box;
        transition: border .2s, box-shadow .2s;
    }

    .student-search:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .1);
    }

    /* ── STUDENT LIST ── */
    .student-list {
        height: 380px;
        overflow-y: auto;
        scroll-behavior: smooth;
    }

    .student-list::-webkit-scrollbar {
        width: 4px;
    }

    .student-list::-webkit-scrollbar-track {
        background: #f8fafc;
    }

    .student-list::-webkit-scrollbar-thumb {
        background: #99f6e4;
        border-radius: 4px;
    }

    .student-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 16px;
        cursor: pointer;
        transition: background .15s;
        border-bottom: 1px solid #f8fafc;
    }

    .student-item:hover {
        background: #f0fdfa;
    }

    .student-item.selected {
        background: #ccfbf1;
        border-left: 3px solid #0d9488;
    }

    .student-item.selected .s-name {
        color: #0d9488;
    }

    .s-ava {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .72rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        background: linear-gradient(135deg, #0d9488, #5eead4);
    }

    .s-ava.sel {
        background: linear-gradient(135deg, #0f766e, #0d9488);
    }

    .s-name {
        font-size: .82rem;
        font-weight: 700;
        color: #1e293b;
        display: block;
    }

    .s-meta {
        font-size: .68rem;
        color: #94a3b8;
    }

    .s-check {
        margin-left: auto;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #0d9488;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    /* ── FORM PANEL ── */
    .form-section {
        padding: 20px 24px;
    }

    .form-section+.form-section {
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
    }

    .form-label {
        display: block;
        font-size: .7rem;
        font-weight: 800;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 8px;
    }

    .form-label span {
        color: #e11d48;
    }

    /* ── STATUS GRID ── */
    .status-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 8px;
    }

    .status-radio {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        padding: 10px 6px;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all .18s;
        font-size: .7rem;
        font-weight: 700;
        color: #64748b;
        background: #f8fafc;
    }

    .status-radio:hover {
        border-color: #0d9488;
        background: #f0fdfa;
    }

    .status-radio.sel-teal {
        border-color: #0d9488;
        background: #f0fdfa;
        color: #0d9488;
    }

    .status-radio.sel-blue {
        border-color: #2563eb;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .status-radio.sel-amber {
        border-color: #d97706;
        background: #fffbeb;
        color: #b45309;
    }

    .status-radio.sel-rose {
        border-color: #db2777;
        background: #fdf2f8;
        color: #be185d;
    }

    .status-radio.sel-red {
        border-color: #dc2626;
        background: #fff5f5;
        color: #dc2626;
    }

    /* ── PREVIEW EMPTY ── */
    .preview-empty {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .preview-ico {
        width: 60px;
        height: 60px;
        border-radius: 18px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        font-size: 1.6rem;
    }

    /* ── SELECTED STRIP ── */
    .selected-strip {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #f0fdfa;
        border-radius: 12px;
        border: 1.5px solid #99f6e4;
        margin-bottom: 20px;
    }

    .selected-strip-ava {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, #0d9488, #5eead4);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .82rem;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
    }

    .fselect {
        width: 100%;
        padding: 10px 14px;
        border-radius: 11px;
        border: 1.5px solid #e2e8f0;
        font-size: .84rem;
        font-family: inherit;
        color: #1e293b;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 12px center;
        appearance: none;
        outline: none;
        cursor: pointer;
        transition: border .2s, box-shadow .2s;
        box-sizing: border-box;
    }

    .fselect:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .1);
    }

    .finput-text {
        width: 100%;
        padding: 10px 14px;
        border-radius: 11px;
        border: 1.5px solid #e2e8f0;
        font-size: .84rem;
        font-family: inherit;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        transition: border .2s, box-shadow .2s;
        box-sizing: border-box;
    }

    .finput-text:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .1);
        background: #fff;
    }

    /* toast */
    .toast-wrap {
        position: fixed;
        bottom: 28px;
        right: 28px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 10px;
        pointer-events: none;
    }

    .toast {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #fff;
        border-radius: 14px;
        padding: 14px 18px;
        box-shadow: 0 8px 30px rgba(15, 23, 42, .14);
        border-left: 4px solid;
        min-width: 280px;
        max-width: 360px;
        transform: translateX(120%);
        opacity: 0;
        transition: transform .35s cubic-bezier(.22, .68, 0, 1.2), opacity .35s ease;
        pointer-events: all;
    }

    .toast.show {
        transform: translateX(0);
        opacity: 1;
    }

    .toast.error {
        border-color: #e11d48;
    }

    .toast-ico {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .toast.error .toast-ico {
        background: #fee2e2;
        color: #e11d48;
    }

    .toast-title {
        font-size: .82rem;
        font-weight: 700;
        color: #0f172a;
    }

    .toast-msg {
        font-size: .72rem;
        color: #64748b;
        margin-top: 2px;
    }
</style>
@endpush

@section('content')

<div class="toast-wrap" id="toastWrap" data-error="{{ session('error') }}"></div>

{{-- PAGE HEADER --}}
<div style="margin-bottom:24px;">
    <a href="{{ route('attendance.index') }}" class="ph-back">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="15 18 9 12 15 6" />
        </svg>
        Kembali ke Presensi
    </a>
    <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Absensi Baru</h1>
    <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Pilih santri dari daftar, lalu isi status kehadiran</p>
</div>

<div class="create-layout" x-data="{
    students: @js($students),
    search: '',
    selectedStudent: null,
    status: 'hadir',
    keterangan: '',
    jam_masuk: '',
    jam_keluar: '',
    tanggal: '{{ now()->toDateString() }}',
    waktu_shalat: 'Subuh',

    get filtered() {
        const q = this.search.toLowerCase();
        return !q ? this.students : this.students.filter(s =>
            s.name.toLowerCase().includes(q) ||
            (s.class||'').toLowerCase().includes(q) ||
            (s.nis||'').toLowerCase().includes(q)
        );
    },
    selectStudent(s) { this.selectedStudent = s; },
    statusSelClass(opt) {
        if (this.status !== opt) return '';
        const m = { hadir:'sel-teal', terlambat:'sel-blue', izin:'sel-amber', sakit:'sel-rose', alpha:'sel-red' };
        return m[opt] || '';
    },
    get canSubmit() { return this.selectedStudent !== null; }
}">

    {{-- LEFT: Student picker --}}
    <div class="panel">
        <div class="panel-hdr">
            <div class="panel-hdr-ico">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <div>
                <div class="panel-hdr-title">Daftar Santri</div>
                <div class="panel-hdr-sub" x-text="'Total ' + students.length + ' santri aktif'"></div>
            </div>
        </div>

        <div class="student-search-wrap">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="search" class="student-search" placeholder="Cari nama, kelas, atau NIS…"
                x-model="search" autocomplete="off">
        </div>

        <div class="student-list">
            <template x-if="filtered.length === 0">
                <div style="padding:40px 20px;text-align:center;color:#94a3b8;">
                    <div style="font-size:1.5rem;margin-bottom:8px;">🔍</div>
                    <p style="font-size:.8rem;font-weight:600;">Santri tidak ditemukan</p>
                </div>
            </template>

            <template x-for="s in filtered" :key="s.id">
                <div class="student-item" :class="selectedStudent && selectedStudent.id === s.id ? 'selected' : ''"
                    @click="selectStudent(s)">
                    <div class="s-ava" :class="selectedStudent && selectedStudent.id === s.id ? 'sel' : ''">
                        <span x-text="s.name.charAt(0).toUpperCase()"></span>
                    </div>
                    <div style="min-width:0;flex:1;">
                        <span class="s-name" x-text="s.name"></span>
                        <span class="s-meta" x-text="'Kelas ' + (s.class||'-') + '  ·  NIS: ' + (s.nis||'-')"></span>
                    </div>
                    <template x-if="selectedStudent && selectedStudent.id === s.id">
                        <div class="s-check">
                            <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="20 6 9 17 4 12" />
                            </svg>
                        </div>
                    </template>
                </div>
            </template>
        </div>

        <div style="padding:10px 16px;border-top:1px solid #f1f5f9;background:#f8fafc;font-size:.7rem;color:#94a3b8;display:flex;justify-content:space-between;">
            <span x-text="filtered.length + ' santri ditampilkan'"></span>
            <span x-show="selectedStudent" x-text="'✓ ' + (selectedStudent ? selectedStudent.name : '') + ' dipilih'" style="color:#0d9488;font-weight:700;"></span>
        </div>
    </div>

    {{-- RIGHT: Form --}}
    <div>
        {{-- Empty state --}}
        <div class="panel" x-show="!selectedStudent">
            <div class="preview-empty">
                <div class="preview-ico">👈</div>
                <p style="font-size:.88rem;font-weight:700;color:#334155;margin-bottom:4px;">Pilih Santri Terlebih Dahulu</p>
                <p style="font-size:.78rem;color:#94a3b8;max-width:240px;line-height:1.5;">Klik nama santri di panel kiri untuk mulai mengisi absensi</p>
            </div>
        </div>

        {{-- Form --}}
        <div class="panel" x-show="selectedStudent" x-cloak>
            <div class="panel-hdr">
                <div class="panel-hdr-ico">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                    </svg>
                </div>
                <div>
                    <div class="panel-hdr-title">Form Absensi</div>
                    <div class="panel-hdr-sub" x-text="selectedStudent ? selectedStudent.name : ''"></div>
                </div>
            </div>

            <form action="{{ route('attendance.store') }}" method="POST" id="createForm">
                @csrf

                <div class="form-section">
                    {{-- Selected student strip --}}
                    <div class="selected-strip" x-show="selectedStudent">
                        <div class="selected-strip-ava">
                            <span x-text="selectedStudent ? selectedStudent.name.charAt(0).toUpperCase() : ''"></span>
                        </div>
                        <div>
                            <div style="font-size:.9rem;font-weight:800;color:#0f172a;" x-text="selectedStudent ? selectedStudent.name : ''"></div>
                            <div style="font-size:.72rem;color:#64748b;" x-text="selectedStudent ? 'Kelas ' + (selectedStudent.class||'-') + '  ·  NIS: ' + (selectedStudent.nis||'-') : ''"></div>
                        </div>
                        <div style="margin-left:auto;">
                            <span style="display:inline-flex;align-items:center;gap:4px;background:#ccfbf1;color:#0d9488;font-size:.68rem;font-weight:700;padding:3px 10px;border-radius:20px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>
                                Dipilih
                            </span>
                        </div>
                    </div>

                    {{-- Hidden student id --}}
                    <input type="hidden" :name="selectedStudent ? 'attendances[' + selectedStudent.id + '][_placeholder]' : '_placeholder'" value="1" x-show="false">

                    {{-- Tanggal & Waktu --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px;">
                        <div>
                            <label class="form-label">Tanggal <span>*</span></label>
                            <input type="date" name="tanggal" x-model="tanggal" class="finput-text" required>
                        </div>
                        <div>
                            <label class="form-label">Waktu Shalat <span>*</span></label>
                            <select name="waktu_shalat" x-model="waktu_shalat" class="fselect" required>
                                @foreach(['Subuh','Dzuhur','Ashar','Maghrib','Isya'] as $p)
                                <option value="{{ $p }}">{{ $p }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    {{-- Status --}}
                    <label class="form-label">Status Kehadiran <span>*</span></label>
                    <div class="status-grid">
                        @foreach([
                        ['hadir', 'teal'],
                        ['terlambat', 'blue'],
                        ['izin', 'amber'],
                        ['sakit', 'rose'],
                        ['alpha', 'red'],
                        ] as [$opt, $color])
                        <label class="status-radio" :class="statusSelClass('{{ $opt }}')" style="cursor:pointer;">
                            <input type="radio"
                                :name="selectedStudent ? 'attendances[' + selectedStudent.id + '][status]' : '_status'"
                                value="{{ $opt }}" x-model="status" style="display:none;" required>
                            @if($opt === 'hadir')
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22 4 12 14.01 9 11.01" />
                            </svg>
                            @elseif($opt === 'terlambat')
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <polyline points="12 6 12 12 16 14" />
                            </svg>
                            @elseif($opt === 'izin')
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <polyline points="14 2 14 8 20 8" />
                            </svg>
                            @elseif($opt === 'sakit')
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                            </svg>
                            @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <line x1="15" y1="9" x2="9" y2="15" />
                                <line x1="9" y1="9" x2="15" y2="15" />
                            </svg>
                            @endif
                            <span style="font-size:.7rem;text-transform:capitalize;">{{ $opt }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="form-section">
                    {{-- Jam Masuk & Jam Keluar --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px;">
                        <div>
                            <label class="form-label">Jam Masuk</label>
                            <input type="time"
                                :name="selectedStudent ? 'attendances[' + selectedStudent.id + '][jam_masuk]' : '_jam_masuk'"
                                x-model="jam_masuk"
                                class="finput-text"
                                onfocus="this.style.borderColor='#0d9488';this.style.boxShadow='0 0 0 3px rgba(13,148,136,.1)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        </div>
                        <div>
                            <label class="form-label">Jam Keluar</label>
                            <input type="time"
                                :name="selectedStudent ? 'attendances[' + selectedStudent.id + '][jam_keluar]' : '_jam_keluar'"
                                x-model="jam_keluar"
                                class="finput-text"
                                onfocus="this.style.borderColor='#0d9488';this.style.boxShadow='0 0 0 3px rgba(13,148,136,.1)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <label class="form-label">
                        Keterangan
                        <span style="font-size:.65rem;font-weight:500;text-transform:none;color:#94a3b8;">(opsional)</span>
                    </label>
                    <textarea
                        :name="selectedStudent ? 'attendances[' + selectedStudent.id + '][keterangan]' : '_ket'"
                        rows="3"
                        x-model="keterangan"
                        class="finput-text"
                        style="resize:vertical;"
                        placeholder="Tambahkan keterangan jika diperlukan…"
                        onfocus="this.style.borderColor='#0d9488';this.style.boxShadow='0 0 0 3px rgba(13,148,136,.1)'"
                        onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'"></textarea>
                </div>

                {{-- Footer actions --}}
                <div style="padding:16px 24px;border-top:1px solid #f1f5f9;background:#f8fafc;display:flex;justify-content:space-between;align-items:center;gap:10px;">
                    <a href="{{ route('attendance.index') }}" class="btn-outline">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6" />
                        </svg>
                        Batal
                    </a>
                    <button type="submit" form="createForm" class="btn-teal" :disabled="!canSubmit"
                        :style="!canSubmit ? 'opacity:.5;cursor:not-allowed;transform:none;box-shadow:none;' : ''">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                            <polyline points="17 21 17 13 7 13 7 21" />
                            <polyline points="7 3 7 8 15 8" />
                        </svg>
                        Simpan Absensi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showToast(type, title, msg, duration = 4000) {
        const wrap = document.getElementById('toastWrap');
        const icons = {
            error: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`
        };
        const t = document.createElement('div');
        t.className = `toast ${type}`;
        t.innerHTML = `<div class="toast-ico">${icons[type]||''}</div><div><div class="toast-title">${title}</div><div class="toast-msg">${msg}</div></div>`;
        wrap.appendChild(t);
        requestAnimationFrame(() => requestAnimationFrame(() => t.classList.add('show')));
        setTimeout(() => {
            t.classList.remove('show');
            setTimeout(() => t.remove(), 400);
        }, duration);
    }
    (function() {
        const w = document.getElementById('toastWrap');
        if (!w) return;
        if (w.dataset.error) showToast('error', 'Gagal!', w.dataset.error);
    })();
</script>
@endpush