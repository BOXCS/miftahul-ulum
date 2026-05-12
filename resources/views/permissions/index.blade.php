@extends('layouts.app')

@section('title', 'Manajemen Perizinan')
@section('breadcrumb', 'Perizinan')
@push('styles')
<style>
    :root {
        --sh-purple: 0 8px 28px rgba(13, 148, 136, .22);
        --sh-purple-sm: 0 4px 14px rgba(13, 148, 136, .18);
    }

    .btn-purple {
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
        box-shadow: var(--sh-purple-sm);
        transition: all .25s ease;
        font-family: inherit;
    }

    .btn-purple:hover {
        background: linear-gradient(135deg, #0f766e, #115e59);
        transform: translateY(-2px);
        box-shadow: var(--sh-purple);
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

    /* stat cards */
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

    .st-card.tc-purple::before {
        background: linear-gradient(90deg, #0d9488, #2dd4bf);
    }

    .st-card.tc-amber::before {
        background: linear-gradient(90deg, #d97706, #fbbf24);
    }

    .st-card.tc-green::before {
        background: linear-gradient(90deg, #16a34a, #4ade80);
    }

    .st-card.tc-red::before {
        background: linear-gradient(90deg, #dc2626, #f87171);
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

    /* table card */
    .tcard {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .06);
        overflow: hidden;
    }

    .filter-strip {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .tab-strip {
        display: flex;
        gap: 4px;
        padding: 14px 20px;
        border-bottom: 1px solid #f1f5f9;
        flex-wrap: wrap;
    }

    .tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 14px;
        border-radius: 10px;
        font-size: .78rem;
        font-weight: 700;
        border: none;
        cursor: pointer;
        background: transparent;
        color: #64748b;
        transition: all .2s;
        font-family: inherit;
    }

    .tab-btn:hover {
        background: #f1f5f9;
        color: #334155;
    }

    .tab-btn.active {
        background: #ccfbf1;
        color: #0d9488;
    }

    .tab-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 20px;
        height: 18px;
        padding: 0 6px;
        border-radius: 999px;
        font-size: .65rem;
        font-weight: 800;
    }

    .tab-btn.active .tab-count {
        background: #0d9488;
        color: #fff;
    }

    .tab-btn:not(.active) .tab-count {
        background: #e2e8f0;
        color: #64748b;
    }

    .tab-btn.amber:not(.active) .tab-count {
        background: #fef3c7;
        color: #b45309;
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

    .dt {
        width: 100%;
        border-collapse: collapse;
    }

    .dt thead th {
        padding: 11px 16px;
        font-size: .68rem;
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
        font-size: .85rem;
    }

    .dt tbody tr:hover td {
        background: #faf5ff;
    }

    .dt tbody tr:last-child td {
        border-bottom: none;
    }

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
        color: #fff;
    }

    .bdg {
        display: inline-flex;
        align-items: center;
        font-size: .68rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 999px;
        letter-spacing: .03em;
    }

    .bdg-purple {
        background: #ede9fe;
        color: #6d28d9;
    }

    .bdg-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .bdg-teal {
        background: #ccfbf1;
        color: #0f766e;
    }

    .bdg-amber {
        background: #fef3c7;
        color: #b45309;
    }

    .bdg-green {
        background: #dcfce7;
        color: #15803d;
    }

    .bdg-red {
        background: #fee2e2;
        color: #dc2626;
    }

    .bdg-slate {
        background: #f1f5f9;
        color: #475569;
    }

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
        background: #f5f3ff;
        color: #7c3aed;
    }

    .ico-btn.edit:hover {
        background: #ede9fe;
    }

    .ico-btn.del {
        background: #fff1f2;
        color: #e11d48;
    }

    .ico-btn.del:hover {
        background: #fee2e2;
    }

    .btn-approve {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: .75rem;
        font-weight: 700;
        border: 1px solid #bbf7d0;
        background: #f0fdf4;
        color: #16a34a;
        cursor: pointer;
        transition: all .18s;
        font-family: inherit;
    }

    .btn-approve:hover {
        background: #dcfce7;
        border-color: #86efac;
    }

    .btn-reject {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: .75rem;
        font-weight: 700;
        border: 1px solid #fecdd3;
        background: #fff1f2;
        color: #e11d48;
        cursor: pointer;
        transition: all .18s;
        font-family: inherit;
    }

    .btn-reject:hover {
        background: #fee2e2;
        border-color: #fca5a5;
    }

    .btn-detail {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: .75rem;
        font-weight: 700;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
        color: #475569;
        cursor: pointer;
        transition: all .18s;
        font-family: inherit;
    }

    .btn-detail:hover {
        background: #f1f5f9;
    }

    /* alert banner */
    .alert-pending {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 14px 18px;
        background: #fffbeb;
        border: 1px solid #fde68a;
        border-radius: 14px;
        margin-bottom: 20px;
    }

    /* modals */
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

    .smodal-hdr {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px 14px;
        border-bottom: 1px solid #f1f5f9;
        flex-shrink: 0;
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
        transition: all .18s;
    }

    .smodal-close:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 14px;
    }

    .detail-cell {
        padding: 12px 14px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
    }

    .detail-cell-lbl {
        font-size: .65rem;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 5px;
    }

    /* export dropdown */
    .export-dropdown {
        position: relative;
    }

    .export-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .12);
        min-width: 180px;
        z-index: 100;
        overflow: hidden;
        display: none;
    }

    .export-menu.open {
        display: block;
    }

    .export-menu a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 16px;
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s;
    }

    .export-menu a:hover {
        background: #f8fafc;
    }

    .export-menu-divider {
        height: 1px;
        background: #f1f5f9;
        margin: 0 12px;
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

    .result-bar strong {
        color: #0f172a;
        font-weight: 700;
    }

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

    .toast.success {
        border-color: #7c3aed;
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
        background: #ede9fe;
        color: #7c3aed;
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
<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:12px;margin-bottom:24px;">
    <div>
        <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Manajemen Perizinan</h1>
        <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Kelola permohonan izin santri Pondok Pesantren Miftahul Ulum</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        {{-- Export Dropdown --}}
        <div class="export-dropdown" id="exportDropdown">
            <button class="btn-secondary-outline" onclick="toggleExport()">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                    <polyline points="7 10 12 15 17 10" />
                    <line x1="12" y1="15" x2="12" y2="3" />
                </svg>
                Export
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </button>
            <div class="export-menu" id="exportMenu">
                <a href="{{ route('permissions.index') }}?export=pdf" style="color:#dc2626;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                    Export PDF
                </a>
                <div class="export-menu-divider"></div>
                <a href="{{ route('permissions.index') }}?export=excel" style="color:#16a34a;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" />
                        <path d="M3 9h18M3 15h18M9 3v18" />
                    </svg>
                    Export Excel
                </a>
            </div>
        </div>
        <a href="{{ route('permissions.create') }}" class="btn-purple">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Tambah Izin
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">
    <div class="st-card tc-purple">
        <div class="st-ico" style="background:#ccfbf1;color:#0d9488;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <polyline points="14 2 14 8 20 8" />
                <line x1="16" y1="13" x2="8" y2="13" />
                <line x1="16" y1="17" x2="8" y2="17" />
            </svg>
        </div>
        <div>
            <div class="st-val" id="statTotal">{{ count($permissions) }}</div>
            <div class="st-lbl">Total Izin</div>
        </div>
    </div>
    <div class="st-card tc-amber">
        <div class="st-ico" style="background:#fef3c7;color:#b45309;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="12" y1="8" x2="12" y2="12" />
                <line x1="12" y1="16" x2="12.01" y2="16" />
            </svg>
        </div>
        <div>
            <div class="st-val" id="statPending">{{ collect($permissions)->where('status','Pending')->count() }}</div>
            <div class="st-lbl">Menunggu</div>
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
            <div class="st-val">{{ collect($permissions)->where('status','Disetujui')->count() }}</div>
            <div class="st-lbl">Disetujui</div>
        </div>
    </div>
    <div class="st-card tc-red">
        <div class="st-ico" style="background:#fee2e2;color:#dc2626;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="15" y1="9" x2="9" y2="15" />
                <line x1="9" y1="9" x2="15" y2="15" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ collect($permissions)->where('status','Ditolak')->count() }}</div>
            <div class="st-lbl">Ditolak</div>
        </div>
    </div>
