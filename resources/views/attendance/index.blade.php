@extends('layouts.app')

@section('title', 'Presensi Santri')
@section('breadcrumb', 'Presensi')
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

    .btn-outline {
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

    .btn-outline:hover {
        background: #f0fdfa;
        border-color: #0d9488;
        color: #0d9488;
    }

    .st-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        padding: 16px 18px;
        display: flex;
        align-items: center;
        gap: 12px;
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
        background: linear-gradient(90deg, #0d9488, #5eead4);
    }

    .st-card.tc-blue::before {
        background: linear-gradient(90deg, #2563eb, #60a5fa);
    }

    .st-card.tc-amber::before {
        background: linear-gradient(90deg, #d97706, #fbbf24);
    }

    .st-card.tc-rose::before {
        background: linear-gradient(90deg, #db2777, #f472b6);
    }

    .st-card.tc-red::before {
        background: linear-gradient(90deg, #dc2626, #f87171);
    }

    .st-ico {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .st-val {
        font-size: 1.6rem;
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

    .st-desc {
        font-size: .65rem;
        color: #b0bec5;
        margin-top: 2px;
        font-weight: 500;
    }

    .filter-bar {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 14px 20px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
        background: #f8fafc;
    }

    .finput,
    .fselect {
        padding: 9px 14px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .82rem;
        font-family: inherit;
        color: #1e293b;
        background: #fff;
        outline: none;
        transition: border .2s, box-shadow .2s;
    }

    .finput:focus,
    .fselect:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .1);
    }

    .fselect {
        appearance: none;
        cursor: pointer;
        padding-right: 32px;
        background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;
    }

    .tcard {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .dt {
        width: 100%;
        border-collapse: collapse;
    }

    .dt thead th {
        padding: 11px 14px;
        font-size: .67rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #94a3b8;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        white-space: nowrap;
    }

    .dt thead th.teal-col {
        color: #0d9488 !important;
        background: #f0fdfa !important;
        position: relative;
    }

    .dt thead th.teal-col::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #0d9488, #5eead4);
    }

    .dt tbody td {
        padding: 11px 14px;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
        font-size: .84rem;
    }

    .dt tbody td.teal-cell {
        background: #f0fdfa !important;
    }

    .dt tbody tr:hover td {
        background: #f0fdfa;
    }

    .dt tbody tr:last-child td {
        border-bottom: none;
    }

    .ava {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .7rem;
        font-weight: 800;
        flex-shrink: 0;
        color: #fff;
    }

    .bdg {
        display: inline-flex;
        align-items: center;
        font-size: .63rem;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 999px;
        letter-spacing: .03em;
    }

    .bdg-teal {
        background: #ccfbf1;
        color: #0f766e;
    }

    .bdg-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .bdg-amber {
        background: #fef3c7;
        color: #b45309;
    }

    .bdg-rose {
        background: #fce7f3;
        color: #be185d;
    }

    .bdg-red {
        background: #fee2e2;
        color: #dc2626;
    }

    .bdg-slate {
        background: #f1f5f9;
        color: #475569;
    }

    .cell-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #94a3b8;
        cursor: pointer;
        margin-left: 4px;
        transition: all .15s;
        flex-shrink: 0;
    }

    .cell-edit-btn:hover {
        background: #f0fdfa;
        border-color: #99f6e4;
        color: #0d9488;
    }

    .ket-pill {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: .7rem;
        color: #64748b;
        background: #f1f5f9;
        border-radius: 6px;
        padding: 2px 7px;
        max-width: 140px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Action buttons */
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
        padding: 20px 24px;
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

    @keyframes ping {

        75%,
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }

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

    .toast.success {
        border-color: #0d9488;
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

    .toast.success .toast-ico {
        background: #ccfbf1;
        color: #0d9488;
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

<div class="toast-wrap" id="toastWrap"
    data-success="{{ session('success') }}"
    data-error="{{ session('error') }}">
</div>

{{-- PAGE HEADER --}}
<div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Presensi Santri</h1>
        <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Rekap kehadiran santri – 5 waktu shalat</p>
    </div>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a href="{{ route('attendance.create') }}" class="btn-teal">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Catat Absensi
        </a>
        <a href="{{ route('attendance.report') }}" class="btn-outline">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
            </svg>
            Lihat Laporan
        </a>

    <div class="flex items-center gap-3">
        <button type="button" class="btn btn-primary bg-indigo-600 hover:bg-indigo-700 text-white" @click="$dispatch('open-verify-modal')">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12 2a10 10 0 0 0-10 10v2a10 10 0 0 0 10 10 10 10 0 0 0 10-10v-2a10 10 0 0 0-10-10z" />
                <path d="M12 6a6 6 0 0 0-6 6v2a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-2a6 6 0 0 0-6-6z" />
                <path d="M12 10a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2 2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2z" />
            </svg>
            Scan Absensi
        </button>
        <form action="{{ route('attendance.index') }}" method="GET" class="flex items-center gap-2">
            <input type="date" name="tanggal" x-model="tanggal" class="form-control form-control-sm" onchange="this.form.submit()">
            <select name="waktu_shalat" x-model="waktu_shalat" class="form-select form-select-sm" onchange="this.form.submit()">
                @foreach($prayers as $p)
                    <option value="{{ $p }}" {{ $waktu_shalat == $p ? 'selected' : '' }}>{{ $p }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

{{-- STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:12px;margin-bottom:22px;">
    <div class="st-card tc-teal">
        <div class="st-ico" style="background:#ccfbf1;color:#0f766e;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                <polyline points="22 4 12 14.01 9 11.01" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['hadir'] }}</div>
            <div class="st-lbl">Hadir</div>
            <div class="st-desc">Tepat waktu</div>
        </div>
    </div>
    <div class="st-card tc-blue">
        <div class="st-ico" style="background:#dbeafe;color:#1d4ed8;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <polyline points="12 6 12 12 16 14" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['terlambat'] }}</div>
            <div class="st-lbl">Terlambat</div>
            <div class="st-desc">Lewat batas waktu</div>
        </div>
    </div>
    <div class="st-card tc-amber">
        <div class="st-ico" style="background:#fef3c7;color:#b45309;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['izin'] }}</div>
            <div class="st-lbl">Izin</div>
            <div class="st-desc">Ada keterangan</div>
        </div>
    </div>
    <div class="st-card tc-rose">
        <div class="st-ico" style="background:#fce7f3;color:#be185d;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['sakit'] }}</div>
            <div class="st-lbl">Sakit</div>
            <div class="st-desc">Perlu istirahat</div>
        </div>
    </div>
    <div class="st-card tc-red">
        <div class="st-ico" style="background:#fee2e2;color:#dc2626;">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="15" y1="9" x2="9" y2="15" />
                <line x1="9" y1="9" x2="15" y2="15" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['alpha'] }}</div>
            <div class="st-lbl">Alpha</div>
            <div class="st-desc">Tanpa keterangan</div>
        </div>
    </div>
</div>

{{-- TABLE CARD --}}
<div class="tcard" x-data="{
    tanggal: '{{ $today }}',
    waktu_shalat: 'semua',
    filterStatus: 'semua',
    prayers: @js($prayers),
    students: @js($students),
    allAttendances: @js($allAttendances),
    search: '',
    currentPage: 1,
    perPage: 10,

    /* Edit modal */
    editModal: false,
    editForm: { id: null, student_id: '', student_name: '', waktu: '', status: 'hadir', keterangan: '', jam_masuk: '', jam_keluar: '' },

    /* Delete modal */
    deleteModal: false,
    deleteTarget: null,

    get showJamCols() {
        return this.waktu_shalat !== 'semua' || this.filterStatus !== 'semua';
    },

    get filtered() {
        const q = this.search.toLowerCase();
        return this.students.filter(s => {
            if (q && !s.name.toLowerCase().includes(q) && !(s.class||'').toLowerCase().includes(q)) return false;
            if (this.filterStatus !== 'semua') {
                const att = this.getAtt(s.id, this.waktu_shalat === 'semua' ? this.prayers[0] : this.waktu_shalat);
                if (this.waktu_shalat === 'semua') {
                    const anyMatch = this.prayers.some(p => this.getAtt(s.id, p).status === this.filterStatus);
                    if (!anyMatch) return false;
                } else {
                    if (att.status !== this.filterStatus) return false;
                }
            }
            return true;
        });
    },
    get totalPages() { return Math.ceil(this.filtered.length / this.perPage) || 1; },
    get paginated() {
        if (this.currentPage > this.totalPages) this.currentPage = this.totalPages;
        const start = (this.currentPage - 1) * this.perPage;
        return this.filtered.slice(start, start + this.perPage);
    },
    get startNumber() { return (this.currentPage - 1) * this.perPage; },
    get pages() {
        const total = this.totalPages, current = this.currentPage, maxV = 10;
        let start = Math.max(1, current - 4);
        let end = Math.min(total, start + maxV - 1);
        if (end - start < maxV - 1) start = Math.max(1, end - maxV + 1);
        let arr = [];
        for (let i = start; i <= end; i++) arr.push(i);
        return arr;
    },
    goPage(page) {
        if (page < 1 || page > this.totalPages) return;
        this.currentPage = page;
    },
    getAtt(studentId, waktu) {
        const key = studentId + '_' + waktu;
        return this.allAttendances[key] || { status: 'alpha', keterangan: '', jam_masuk: null, jam_keluar: null };
    },
    statusClass(s) {
        const m = { hadir:'bdg-teal', terlambat:'bdg-blue', izin:'bdg-amber', sakit:'bdg-rose', alpha:'bdg-red' };
        return m[s] || 'bdg-slate';
    },
    avatarColor(studentId) {
        const colors = [
            'background:linear-gradient(135deg,#0d9488,#5eead4)',
            'background:linear-gradient(135deg,#2563eb,#60a5fa)',
            'background:linear-gradient(135deg,#7c3aed,#a78bfa)',
            'background:linear-gradient(135deg,#d97706,#fbbf24)',
            'background:linear-gradient(135deg,#db2777,#f472b6)',
            'background:linear-gradient(135deg,#0891b2,#67e8f9)',
        ];
        return colors[studentId % colors.length];
    },
    formatTime(t) {
        if (!t) return '—';
        try { return new Date(t).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'}); } catch(e) { return '—'; }
    },
    isActiveCol(w) { return this.waktu_shalat !== 'semua' && w === this.waktu_shalat; },

    openEdit(student, waktu) {
        const att = this.getAtt(student.id, waktu);
        this.editForm = {
            id: att.id || null,
            student_id: student.id,
            student_name: student.name,
            waktu: waktu,
            status: att.status || 'hadir',
            keterangan: att.keterangan || '',
            jam_masuk: att.jam_masuk ? this.formatTime(att.jam_masuk) : '',
            jam_keluar: att.jam_keluar ? this.formatTime(att.jam_keluar) : ''
        };
        this.editModal = true;
        document.body.style.overflow = 'hidden';
    },
    closeEdit() {
        this.editModal = false;
        document.body.style.overflow = '';
    },
    submitEdit() {
        document.getElementById('editAttForm').submit();
    },

    openDelete(student, waktu) {
        const att = this.getAtt(student.id, waktu);
        this.deleteTarget = { id: att.id, student_name: student.name, waktu: waktu };
        this.deleteModal = true;
        document.body.style.overflow = 'hidden';
    },
    closeDelete() {
        this.deleteModal = false;
        document.body.style.overflow = '';
    },
    submitDelete() {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '/attendance/' + this.deleteTarget.id;
        const t = document.createElement('input'); t.type='hidden'; t.name='_token'; t.value=document.querySelector('meta[name=csrf-token]').content; f.appendChild(t);
        const m = document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='DELETE'; f.appendChild(m);
        document.body.appendChild(f); f.submit();
    },

    avatarStyleByStatus(s) {
        const m = {
            hadir:     'background:linear-gradient(135deg,#0d9488,#5eead4)',
            terlambat: 'background:linear-gradient(135deg,#2563eb,#60a5fa)',
            izin:      'background:linear-gradient(135deg,#d97706,#fbbf24)',
            sakit:     'background:linear-gradient(135deg,#db2777,#f472b6)',
            alpha:     'background:linear-gradient(135deg,#dc2626,#f87171)'
        };
        return m[s] || 'background:linear-gradient(135deg,#64748b,#94a3b8)';
    }
}" @keydown.escape.window="editModal && closeEdit(); deleteModal && closeDelete()">

    {{-- Filter bar --}}
    <div class="filter-bar">
        <form action="{{ route('attendance.index') }}" method="GET"
            style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;" id="filterForm">
            <div style="display:flex;align-items:center;gap:6px;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;padding:6px 12px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" />
                    <line x1="16" y1="2" x2="16" y2="6" />
                    <line x1="8" y1="2" x2="8" y2="6" />
                    <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <input type="date" name="tanggal" value="{{ $today }}"
                    style="border:none;background:transparent;padding:0;outline:none;font-size:.82rem;font-family:inherit;color:#1e293b;"
                    onchange="this.form.submit()">
            </div>
        </form>

        {{-- Filter Waktu Shalat (Alpine, no page reload) --}}
        <select class="fselect" x-model="waktu_shalat" @change="currentPage=1;filterStatus='semua'">
            <option value="semua">Semua Waktu</option>
            @foreach($prayers as $p)
            <option value="{{ $p }}">{{ $p }}</option>
            @endforeach
        </select>

        {{-- Filter Status (Alpine) --}}
        <select class="fselect" x-model="filterStatus" @change="currentPage=1">
            <option value="semua">Semua Status</option>
            <option value="hadir">Hadir</option>
            <option value="terlambat">Terlambat</option>
            <option value="izin">Izin</option>
            <option value="sakit">Sakit</option>
            <option value="alpha">Alpha</option>
        </select>

        <div style="position:relative;margin-left:auto;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#94a3b8" stroke-width="2"
                style="position:absolute;left:11px;top:50%;transform:translateY(-50%);pointer-events:none;">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="search" placeholder="Cari santri…" x-model="search" @input="currentPage=1"
                style="padding:9px 14px 9px 36px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:.8rem;background:#fff;font-family:inherit;outline:none;width:220px;color:#334155;"
                autocomplete="off">
        </div>
    </div>

    <div class="result-bar">
        <span>
            Menampilkan <strong x-text="filtered.length"></strong> dari <strong x-text="students.length"></strong> santri
            &nbsp;·&nbsp;
            <span style="color:#0f172a;font-weight:600;">{{ \Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM Y') }}</span>
            &nbsp;·&nbsp;
            <span style="display:inline-flex;align-items:center;gap:4px;background:#ccfbf1;color:#0d9488;font-weight:700;padding:2px 8px;border-radius:6px;" x-text="'Waktu: ' + (waktu_shalat === 'semua' ? 'Semua' : waktu_shalat)"></span>
        </span>
    </div>

    {{-- Table --}}
    <div style="overflow-x:auto;">
        <table class="dt">
            <thead>
                <tr>
                    <th style="width:44px;text-align:center;">No</th>
                    <th>Santri</th>
                    <th style="text-align:center;">Kelas</th>

                    {{-- Semua waktu: tampilkan semua kolom --}}
                    <template x-if="waktu_shalat === 'semua'">
                        <template x-for="p in prayers" :key="p">
                            <th style="text-align:center;min-width:90px;" x-text="p"></th>
                        </template>
                    </template>

                    {{-- Filter waktu tertentu: hanya kolom itu --}}
                    <template x-if="waktu_shalat !== 'semua'">
                        <th class="teal-col" style="text-align:center;min-width:100px;" x-text="waktu_shalat"></th>
                    </template>

                    {{-- Jam masuk & keluar hanya tampil saat filter aktif --}}
                    <template x-if="showJamCols">
                        <th style="text-align:center;min-width:80px;">Jam Masuk</th>
                    </template>
                    <template x-if="showJamCols">
                        <th style="text-align:center;min-width:80px;">Jam Keluar</th>
                    </template>

                    <th>Keterangan</th>
                    <th style="text-align:center;width:90px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr x-show="filtered.length === 0">
                    <td :colspan="6 + (waktu_shalat === 'semua' ? prayers.length : 1) + (showJamCols ? 2 : 0)"
                        style="padding:60px 20px;text-align:center;color:#94a3b8;">
                        <div style="width:56px;height:56px;background:#f1f5f9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 10px;font-size:1.5rem;">📋</div>
                        <p style="font-size:.82rem;font-weight:600;">Tidak ada santri ditemukan</p>
                    </td>
                </tr>

                <template x-for="(student, idx) in paginated" :key="student.id">
                    <tr>
                        <td style="text-align:center;">
                            <span style="font-size:.75rem;color:#94a3b8;font-weight:600;" x-text="startNumber + idx + 1"></span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="ava" :style="avatarColor(student.id)">
                                    <span x-text="student.name.charAt(0).toUpperCase()"></span>
                                </div>
                                <div>
                                    <span style="font-size:.85rem;font-weight:700;color:#1e293b;display:block;" x-text="student.name"></span>
                                    <span style="font-size:.72rem;color:#94a3b8;" x-text="'NIS: ' + (student.nis || '-')"></span>
                                </div>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <span style="display:inline-flex;align-items:center;justify-content:center;min-width:38px;height:26px;border-radius:8px;background:#f1f5f9;color:#475569;font-size:.75rem;font-weight:800;padding:0 6px;" x-text="student.class || '—'"></span>
                        </td>

                        {{-- Semua kolom waktu --}}
                        <template x-if="waktu_shalat === 'semua'">
                            <template x-for="p in prayers" :key="p">
                                <td style="text-align:center;">
                                    <div style="display:flex;align-items:center;justify-content:center;gap:3px;">
                                        <template x-if="getAtt(student.id, p).status === 'alpha'">
                                            <span style="position:relative;display:inline-flex;margin-right:2px;">
                                                <span style="position:absolute;display:inline-flex;width:100%;height:100%;border-radius:50%;background:#fca5a5;opacity:.75;animation:ping 1.2s cubic-bezier(0,0,.2,1) infinite;"></span>
                                                <span style="width:7px;height:7px;border-radius:50%;background:#ef4444;display:inline-flex;position:relative;"></span>
                                            </span>
                                        </template>
                                        <span class="bdg" :class="statusClass(getAtt(student.id, p).status)"
                                            x-text="getAtt(student.id, p).status"></span>
                                    </div>
                                </td>
                            </template>
                        </template>

                        {{-- Kolom waktu terpilih saja --}}
                        <template x-if="waktu_shalat !== 'semua'">
                            <td class="teal-cell" style="text-align:center;">
                                <div style="display:flex;align-items:center;justify-content:center;gap:3px;">
                                    <template x-if="getAtt(student.id, waktu_shalat).status === 'alpha'">
                                        <span style="position:relative;display:inline-flex;margin-right:2px;">
                                            <span style="position:absolute;display:inline-flex;width:100%;height:100%;border-radius:50%;background:#fca5a5;opacity:.75;animation:ping 1.2s cubic-bezier(0,0,.2,1) infinite;"></span>
                                            <span style="width:7px;height:7px;border-radius:50%;background:#ef4444;display:inline-flex;position:relative;"></span>
                                        </span>
                                    </template>
                                    <span class="bdg" :class="statusClass(getAtt(student.id, waktu_shalat).status)"
                                        x-text="getAtt(student.id, waktu_shalat).status"></span>
                                </div>
                            </td>
                        </template>

                        {{-- Jam masuk --}}
                        <template x-if="showJamCols">
                            <td style="text-align:center;">
                                <span style="font-size:.75rem;font-family:monospace;color:#475569;background:#f8fafc;padding:2px 8px;border-radius:6px;"
                                    x-text="waktu_shalat !== 'semua' ? formatTime(getAtt(student.id, waktu_shalat).jam_masuk) : '—'">
                                </span>
                            </td>
                        </template>

                        {{-- Jam keluar --}}
                        <template x-if="showJamCols">
                            <td style="text-align:center;">
                                <span style="font-size:.75rem;font-family:monospace;color:#475569;background:#f8fafc;padding:2px 8px;border-radius:6px;"
                                    x-text="waktu_shalat !== 'semua' ? formatTime(getAtt(student.id, waktu_shalat).jam_keluar) : '—'">
                                </span>
                            </td>
                        </template>

                        {{-- Keterangan --}}
                        <td>
                            <template x-if="waktu_shalat !== 'semua' && getAtt(student.id, waktu_shalat).keterangan">
                                <span class="ket-pill" x-text="getAtt(student.id, waktu_shalat).keterangan"></span>
                            </template>
                            <template x-if="waktu_shalat === 'semua' || !getAtt(student.id, waktu_shalat).keterangan">
                                <span style="color:#cbd5e1;font-size:.78rem;">—</span>
                            </template>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div style="display:flex;align-items:center;justify-content:center;gap:5px;">
                                <button type="button" class="ico-btn edit" title="Edit"
                                    @click="openEdit(student, waktu_shalat !== 'semua' ? waktu_shalat : prayers[0])">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </button>
                                <button type="button" class="ico-btn del" title="Hapus"
                                    @click="openDelete(student, waktu_shalat !== 'semua' ? waktu_shalat : prayers[0])">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6" />
                                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                        <path d="M10 11v6" />
                                        <path d="M14 11v6" />
                                        <path d="M9 6V4h6v2" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div style="padding:12px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
        <span style="font-size:.75rem;color:#64748b;">
            Menampilkan
            <strong style="color:#0f172a;" x-text="filtered.length === 0 ? 0 : startNumber + 1"></strong>–<strong style="color:#0f172a;" x-text="Math.min(startNumber + perPage, filtered.length)"></strong>
            dari <strong style="color:#0f172a;" x-text="filtered.length"></strong> santri
        </span>
        <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;">
            <button type="button" @click="goPage(currentPage-1)" :disabled="currentPage===1"
                style="padding:6px 10px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-size:.75rem;font-weight:700;cursor:pointer;"
                :style="currentPage===1?'opacity:.4;cursor:not-allowed;':''">‹</button>
            <template x-for="page in pages" :key="page">
                <button type="button" @click="goPage(page)"
                    style="min-width:32px;height:32px;border-radius:8px;border:1px solid #e2e8f0;font-size:.75rem;font-weight:800;cursor:pointer;"
                    :style="currentPage===page?'background:#0d9488;color:#fff;border-color:#0d9488;':'background:#fff;color:#475569;'"
                    x-text="page"></button>
            </template>
            <button type="button" @click="goPage(currentPage+1)" :disabled="currentPage===totalPages"
                style="padding:6px 10px;border-radius:8px;border:1px solid #e2e8f0;background:#fff;color:#475569;font-size:.75rem;font-weight:700;cursor:pointer;"
                :style="currentPage===totalPages?'opacity:.4;cursor:not-allowed;':''">›</button>
        </div>
    </div>

    {{-- ===== MODAL EDIT ===== --}}
    <div class="smodal-overlay" :class="{ open: editModal }" @click.self="closeEdit()">
        <div class="smodal-box" style="max-width:500px;">
            <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px 14px;border-bottom:1px solid #f1f5f9;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;border-radius:11px;background:#ccfbf1;color:#0d9488;display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:1rem;font-weight:800;color:#0f172a;">Edit Presensi</div>
                        <div style="font-size:.72rem;color:#94a3b8;" x-text="editForm.student_name + ' · ' + editForm.waktu"></div>
                    </div>
                </div>
                <button @click="closeEdit()"
                    style="width:30px;height:30px;border-radius:8px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;">✕</button>
            </div>

            <div class="smodal-body">
                <form action="{{ route('attendance.store') }}" method="POST" id="editAttForm">
                    @csrf
                    <input type="hidden" name="tanggal" :value="tanggal">
                    <input type="hidden" name="waktu_shalat" :value="editForm.waktu">

                    {{-- Student info strip --}}
                    <div style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#f0fdfa;border-radius:12px;border:1.5px solid #99f6e4;margin-bottom:20px;">
                        <div style="width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;color:#fff;flex-shrink:0;"
                            :style="avatarStyleByStatus(editForm.status)">
                            <span x-text="editForm.student_name ? editForm.student_name.charAt(0).toUpperCase() : ''"></span>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:700;color:#0f172a;" x-text="editForm.student_name"></div>
                            <div style="font-size:.72rem;color:#64748b;" x-text="editForm.waktu + ' · {{ \Carbon\Carbon::parse($today)->isoFormat('D MMMM Y') }}'"></div>
                        </div>
                    </div>

                    {{-- Status Grid --}}
                    <div style="margin-bottom:18px;">
                        <label style="display:block;font-size:.72rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;">
                            Status Kehadiran <span style="color:#e11d48;">*</span>
                        </label>
                        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;">
                            <template x-for="opt in ['hadir','terlambat','izin','sakit','alpha']" :key="opt">
                                <label style="display:flex;flex-direction:column;align-items:center;gap:5px;padding:10px 8px;border-radius:12px;border:2px solid;cursor:pointer;transition:all .18s;font-size:.72rem;font-weight:700;"
                                    :style="editForm.status === opt
                                        ? (opt==='hadir'    ? 'border-color:#0d9488;background:#f0fdfa;color:#0d9488;'
                                        : opt==='terlambat' ? 'border-color:#2563eb;background:#eff6ff;color:#1d4ed8;'
                                        : opt==='izin'      ? 'border-color:#d97706;background:#fffbeb;color:#b45309;'
                                        : opt==='sakit'     ? 'border-color:#db2777;background:#fdf2f8;color:#be185d;'
                                        :                     'border-color:#dc2626;background:#fff5f5;color:#dc2626;')
                                        : 'border-color:#e2e8f0;background:#f8fafc;color:#64748b;'">
                                    <input type="radio"
                                        :name="'attendances[' + editForm.student_id + '][status]'"
                                        :value="opt"
                                        x-model="editForm.status"
                                        style="display:none;">
                                    <template x-if="opt === 'hadir'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                            <polyline points="22 4 12 14.01 9 11.01" />
                                        </svg>
                                    </template>
                                    <template x-if="opt === 'terlambat'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                    </template>
                                    <template x-if="opt === 'izin'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg>
                                    </template>
                                    <template x-if="opt === 'sakit'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                        </svg>
                                    </template>
                                    <template x-if="opt === 'alpha'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="15" y1="9" x2="9" y2="15" />
                                            <line x1="9" y1="9" x2="15" y2="15" />
                                        </svg>
                                    </template>
                                    <span style="font-size:.7rem;text-transform:capitalize;" x-text="opt"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    {{-- Jam masuk & keluar --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px;">
                        <div>
                            <label style="display:block;font-size:.72rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:7px;">Jam Masuk</label>
                            <input type="time"
                                :name="'attendances[' + editForm.student_id + '][jam_masuk]'"
                                x-model="editForm.jam_masuk"
                                style="width:100%;padding:10px 14px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:.84rem;font-family:inherit;color:#334155;background:#f8fafc;outline:none;box-sizing:border-box;transition:border .2s;"
                                onfocus="this.style.borderColor='#0d9488';this.style.boxShadow='0 0 0 3px rgba(13,148,136,.1)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        </div>
                        <div>
                            <label style="display:block;font-size:.72rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:7px;">Jam Keluar</label>
                            <input type="time"
                                :name="'attendances[' + editForm.student_id + '][jam_keluar]'"
                                x-model="editForm.jam_keluar"
                                style="width:100%;padding:10px 14px;border-radius:10px;border:1.5px solid #e2e8f0;font-size:.84rem;font-family:inherit;color:#334155;background:#f8fafc;outline:none;box-sizing:border-box;transition:border .2s;"
                                onfocus="this.style.borderColor='#0d9488';this.style.boxShadow='0 0 0 3px rgba(13,148,136,.1)'"
                                onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'">
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label style="display:block;font-size:.72rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:7px;">
                            Keterangan <span style="font-size:.68rem;font-weight:500;text-transform:none;color:#94a3b8;">(opsional)</span>
                        </label>
                        <textarea
                            :name="'attendances[' + editForm.student_id + '][keterangan]'"
                            rows="2"
                            x-model="editForm.keterangan"
                            style="width:100%;padding:10px 14px;border-radius:11px;border:1.5px solid #e2e8f0;font-size:.83rem;font-family:inherit;color:#1e293b;background:#f8fafc;outline:none;resize:vertical;box-sizing:border-box;transition:border .2s;"
                            placeholder="Tambahkan keterangan jika perlu…"
                            onfocus="this.style.borderColor='#0d9488';this.style.boxShadow='0 0 0 3px rgba(13,148,136,.1)'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'"></textarea>
                    </div>
                </form>
            </div>

            <div class="smodal-ftr">
                <button type="button" @click="closeEdit()"
                    style="padding:9px 18px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.82rem;font-weight:700;color:#475569;cursor:pointer;font-family:inherit;">
                    Batal
                </button>
                <button type="submit" form="editAttForm"
                    style="padding:9px 22px;border-radius:10px;background:linear-gradient(135deg,#0d9488,#0f766e);color:#fff;font-size:.82rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;box-shadow:0 4px 12px rgba(13,148,136,.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL HAPUS ===== --}}
    <div class="smodal-overlay" :class="{ open: deleteModal }" @click.self="closeDelete()">
        <div class="smodal-box" style="max-width:400px;">
            <div class="smodal-body" style="text-align:center;padding:32px 24px 20px;">
                <div style="width:60px;height:60px;border-radius:18px;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                        <path d="M10 11v6" />
                        <path d="M14 11v6" />
                        <path d="M9 6V4h6v2" />
                    </svg>
                </div>
                <div style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">Hapus Presensi?</div>
                <p style="font-size:.82rem;color:#64748b;margin-bottom:14px;">Data presensi berikut akan dihapus permanen:</p>
                <div style="padding:12px 14px;background:#fff7f7;border:1px solid #fecaca;border-radius:12px;text-align:left;">
                    <div style="font-size:.85rem;font-weight:700;color:#0f172a;" x-text="deleteTarget?.student_name"></div>
                    <div style="font-size:.75rem;color:#64748b;margin-top:3px;" x-text="deleteTarget?.waktu"></div>
                </div>
            </div>
            <div class="smodal-ftr">
                <button @click="closeDelete()"
                    style="padding:8px 18px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.8rem;font-weight:700;color:#475569;cursor:pointer;font-family:inherit;">
                    Batal
                </button>
                <button @click="submitDelete()"
                    style="padding:8px 20px;border-radius:10px;background:#e11d48;color:#fff;font-size:.8rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="3 6 5 6 21 6" />
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                    </svg>
                    Ya, Hapus
{{-- FINGERPRINT VERIFY MODAL --}}
<div x-data="{
    open: false,
    status: 'ready', // ready, scanning, success, failed
    errorMessage: '',
    matchResult: null,
    
    openModal() {
        this.status = 'ready';
        this.errorMessage = '';
        this.matchResult = null;
        this.open = true;
    },
    
    closeModal() {
        this.open = false;
    },
    
    async scanAndVerify() {
        this.status = 'scanning';
        this.errorMessage = '';
        this.matchResult = null;
        
        try {
            // Simulasi scan (seperti di form pendaftaran, tapi ini hanya dapat 1 base64 template)
            // Dalam realitas kita memanggil API Scanner untuk mendapatkan template jari yang saat ini ditempelkan
            const result = await new Promise((resolve, reject) => {
                setTimeout(() => {
                    // Karena ini simulasi dan kita tidak bisa benar-benar menyamakan base64 random,
                    // Kita asumsikan mendapat string base64 tertentu.
                    // Jika ingin test error, ganti logika. Untuk testing, kita kirim sembarang yg disimulasikan error atau fix string jika tau.
                    // Pada implementasi asli, alat yang menggenerate template biometrik.
                    resolve({
                        template: 'BASE64_TEMPLATE_' // Ini harusnya valid base64 dari alat
                    });
                }, 1500);
            });
            
            // Verifikasi ke server
            // Untuk memastikan simulasi berjalan, biarkan server mereturn gagal atau sukses sesuai matching
            // Agar demo jalan, anggap saja berhasil. Tapi ini kode real:
            
            // Kita minta user input ID manual HANYA UNTUK KEPERLUAN MOCK/SIMULASI AGAR BISA COCOK
            // DI REALITA: POST template hasil scan ke API.
            const dummyTemplateToMatch = prompt('SIMULASI: Masukkan Base64 Template persis seperti yang disimpan sebelumnya (biarkan kosong untuk acak):', '');
            
            const response = await fetch('/attendance/verify', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                },
                body: JSON.stringify({
                    fingerprint_template: dummyTemplateToMatch || result.template + Math.random().toString(36)
                })
            });
            
            const data = await response.json();
            
            if (response.ok && data.success) {
                this.status = 'success';
                this.matchResult = data.student;
                
                // Otomatis tandai hadir
                this.markAttendance(data.student.id);
            } else {
                throw new Error(data.message || 'Sidik jari tidak dikenali.');
            }
            
        } catch (error) {
            this.status = 'failed';
            this.errorMessage = error.message;
        }
    },
    
    markAttendance(studentId) {
        // Submit hidden form otomatis
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('attendance.store') }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content');
        form.appendChild(csrfToken);
        
        const tanggalInput = document.createElement('input');
        tanggalInput.type = 'hidden';
        tanggalInput.name = 'tanggal';
        tanggalInput.value = document.querySelector('input[name=\"tanggal\"]').value || '{{ $today }}';
        form.appendChild(tanggalInput);
        
        const waktuShalatInput = document.createElement('input');
        waktuShalatInput.type = 'hidden';
        waktuShalatInput.name = 'waktu_shalat';
        waktuShalatInput.value = document.querySelector('select[name=\"waktu_shalat\"]').value || '{{ $waktu_shalat }}';
        form.appendChild(waktuShalatInput);
        
        const statusInput = document.createElement('input');
        statusInput.type = 'hidden';
        statusInput.name = 'attendances[' + studentId + '][status]';
        statusInput.value = 'hadir';
        form.appendChild(statusInput);
        
        document.body.appendChild(form);
        setTimeout(() => form.submit(), 1500);
    }
}" @open-verify-modal.window="openModal()">

    <div class="modal-overlay" x-show="open" style="display:none;" @click.self="closeModal()">
        <div class="modal-box max-w-sm w-full mx-4">
            <div class="modal-header">
                <h3 class="font-bold text-slate-800">Scan Absensi Sidik Jari</h3>
                <button type="button" @click="closeModal()">&times;</button>
            </div>
            
            <div class="modal-body text-center py-6">
                <div class="flex justify-center mb-6">
                    <div class="w-24 h-24 rounded-full flex items-center justify-center border-4"
                        :class="{
                            'border-slate-200 text-slate-400': status === 'ready',
                            'border-indigo-500 text-indigo-500 animate-pulse': status === 'scanning',
                            'border-green-500 text-green-500': status === 'success',
                            'border-red-500 text-red-500': status === 'failed'
                        }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a10 10 0 0 0-10 10v2a10 10 0 0 0 10 10 10 10 0 0 0 10-10v-2a10 10 0 0 0-10-10z" />
                            <path d="M12 6a6 6 0 0 0-6 6v2a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-2a6 6 0 0 0-6-6z" />
                            <path d="M12 10a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2 2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2z" />
                        </svg>
                    </div>
                </div>
                
                <div class="mb-6 h-12">
                    <p x-show="status === 'ready'" class="text-slate-600">Tekan tombol di bawah dan letakkan jari Anda pada scanner</p>
                    <p x-show="status === 'scanning'" class="text-indigo-600 font-medium">Sedang memverifikasi...</p>
                    
                    <div x-show="status === 'success'" class="text-green-600">
                        <p class="font-bold text-lg" x-text="matchResult ? matchResult.name : ''"></p>
                        <p class="text-sm">Berhasil diverifikasi! Menyimpan absen...</p>
                    </div>
                    
                    <p x-show="status === 'failed'" class="text-red-600 font-medium" x-text="errorMessage"></p>
                </div>
                
                <button type="button" class="btn w-full justify-center" 
                    :class="status === 'scanning' ? 'btn-secondary opacity-50 cursor-not-allowed' : 'btn-primary'" 
                    @click="scanAndVerify()"
                    :disabled="status === 'scanning'">
                    <span x-text="status === 'success' || status === 'failed' ? 'Coba Lagi' : 'Mulai Scan'"></span>
                </button>
            </div>
        </div>
    </div>

</div>{{-- end tcard --}}

</div>
</div>
@endsection

@push('scripts')
<style>
    @keyframes ping {

        75%,
        100% {
            transform: scale(2);
            opacity: 0;
        }
    }
</style>
<script>
    function showToast(type, title, msg, duration = 4000) {
        const wrap = document.getElementById('toastWrap');
        const icons = {
            success: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`
        };
        const t = document.createElement('div');
        t.className = `toast ${type}`;
        t.innerHTML = `<div class="toast-ico">${icons[type]||icons.success}</div><div><div class="toast-title">${title}</div><div class="toast-msg">${msg}</div></div>`;
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
        if (w.dataset.success) showToast('success', 'Berhasil!', w.dataset.success);
        if (w.dataset.error) showToast('error', 'Gagal!', w.dataset.error);
    })();
</script>
@endpush