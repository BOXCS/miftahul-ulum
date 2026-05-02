@extends('layouts.app')

@section('title', 'Data Santri')
@section('breadcrumb', 'Data Santri')
@push('styles')
<style>
    /* ── TEAL TOKENS ── */
    :root {
        --teal-50: #f0fdfa;
        --teal-100: #ccfbf1;
        --teal-200: #99f6e4;
        --teal-400: #2dd4bf;
        --teal-500: #14b8a6;
        --teal-600: #0d9488;
        --teal-700: #0f766e;
        --teal-800: #115e59;
        --teal-900: #134e4a;
        --sh-teal: 0 8px 28px rgba(13, 148, 136, .22);
        --sh-teal-sm: 0 4px 14px rgba(13, 148, 136, .18);
    }

    /* ── BTN TEAL ── */
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
        letter-spacing: -.01em;
    }

    .btn-teal:hover {
        background: linear-gradient(135deg, #0f766e, #115e59);
        transform: translateY(-2px);
        box-shadow: var(--sh-teal);
        color: #fff;
    }

    .btn-teal-sm {
        padding: 6px 14px;
        font-size: .75rem;
        border-radius: 9px;
    }

    /* ── STAT MINI CARDS ── */
    .st-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 14px;
        transition: transform .2s, box-shadow .2s;
        position: relative;
        overflow: hidden;
    }

    .st-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(15, 23, 42, .1);
    }

    .st-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 16px 16px 0 0;
    }

    .st-card.tc-teal::before {
        background: linear-gradient(90deg, #0d9488, #2dd4bf);
    }

    .st-card.tc-blue::before {
        background: linear-gradient(90deg, #2563eb, #60a5fa);
    }

    .st-card.tc-pink::before {
        background: linear-gradient(90deg, #db2777, #f472b6);
    }

    .st-card.tc-green::before {
        background: linear-gradient(90deg, #16a34a, #4ade80);
    }

    .st-ico {
        width: 44px;
        height: 44px;
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .st-val {
        font-size: 1.75rem;
        font-weight: 800;
        color: #0f172a;
        line-height: 1;
        letter-spacing: -.04em;
    }

    .st-lbl {
        font-size: .68rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-top: 3px;
    }

    /* ── TABLE CARD ── */
    .tcard {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    /* ── FILTER STRIP ── */
    .filter-strip {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .filter-search {
        position: relative;
        flex: 1;
        min-width: 200px;
    }

    .filter-search input {
        width: 100%;
        padding: 9px 14px 9px 38px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .8rem;
        background: #f8fafc;
        font-family: inherit;
        outline: none;
        transition: border .2s, box-shadow .2s;
        color: #334155;
    }

    .filter-search input:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);
        background: #fff;
    }

    .filter-search svg {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #94a3b8;
    }

    .filter-select {
        appearance: none;
        padding: 9px 32px 9px 12px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .8rem;
        font-family: inherit;
        color: #475569;
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;
        outline: none;
        cursor: pointer;
        transition: border .2s;
    }

    .filter-select:focus {
        border-color: #0d9488;
    }

    /* ── DATA TABLE ── */
    .dt {
        width: 100%;
        border-collapse: collapse;
    }

    .dt thead th {
        padding: 11px 16px;
        font-size: .80rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: #94a3b8;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        white-space: nowrap;
    }

    .dt tbody td {
        padding: 13px 16px;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }

    .dt tbody tr:hover td {
        background: #f0fdfa;
    }

    .dt tbody tr:last-child td {
        border-bottom: none;
    }

    /* ── AVATAR ── */
    .ava {
        width: 36px;
        height: 36px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .65rem;
        font-weight: 800;
        flex-shrink: 0;
    }

    /* ── BADGES ── */
    .bdg {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: .65rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
        letter-spacing: .03em;
        text-transform: capitalize;
    }

    .bdg-teal {
        background: #ccfbf1;
        color: #0f766e;
    }

    .bdg-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .bdg-pink {
        background: #fce7f3;
        color: #be185d;
    }

    .bdg-green {
        background: #dcfce7;
        color: #15803d;
    }

    .bdg-amber {
        background: #fef3c7;
        color: #b45309;
    }

    .bdg-red {
        background: #fee2e2;
        color: #dc2626;
    }

    .bdg-slate {
        background: #f1f5f9;
        color: #475569;
    }

    /* ── ICON BTN ── */
    .ico-btn {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .18s;
        background: transparent;
        text-decoration: none;
    }

    .ico-btn:hover {
        transform: scale(1.08);
    }

    .ico-btn.edit {
        background: #f0fdfa;
        color: #0d9488;
    }

    .ico-btn.edit:hover {
        background: #ccfbf1;
    }

    .ico-btn.del {
        background: #fff1f2;
        color: #e11d48;
    }

    .ico-btn.del:hover {
        background: #fee2e2;
    }

    /* ── MODAL ── */
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
        box-shadow: 0 24px 64px rgba(15, 23, 42, .18), 0 8px 20px rgba(15, 23, 42, .08);
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

    .smodal-hdr {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px 16px;
        border-bottom: 1px solid #f1f5f9;
        flex-shrink: 0;
    }

    .smodal-hdr-ico {
        width: 36px;
        height: 36px;
        border-radius: 11px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .smodal-title {
        font-size: 1rem;
        font-weight: 800;
        color: #0f172a;
        letter-spacing: -.02em;
    }

    .smodal-sub {
        font-size: .72rem;
        color: #94a3b8;
        font-weight: 500;
        margin-top: 2px;
    }

    .smodal-close {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: #f1f5f9;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-size: 1rem;
        transition: all .18s;
    }

    .smodal-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .smodal-body {
        overflow-y: auto;
        flex: 1;
        padding: 20px 24px;
    }

    .smodal-body::-webkit-scrollbar {
        width: 4px;
    }

    .smodal-body::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 2px;
    }

    .smodal-ftr {
        padding: 14px 24px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
        flex-shrink: 0;
        background: #f8fafc;
    }

    /* ── TOAST ── */
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
        box-shadow: 0 8px 30px rgba(15, 23, 42, .14), 0 2px 8px rgba(15, 23, 42, .08);
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

    .toast.success {
        border-color: #0d9488;
    }

    .toast.error {
        border-color: #e11d48;
    }

    .toast.warning {
        border-color: #d97706;
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

    .toast.success .toast-ico {
        background: #ccfbf1;
        color: #0d9488;
    }

    .toast.error .toast-ico {
        background: #fee2e2;
        color: #e11d48;
    }

    .toast.warning .toast-ico {
        background: #fef3c7;
        color: #d97706;
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

    /* ── DELETE MODAL ── */
    .del-modal-box {
        max-width: 420px;
    }

    .del-warning-box {
        background: #fff7f7;
        border: 1px solid #fecaca;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 16px;
    }

    /* ── EMPTY STATE ── */
    .empty-row td {
        padding: 60px 20px;
        text-align: center;
        color: #94a3b8;
    }

    .empty-ico-wrap {
        width: 56px;
        height: 56px;
        background: #f1f5f9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 10px;
        font-size: 1.5rem;
    }

    /* ── RESULT INFO BAR ── */
    .result-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 8px 20px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        font-size: .72rem;
        color: #64748b;
    }

    .result-bar strong {
        color: #0f172a;
        font-weight: 700;
    }

    /* ── FORM FIELDS ── */
    .finput {
        width: 100%;
        padding: 9px 13px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .82rem;
        font-family: inherit;
        color: #1e293b;
        background: #f8fafc;
        outline: none;
        transition: border .2s, box-shadow .2s, background .2s;
    }

    .finput:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);
        background: #fff;
    }

    /* Tambahkan setelah .dt tbody td { ... } */
    .dt tbody td span {
        font-size: .85rem !important;
    }
</style>
@endpush

@section('content')

{{-- ── TOAST CONTAINER ── --}}
<div class="toast-wrap"
    id="toastWrap"
    data-success="{{ session('success') }}"
    data-error="{{ session('error') }}">
</div>


{{-- ── PAGE HEADER ── --}}
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Data Santri</h1>
        <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Kelola data santri pesantren Miftahul Ulum</p>
    </div>
    <a href="{{ route('students.create') }}" class="btn-teal">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Tambah Santri
    </a>
</div>

{{-- ── STAT CARDS ── --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">

    <div class="st-card tc-teal">
        <div class="st-ico" style="background:#ccfbf1;color:#0f766e;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                <circle cx="9" cy="7" r="4" />
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['total'] }}</div>
            <div class="st-lbl">Total Santri</div>
        </div>
    </div>

    <div class="st-card tc-blue">
        <div class="st-ico" style="background:#dbeafe;color:#2563eb;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['laki_laki'] }}</div>
            <div class="st-lbl">Laki-laki</div>
        </div>
    </div>

    <div class="st-card tc-pink">
        <div class="st-ico" style="background:#fce7f3;color:#db2777;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                <circle cx="12" cy="7" r="4" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['perempuan'] }}</div>
            <div class="st-lbl">Perempuan</div>
        </div>
    </div>

    <div class="st-card tc-green">
        <div class="st-ico" style="background:#dcfce7;color:#15803d;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['aktif'] }}</div>
            <div class="st-lbl">Aktif</div>
        </div>
    </div>

</div>

{{-- ── TABLE CARD ── --}}
<div class="tcard" x-data="{
    search: '',
    kelasFilter: '',
    statusFilter: '',
    students: @js($students),
    get filtered() {
        return this.students.filter(s => {
            const q = this.search.toLowerCase();
            const matchS = !q || s.name.toLowerCase().includes(q) || (s.nis||'').includes(q) || (s.class||'').toLowerCase().includes(q);
            const matchK = !this.kelasFilter || s.class === this.kelasFilter;
            const matchSt = !this.statusFilter || s.status === this.statusFilter;
            return matchS && matchK && matchSt;
        });
    },
    initials(n){ return n.split(' ').slice(0,2).map(w=>w[0]).join('').toUpperCase(); }
}">

    {{-- Filter Strip --}}
    <div class="filter-strip">
        <div class="filter-search" style="flex:1;min-width:180px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="search" placeholder="Cari nama, NIS, kelas…" x-model="search" autocomplete="off">
        </div>
        <select class="filter-select" x-model="kelasFilter" style="min-width:130px;">
            <option value="">Semua Kelas</option>
            @foreach($kelas_options as $opt)
            <option value="{{ $opt }}">Kelas {{ $opt }}</option>
            @endforeach
        </select>
        <select class="filter-select" x-model="statusFilter" style="min-width:130px;">
            <option value="">Semua Status</option>
            <option value="aktif">Aktif</option>
            <option value="alumni">Alumni</option>
            <option value="keluar">Keluar</option>
        </select>
    </div>

    {{-- Result Bar --}}
    <div class="result-bar">
        <span>Menampilkan <strong x-text="filtered.length"></strong> dari <strong x-text="students.length"></strong> santri</span>
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table class="dt">
            <thead>
                <tr>
                    <th style="width:48px;text-align:center;">No</th>
                    <th>NIS</th>
                    <th>Nama Santri</th>
                    <th style="text-align:center;">Kelas</th>
                    <th style="text-align:center;">Kelamin</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;width:90px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="empty-row" x-show="filtered.length === 0">
                    <td colspan="7">
                        <div class="empty-ico-wrap">📋</div>
                        <p style="font-size:.82rem;font-weight:600;">Tidak ada data ditemukan</p>
                    </td>
                </tr>
                <template x-for="(s, idx) in filtered" :key="s.id">
                    <tr>
                        <td style="text-align:center;">
                            <span style="font-size:.75rem;color:#94a3b8;font-weight:600;" x-text="idx+1"></span>
                        </td>
                        <td>
                            <span style="font-size:.78rem;font-family:monospace;color:#64748b;" x-text="s.nis||'-'"></span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:11px;">
                                <div class="ava" :style="s.gender==='Perempuan'?'background:#fce7f3;color:#db2777;':'background:#dbeafe;color:#2563eb;'">
                                    <span x-text="initials(s.name)"></span>
                                </div>
<span style="font-size:.82rem;font-weight:700;color:#1e293b;" x-text="s.name"></span>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <span class="bdg bdg-teal" x-text="s.class"></span>
                        </td>
                        <td style="text-align:center;">
                            <span class="bdg" :class="s.gender==='Perempuan'?'bdg-pink':'bdg-blue'" x-text="s.gender"></span>
                        </td>
                        <td style="text-align:center;">
                            <span class="bdg" :class="s.status==='aktif'?'bdg-green':(s.status==='keluar'?'bdg-red':'bdg-amber')" x-text="s.status"></span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">

                                {{-- Sidik Jari --}}
                                <button type="button" class="ico-btn edit" title="Sidik Jari"
                                    @click="$dispatch('open-fingerprint-modal', { student: s })">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 2a10 10 0 0 0-10 10v2a10 10 0 0 0 10 10 10 10 0 0 0 10-10v-2a10 10 0 0 0-10-10z" />
                                        <path d="M12 6a6 6 0 0 0-6 6v2a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-2a6 6 0 0 0-6-6z" />
                                        <path d="M12 10a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2 2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2z" />
                                    </svg>
                                </button>

                                {{-- Edit --}}
                                <a :href="`/students/${s.id}/edit`" class="ico-btn edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </a>

                                {{-- Hapus --}}
                                <button class="ico-btn del" title="Hapus" @click="openDeleteModal(s)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="1.8"
                                        stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

</div>{{-- /tcard --}}


{{-- ════════════════════════════════════
     MODAL — KONFIRMASI HAPUS
════════════════════════════════════ --}}
<div class="smodal-overlay" id="deleteModal">
    <div class="smodal-box del-modal-box">

        <div class="smodal-hdr">
            <div style="display:flex;align-items:center;gap:12px;">
                <div class="smodal-hdr-ico" style="background:#fee2e2;color:#dc2626;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <line x1="12" y1="17" x2="12.01" y2="17" />
                    </svg>
                </div>
                <div>
                    <div class="smodal-title" style="color:#dc2626;">Hapus Data Santri</div>
                    <div class="smodal-sub">Tindakan ini tidak dapat dibatalkan</div>
                </div>
            </div>
            <button class="smodal-close" onclick="closeDeleteModal()">✕</button>
        </div>

        <div class="smodal-body">
            <div class="del-warning-box">
                <p style="font-size:.82rem;color:#7f1d1d;line-height:1.6;">
                    Anda akan menghapus data santri <strong id="delStudentName" style="font-weight:800;">—</strong>.<br>
                    Seluruh riwayat absensi dan data terkait akan <strong>terhapus permanen</strong>.
                </p>
            </div>
            <form id="deleteForm" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        </div>

        <div class="smodal-ftr">
            <button type="button" onclick="closeDeleteModal()"
                style="padding:8px 18px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.8rem;font-weight:700;color:#475569;cursor:pointer;font-family:inherit;">
                Tidak
            </button>

            <button id="delConfirmBtn" onclick="submitDelete()"
                style="padding:8px 18px;border-radius:10px;background:#e11d48;color:#fff;font-size:.8rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;transition:all .2s;display:flex;align-items:center;gap:7px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                </svg>
                Ya
            </button>
        </div>
    </div>
</div>

    {{-- FINGERPRINT MODAL --}}
    <div x-data="{
        open: false,
        student: null,
        status: 'ready', // ready, scanning, success, failed
        quality: 0,
        template: '',
        errorMessage: '',
        openModal(payload) {
            this.student = payload.student;
            this.status = 'ready';
            this.quality = 0;
            this.template = '';
            this.errorMessage = '';
            this.open = true;
        },
        closeModal() {
            this.open = false;
        },
        async scanFingerprint() {
            this.status = 'scanning';
            this.errorMessage = '';
            this.quality = 0;
            
            try {
                // Simulasi pemanggilan API Web Fingerprint Scanner
                // Menggunakan Promise dengan setTimeout
                const result = await new Promise((resolve, reject) => {
                    setTimeout(() => {
                        // Simulasi success rate 80%
                        if (Math.random() > 0.2) {
                            resolve({
                                success: true,
                                quality: Math.floor(Math.random() * 20) + 80, // Quality 80-100
                                template: 'BASE64_TEMPLATE_' + Math.random().toString(36).substring(7)
                            });
                        } else {
                            reject(new Error('Kualitas scan buruk. Silakan coba lagi.'));
                        }
                    }, 2000);
                });
                
                this.quality = result.quality;
                this.template = result.template;
                this.status = 'success';
            } catch (error) {
                this.status = 'failed';
                this.errorMessage = error.message || 'Gagal terhubung dengan perangkat scanner.';
            }
        },
        async saveFingerprint() {
            if (!this.template || this.status !== 'success') return;
            
            try {
                const response = await fetch('/students/' + this.student.id + '/fingerprint', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        fingerprint_template: this.template,
                        fingerprint_quality: this.quality
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Data sidik jari berhasil disimpan!');
                    this.closeModal();
                } else {
                    throw new Error(data.message || 'Gagal menyimpan data.');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }
    }" @open-fingerprint-modal.window="openModal($event.detail)">

        <div class="modal-overlay" x-show="open" style="display:none;" @click.self="closeModal()">
            <div class="modal-box max-w-md w-full mx-4">
                <div class="modal-header">
                    <h2 class="text-base font-bold text-slate-800">Scan Sidik Jari</h2>
                    <button type="button" @click="closeModal()">&times;</button>
                </div>
                <div class="modal-body text-center py-6">
                    <h3 class="font-medium text-lg mb-2" x-text="student ? student.name : ''"></h3>
                    <p class="text-sm text-slate-500 mb-6" x-text="student ? 'NIS: ' + student.nis : ''"></p>
                    
                    <div class="flex justify-center mb-6">
                        <div class="w-32 h-32 rounded-full flex items-center justify-center border-4"
                            :class="{
                                'border-slate-200 text-slate-400': status === 'ready',
                                'border-blue-500 text-blue-500 animate-pulse': status === 'scanning',
                                'border-green-500 text-green-500': status === 'success',
                                'border-red-500 text-red-500': status === 'failed'
                            }">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2a10 10 0 0 0-10 10v2a10 10 0 0 0 10 10 10 10 0 0 0 10-10v-2a10 10 0 0 0-10-10z" />
                                <path d="M12 6a6 6 0 0 0-6 6v2a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-2a6 6 0 0 0-6-6z" />
                                <path d="M12 10a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2 2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="mb-4 h-6">
                        <p x-show="status === 'ready'" class="text-slate-600">Tekan tombol di bawah untuk memulai scan</p>
                        <p x-show="status === 'scanning'" class="text-blue-600 font-medium">Sedang memindai... Letakkan jari pada scanner.</p>
                        <p x-show="status === 'success'" class="text-green-600 font-medium">
                            Scan berhasil! Kualitas: <span x-text="quality + '%'"></span>
                        </p>
                        <p x-show="status === 'failed'" class="text-red-600 font-medium" x-text="errorMessage"></p>
                    </div>
                    
                    <div x-show="status === 'success'" class="w-full bg-slate-200 rounded-full h-2.5 mb-6">
                        <div class="bg-green-600 h-2.5 rounded-full" :style="'width: ' + quality + '%'"></div>
                    </div>

                    <div class="flex justify-center gap-3">
                        <button type="button" class="btn" 
                            :class="status === 'scanning' ? 'btn-secondary opacity-50 cursor-not-allowed' : 'btn-primary'" 
                            @click="scanFingerprint()"
                            :disabled="status === 'scanning'">
                            <span x-text="status === 'success' || status === 'failed' ? 'Re-scan Fingerprint' : 'Scan Fingerprint'"></span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer flex justify-between">
                    <button type="button" class="btn btn-secondary" @click="closeModal()">Batal</button>
                    <button type="button" class="btn btn-primary" 
                        @click="saveFingerprint()"
                        :disabled="status !== 'success'"
                        :class="{'opacity-50 cursor-not-allowed': status !== 'success'}">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ── TOAST ──────────────────────────────────────────
    function showToast(type, title, msg, duration = 4000) {
        const wrap = document.getElementById('toastWrap');
        const t = document.createElement('div');
        const icons = {
            success: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`,
            warning: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>`
        };
        t.className = `toast ${type}`;
        t.innerHTML = `<div class="toast-ico">${icons[type]||icons.success}</div><div><div class="toast-title">${title}</div><div class="toast-msg">${msg}</div></div>`;
        wrap.appendChild(t);
        requestAnimationFrame(() => {
            requestAnimationFrame(() => t.classList.add('show'));
        });
        setTimeout(() => {
            t.classList.remove('show');
            setTimeout(() => t.remove(), 400);
        }, duration);
    }

    function initSessionToasts() {
        const wrap = document.getElementById('toastWrap');
        if (!wrap) return;

        const successMsg = wrap.dataset.success || '';
        const errorMsg = wrap.dataset.error || '';

        if (successMsg) {
            showToast('success', 'Berhasil!', successMsg);
        }

        if (errorMsg) {
            showToast('error', 'Gagal!', errorMsg);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSessionToasts);
    } else {
        initSessionToasts();
    }
    // ── DELETE MODAL ─────────────────────────────────
    let deleteTargetUrl = null;

    function openDeleteModal(student) {
        deleteTargetUrl = `/students/${student.id}`;
        document.getElementById('delStudentName').textContent = student.name;
        document.getElementById('deleteModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    function submitDelete() {
        const form = document.getElementById('deleteForm');
        form.action = deleteTargetUrl;
        form.submit();
    }

    // ── CLOSE ON OVERLAY CLICK / ESC ────────────────
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endpush