</div>

{{-- MAIN TABLE CARD --}}
<div class="tcard" x-data="{
    activeTab: 'semua',
    search: '',
    detailModal: false,
    approveModal: false,
    rejectModal: false,
    selectedItem: null,
    rejectReason: '',
    permissions: @js($permissions),
    get filtered() {
        return this.permissions.filter(p => {
            const q = this.search.toLowerCase();
            const matchS = !q || p.santri.toLowerCase().includes(q) || p.jenis.toLowerCase().includes(q);
            const matchT = this.activeTab === 'semua'
                || (this.activeTab === 'pending'   && p.status === 'Pending')
                || (this.activeTab === 'disetujui' && p.status === 'Disetujui')
                || (this.activeTab === 'ditolak'   && p.status === 'Ditolak');
            return matchS && matchT;
        });
    },
    get pendingCount()   { return this.permissions.filter(p => p.status === 'Pending').length; },
    get approvedCount()  { return this.permissions.filter(p => p.status === 'Disetujui').length; },
    get rejectedCount()  { return this.permissions.filter(p => p.status === 'Ditolak').length; },
openDetail(item) {
    openDetailModal(item);
},
openApprove(item) {
    openApproveModal(item);
},
openReject(item) {
    openRejectModal(item);
},
    confirmApprove() {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '/permissions/' + this.selectedItem.id + '/approve';
        const t = document.createElement('input'); t.type='hidden'; t.name='_token'; t.value=document.querySelector('meta[name=csrf-token]').content; f.appendChild(t);
        const a = document.createElement('input'); a.type='hidden'; a.name='approved_by'; a.value='Admin'; f.appendChild(a);
        document.body.appendChild(f); f.submit();
    },
    confirmReject() {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '/permissions/' + this.selectedItem.id + '/reject';
        const t = document.createElement('input'); t.type='hidden'; t.name='_token'; t.value=document.querySelector('meta[name=csrf-token]').content; f.appendChild(t);
        const a = document.createElement('input'); a.type='hidden'; a.name='approved_by'; a.value='Admin'; f.appendChild(a);
        const r = document.createElement('input'); r.type='hidden'; r.name='reason'; r.value=this.rejectReason; f.appendChild(r);
        document.body.appendChild(f); f.submit();
    },
    jenisColor(j) {
        const m = { Pulang:'bdg-blue', Sakit:'bdg-red', Kegiatan:'bdg-teal', Keluar:'bdg-amber' };
        return m[j] || 'bdg-slate';
    },
    statusColor(s) {
        if (s==='Pending')   return 'bdg-amber';
        if (s==='Disetujui') return 'bdg-green';
        if (s==='Ditolak')   return 'bdg-red';
        return 'bdg-slate';
    },
    avatarColor(s) {
        if (s==='Pending')   return 'background:linear-gradient(135deg,#d97706,#fbbf24)';
        if (s==='Disetujui') return 'background:linear-gradient(135deg,#16a34a,#4ade80)';
        if (s==='Ditolak')   return 'background:linear-gradient(135deg,#dc2626,#f87171)';
        return 'background:linear-gradient(135deg,#7c3aed,#a78bfa)';
    },

    // ─── Realtime listener via Laravel Reverb ───────────────────
    init() {
        if (!window.Echo) {
            console.warn('Echo tidak tersedia, real-time perizinan dimatikan');
            return;
        }
        try {
            window.Echo.private('permissions-admin')
                .listen('.PermissionSubmitted', (e) => {
                    // Hindari duplikat (kalau by chance dikirim 2x)
                    if (this.permissions.some(p => p.id === e.id)) return;

                    // Prepend ke list — izin paling baru di atas
                    this.permissions.unshift(e);

                    // Tampilkan toast notifikasi (kalau komponen toast tersedia)
                    this.notifyNewPermission(e);
                });
            console.log('[Reverb] Listening on permissions-admin');
        } catch (err) {
            console.warn('[Reverb] Gagal subscribe permissions-admin:', err);
        }
    },

    notifyNewPermission(p) {
        // Notifikasi browser native (kalau diizinkan)
        if ('Notification' in window && Notification.permission === 'granted') {
            new Notification('Permohonan Izin Baru', {
                body: `${p.santri} mengajukan izin ${p.jenis}`,
                icon: '/favicon.ico',
            });
        }
        // Audio beep singkat
        try {
            const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.frequency.value = 880;
            gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.25);
            osc.start(); osc.stop(audioCtx.currentTime + 0.25);
        } catch(_) {}
    },
}"
x-init="
    if ('Notification' in window && Notification.permission === 'default') {
        Notification.requestPermission();
    }
">

    {{-- Alert pending --}}
    <div class="alert-pending" x-show="pendingCount > 0" style="margin:16px 20px 0;">
        <div style="width:32px;height:32px;border-radius:10px;background:#fde68a;color:#92400e;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                <line x1="12" y1="9" x2="12" y2="13" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
        </div>
        <div style="flex:1;">
            <p style="font-size:.82rem;font-weight:700;color:#92400e;">
                Ada <span x-text="pendingCount"></span> permohonan izin menunggu persetujuan
            </p>
            <p style="font-size:.72rem;color:#b45309;margin-top:2px;">Segera ditindaklanjuti agar santri mendapat kepastian tepat waktu.</p>
        </div>
        <button @click="activeTab='pending'"
            style="padding:6px 14px;border-radius:9px;background:#d97706;color:#fff;font-size:.75rem;font-weight:700;border:none;cursor:pointer;white-space:nowrap;font-family:inherit;">
            Tinjau
        </button>
    </div>

    {{-- Tab strip --}}
    <div class="tab-strip">
        <button class="tab-btn" :class="activeTab==='semua' ? 'active' : ''" @click="activeTab='semua'">
            Semua <span class="tab-count" x-text="permissions.length"></span>
        </button>
        <button class="tab-btn amber" :class="activeTab==='pending' ? 'active' : ''" @click="activeTab='pending'">
            Pending <span class="tab-count" x-text="pendingCount"></span>
        </button>
        <button class="tab-btn" :class="activeTab==='disetujui' ? 'active' : ''" @click="activeTab='disetujui'">
            Disetujui <span class="tab-count" x-text="approvedCount"></span>
        </button>
        <button class="tab-btn" :class="activeTab==='ditolak' ? 'active' : ''" @click="activeTab='ditolak'">
            Ditolak <span class="tab-count" x-text="rejectedCount"></span>
        </button>

        {{-- Search --}}
        <div class="filter-search" style="margin-left:auto;max-width:240px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="search" placeholder="Cari santri, jenis…" x-model="search" autocomplete="off">
        </div>
    </div>

    <div class="result-bar">
        <span>Menampilkan <strong x-text="filtered.length"></strong> dari <strong x-text="permissions.length"></strong> permohonan</span>
    </div>

    <div style="overflow-x:auto;">
        <table class="dt">
            <thead>
                <tr>
                    <th style="width:48px;text-align:center;">No</th>
                    <th>Santri</th>
                    <th style="text-align:center;">Kelas</th>
                    <th style="text-align:center;">Jenis</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="empty-row" x-show="filtered.length === 0">
                    <td colspan="8">
                        <div class="empty-ico-wrap">📋</div>
                        <p style="font-size:.82rem;font-weight:600;">Tidak ada permohonan ditemukan</p>
                    </td>
                </tr>
                <template x-for="(item, idx) in filtered" :key="item.id">
                    <tr>
                        <td style="text-align:center;">
                            <span style="font-size:.75rem;color:#94a3b8;font-weight:600;" x-text="idx+1"></span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="ava" :style="avatarColor(item.status)">
                                    <span style="font-size:.65rem;" x-text="item.avatar"></span>
                                </div>
                                <div>
                                    <span style="font-size:.85rem;font-weight:700;color:#1e293b;display:block;" x-text="item.santri"></span>
                                    <span style="font-size:.72rem;color:#94a3b8;" x-text="'Kelas ' + item.kelas"></span>
                                </div>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:26px;border-radius:8px;background:#f1f5f9;color:#475569;font-size:.75rem;font-weight:800;" x-text="item.kelas"></span>
                        </td>
                        <td style="text-align:center;">
                            <span class="bdg" :class="jenisColor(item.jenis)" x-text="item.jenis"></span>
                        </td>
                        <td>
                            <span style="font-size:.8rem;color:#475569;white-space:nowrap;" x-text="item.tanggal"></span>
                        </td>
                        <td>
                            <span style="font-size:.8rem;color:#64748b;display:block;max-width:180px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" :title="item.keterangan" x-text="item.keterangan || '—'"></span>
                        </td>
                        <td style="text-align:center;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:5px;">
                                <template x-if="item.status === 'Pending'">
                                    <span style="position:relative;display:inline-flex;">
                                        <span style="position:absolute;display:inline-flex;width:100%;height:100%;border-radius:50%;background:#fbbf24;opacity:.75;animation:ping 1s cubic-bezier(0,0,.2,1) infinite;"></span>
                                        <span style="width:8px;height:8px;border-radius:50%;background:#f59e0b;display:inline-flex;"></span>
                                    </span>
                                </template>
                                <span class="bdg" :class="statusColor(item.status)" x-text="item.status"></span>
                            </div>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <template x-if="item.status === 'Pending'">
                                    <div style="display:flex;gap:5px;">
                                        <button class="btn-approve" @click="openApprove(item)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12" />
                                            </svg>
                                            Setujui
                                        </button>
                                        <button class="btn-reject" @click="openReject(item)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18" />
                                                <line x1="6" y1="6" x2="18" y2="18" />
                                            </svg>
                                            Tolak
                                        </button>
                                    </div>
                                </template>
                                <template x-if="item.status !== 'Pending'">
                                    <div style="display:flex;gap:5px;">
                                        <button class="btn-detail" @click="openDetail(item)">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="11" cy="11" r="8" />
                                                <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                            </svg>
                                            Detail
                                        </button>
                                        <a :href="`/permissions/${item.id}/edit`" class="ico-btn edit" title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </a>
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    {{-- Table footer --}}
    <div style="padding:12px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:.75rem;color:#64748b;">
            Menampilkan <strong x-text="filtered.length" style="color:#0f172a;"></strong>
            dari <strong x-text="permissions.length" style="color:#0f172a;"></strong> permohonan
        </span>
    </div>

    {{-- MODAL DETAIL --}}
    <div class="smodal-overlay" id="detailModal">
        <div class="smodal-box" style="max-width:520px;">
            <div class="smodal-hdr">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;border-radius:11px;background:#ede9fe;color:#7c3aed;display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8" />
                            <line x1="21" y1="21" x2="16.65" y2="16.65" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:1rem;font-weight:800;color:#0f172a;">Detail Permohonan Izin</div>
                        <div style="font-size:.72rem;color:#94a3b8;" id="detailId"></div>
                    </div>
                </div>
                <button class="smodal-close" onclick="closeModal('detailModal')">✕</button>
            </div>
            <div class="smodal-body">
                <div style="display:flex;align-items:center;gap:14px;padding:14px;background:#f8fafc;border-radius:14px;border:1px solid #e2e8f0;margin-bottom:16px;">
                    <div class="ava" style="width:46px;height:46px;border-radius:13px;" id="detailAva"></div>
                    <div>
                        <div style="font-size:.95rem;font-weight:800;color:#0f172a;" id="detailName"></div>
                        <div style="font-size:.75rem;color:#64748b;margin-top:2px;" id="detailSub"></div>
                    </div>
                    <div style="margin-left:auto;" id="detailStatusBdg"></div>
                </div>
                <div class="detail-grid">
                    <div class="detail-cell">
                        <div class="detail-cell-lbl">Jenis Izin</div>
                        <div id="detailJenis"></div>
                    </div>
                    <div class="detail-cell">
                        <div class="detail-cell-lbl">Tanggal</div>
                        <div style="font-size:.83rem;font-weight:600;color:#334155;" id="detailTanggal"></div>
                    </div>
                    <div class="detail-cell">
                        <div class="detail-cell-lbl">Diajukan</div>
                        <div id="detailDiajukan"></div>
                    </div>
                    <div class="detail-cell">
                        <div class="detail-cell-lbl">Tgl Ajuan</div>
                        <div style="font-size:.83rem;font-weight:600;color:#334155;" id="detailTglAjuan"></div>
                    </div>
                </div>
                <div style="padding:12px 14px;background:#f8fafc;border-radius:12px;border:1px solid #f1f5f9;">
                    <div class="detail-cell-lbl" style="margin-bottom:6px;">Keterangan</div>
                    <div style="font-size:.83rem;color:#475569;line-height:1.6;" id="detailKeterangan"></div>
                </div>
                <div id="detailCatatan" style="display:none;margin-top:12px;padding:12px 14px;border-radius:12px;border:1px solid;">
                    <div style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;" id="detailCatatanLbl"></div>
                    <div style="font-size:.83rem;line-height:1.6;" id="detailCatatanText"></div>
                </div>
            </div>
            <div class="smodal-ftr">
                <button onclick="closeModal('detailModal')"
                    style="padding:8px 18px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.8rem;font-weight:700;color:#475569;cursor:pointer;font-family:inherit;">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- MODAL SETUJUI --}}
    <div class="smodal-overlay" id="approveModal">
        <div class="smodal-box" style="max-width:420px;">
            <div class="smodal-body" style="text-align:center;padding:32px 24px 20px;">
                <div style="width:60px;height:60px;border-radius:18px;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg>
                </div>
                <div style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">Setujui Permohonan?</div>
                <p style="font-size:.82rem;color:#64748b;margin-bottom:14px;">Anda akan menyetujui permohonan izin:</p>
                <div style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#f0fdf4;border-radius:12px;border:1px solid #bbf7d0;text-align:left;margin-bottom:16px;">
                    <div class="ava" style="width:40px;height:40px;border-radius:11px;" id="approveName2"></div>
                    <div>
                        <div style="font-size:.85rem;font-weight:700;color:#0f172a;" id="approveName"></div>
                        <div style="font-size:.75rem;color:#64748b;" id="approveDetail"></div>
                    </div>
                </div>
            </div>
            <div class="smodal-ftr">
                <button onclick="closeModal('approveModal')"
                    style="padding:8px 18px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.8rem;font-weight:700;color:#475569;cursor:pointer;font-family:inherit;">
                    Batal
                </button>
                <button type="button" onclick="document.getElementById('approveForm').submit()"
                    style="padding:8px 20px;border-radius:10px;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;font-size:.8rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                    Ya, Setujui
                </button>
            </div>
        </div>
    </div>
    <form id="approveForm" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="approved_by" value="{{ auth()->user()->name ?? 'Admin' }}">
    </form>

    {{-- MODAL TOLAK --}}
    <div class="smodal-overlay" id="rejectModal">
        <div class="smodal-box" style="max-width:460px;">
            <div class="smodal-hdr">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;border-radius:11px;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="15" y1="9" x2="9" y2="15" />
                            <line x1="9" y1="9" x2="15" y2="15" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:1rem;font-weight:800;color:#dc2626;">Tolak Permohonan</div>
                        <div style="font-size:.72rem;color:#94a3b8;" id="rejectSubtitle"></div>
                    </div>
                </div>
                <button class="smodal-close" onclick="closeModal('rejectModal')">✕</button>
            </div>
            <div class="smodal-body">
                <div style="padding:12px 14px;background:#fff7f7;border:1px solid #fecaca;border-radius:12px;margin-bottom:14px;font-size:.8rem;color:#7f1d1d;line-height:1.6;">
                    Penolakan akan diberitahukan ke santri dan wali. Isi alasan dengan jelas.
                </div>
                <label style="display:block;font-size:.72rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:7px;">
                    Alasan Penolakan <span style="color:#e11d48;">*</span>
                </label>
                <textarea id="rejectReasonInput" rows="3"
                    style="width:100%;padding:10px 14px;border-radius:11px;border:1.5px solid #e2e8f0;font-size:.83rem;font-family:inherit;color:#1e293b;background:#f8fafc;outline:none;resize:vertical;box-sizing:border-box;"
                    placeholder="Tuliskan alasan penolakan..."></textarea>
                <p style="font-size:.7rem;color:#94a3b8;margin-top:5px;">Minimal 10 karakter</p>
                <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:10px;">
                    <button onclick="document.getElementById('rejectReasonInput').value='Tidak sesuai jadwal kepulangan pondok'"
                        style="padding:5px 12px;border-radius:999px;font-size:.72rem;background:#f1f5f9;border:1px solid #e2e8f0;color:#475569;cursor:pointer;font-family:inherit;">
                        Tidak sesuai jadwal
                    </button>
                    <button onclick="document.getElementById('rejectReasonInput').value='Dokumen pendukung tidak lengkap'"
                        style="padding:5px 12px;border-radius:999px;font-size:.72rem;background:#f1f5f9;border:1px solid #e2e8f0;color:#475569;cursor:pointer;font-family:inherit;">
                        Dokumen tidak lengkap
                    </button>
                    <button onclick="document.getElementById('rejectReasonInput').value='Santri memiliki kegiatan pondok yang tidak dapat ditinggalkan'"
                        style="padding:5px 12px;border-radius:999px;font-size:.72rem;background:#f1f5f9;border:1px solid #e2e8f0;color:#475569;cursor:pointer;font-family:inherit;">
                        Ada kegiatan pondok
                    </button>
                </div>
            </div>
            <div class="smodal-ftr">
                <button onclick="closeModal('rejectModal')"
                    style="padding:8px 18px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.8rem;font-weight:700;color:#475569;cursor:pointer;font-family:inherit;">
                    Batal
                </button>
                <button type="button" onclick="submitReject()"
                    style="padding:8px 20px;border-radius:10px;background:#e11d48;color:#fff;font-size:.8rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg>
                    Tolak Izin
                </button>
            </div>
        </div>
    </div>
    <form id="rejectForm" method="POST" style="display:none;">
        @csrf
        <input type="hidden" name="approved_by" value="{{ auth()->user()->name ?? 'Admin' }}">
        <input type="hidden" name="catatan" id="rejectReasonHidden">
    </form>

</div>{{-- end tcard / x-data --}}

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
    // Toast
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

    // Export dropdown
    function toggleExport() {
        document.getElementById('exportMenu').classList.toggle('open');
    }
    document.addEventListener('click', e => {
        if (!document.getElementById('exportDropdown').contains(e.target))
            document.getElementById('exportMenu').classList.remove('open');
    });

    // Modal helpers
    function openModal(id) {
        document.getElementById(id).classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') document.querySelectorAll('.smodal-overlay.open').forEach(m => m.classList.remove('open'));
    });
    document.querySelectorAll('.smodal-overlay').forEach(m => m.addEventListener('click', function(e) {
        if (e.target === this) closeModal(this.id);
    }));

    // Detail modal
    function openDetailModal(item) {
        const statusColors = {
            Pending: 'background:#fef3c7;color:#b45309',
            Disetujui: 'background:#dcfce7;color:#15803d',
            Ditolak: 'background:#fee2e2;color:#dc2626'
        };
        const jenisColors = {
            Pulang: 'background:#dbeafe;color:#1d4ed8',
            Sakit: 'background:#fee2e2;color:#dc2626',
            Kegiatan: 'background:#ccfbf1;color:#0f766e',
            Keluar: 'background:#fef3c7;color:#b45309'
        };
        const avatarColors = {
            Pending: 'background:linear-gradient(135deg,#d97706,#fbbf24)',
            Disetujui: 'background:linear-gradient(135deg,#16a34a,#4ade80)',
            Ditolak: 'background:linear-gradient(135deg,#dc2626,#f87171)'
        };

        document.getElementById('detailId').textContent = 'ID #' + String(item.id).padStart(4, '0');
        document.getElementById('detailAva').style.cssText = (avatarColors[item.status] || 'background:linear-gradient(135deg,#7c3aed,#a78bfa)') + ';display:flex;align-items:center;justify-content:center;color:#fff;font-size:.7rem;font-weight:800;';
        document.getElementById('detailAva').textContent = item.avatar;
        document.getElementById('detailName').textContent = item.santri;
        document.getElementById('detailSub').textContent = 'Kelas ' + item.kelas;
        document.getElementById('detailStatusBdg').innerHTML = `<span style="font-size:.72rem;font-weight:700;padding:4px 12px;border-radius:999px;${statusColors[item.status]||''}">${item.status}</span>`;
        document.getElementById('detailJenis').innerHTML = `<span style="font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:999px;${jenisColors[item.jenis]||'background:#f1f5f9;color:#475569'}">${item.jenis}</span>`;
        document.getElementById('detailTanggal').textContent = item.tanggal;
        document.getElementById('detailDiajukan').innerHTML = `<span style="font-size:.75rem;font-weight:700;padding:3px 10px;border-radius:999px;background:#dbeafe;color:#1d4ed8">${item.diajukan}</span>`;
        document.getElementById('detailTglAjuan').textContent = item.tglAjuan;
        document.getElementById('detailKeterangan').textContent = item.keterangan || '—';

        const catatanEl = document.getElementById('detailCatatan');
        if (item.catatan) {
            catatanEl.style.display = 'block';
            catatanEl.style.borderColor = item.status === 'Disetujui' ? '#bbf7d0' : '#fecaca';
            catatanEl.style.background = item.status === 'Disetujui' ? '#f0fdf4' : '#fff7f7';
            document.getElementById('detailCatatanLbl').style.color = item.status === 'Disetujui' ? '#15803d' : '#dc2626';
            document.getElementById('detailCatatanLbl').textContent = 'Catatan Admin';
            document.getElementById('detailCatatanText').style.color = item.status === 'Disetujui' ? '#166534' : '#7f1d1d';
            document.getElementById('detailCatatanText').textContent = item.catatan;
        } else {
            catatanEl.style.display = 'none';
        }
        openModal('detailModal');
    }

    // Approve modal
    let approveTargetId = null;

    function openApproveModal(item) {
        approveTargetId = item.id;
        const avatarColors = {
            Pending: 'background:linear-gradient(135deg,#d97706,#fbbf24)',
            Disetujui: 'background:linear-gradient(135deg,#16a34a,#4ade80)',
            Ditolak: 'background:linear-gradient(135deg,#dc2626,#f87171)'
        };
        document.getElementById('approveName').textContent = item.santri;
        document.getElementById('approveDetail').textContent = item.jenis + ' · ' + item.tanggal;
        const ava2 = document.getElementById('approveName2');
        ava2.style.cssText = (avatarColors[item.status] || 'background:#7c3aed') + ';display:flex;align-items:center;justify-content:center;color:#fff;font-size:.65rem;font-weight:800;';
        ava2.textContent = item.avatar;
        document.getElementById('approveForm').action = '/permissions/' + item.id + '/approve';
        openModal('approveModal');
    }

    // Reject modal
    let rejectTargetId = null;

    function openRejectModal(item) {
        rejectTargetId = item.id;
        document.getElementById('rejectSubtitle').textContent = item.santri + ' — ' + item.jenis;
        document.getElementById('rejectReasonInput').value = '';
        document.getElementById('rejectForm').action = '/permissions/' + item.id + '/reject';
        openModal('rejectModal');
    }

    function submitReject() {
        const reason = document.getElementById('rejectReasonInput').value;
        if (reason.length < 10) {
            alert('Alasan minimal 10 karakter');
            return;
        }
        document.getElementById('rejectReasonHidden').value = reason;
        document.getElementById('rejectForm').submit();
    }
</script>
@endpush