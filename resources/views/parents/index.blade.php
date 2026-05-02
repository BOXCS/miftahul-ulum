@extends('layouts.app')

@section('title', 'Data Wali Santri')
@section('breadcrumb', 'Wali Santri')
@push('styles')
<style>
    :root {
        --teal-600: #0d9488;
        --teal-700: #0f766e;
        --teal-800: #115e59;
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
        outline: none;
        cursor: pointer;
        transition: border .2s;
        background: #f8fafc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2394a3b8' stroke-width='2.5'%3E%3Cpolyline points='6 9 12 15 18 9'/%3E%3C/svg%3E") no-repeat right 10px center;
    }

    .filter-select:focus {
        border-color: #0d9488;
    }

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

    /* Tambahkan setelah .dt tbody td { ... } */
    .dt tbody td span {
        font-size: .85rem !important;
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
    }

    .bdg {
        display: inline-flex;
        align-items: center;
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

    .bdg-amber {
        background: #fef3c7;
        color: #b45309;
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
        <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Data Wali Santri</h1>
        <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Kelola data orang tua & wali santri Pondok Pesantren Miftahul Ulum</p>
    </div>
    <a href="{{ route('parents.create') }}" class="btn-teal">
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>
        Tambah Wali
    </a>
</div>

{{-- STAT CARDS — sesuai $stats dari controller: total & terhubung --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:22px;">
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
            <div class="st-lbl">Total Wali</div>
        </div>
    </div>
    <div class="st-card tc-green">
        <div class="st-ico" style="background:#dcfce7;color:#15803d;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ $stats['terhubung'] }}</div>
            <div class="st-lbl">Terhubung ke Santri</div>
        </div>
    </div>
</div>

{{-- TABLE CARD
     $parents = Eloquent collection dengan field: name, relationship, phone, email, address
     relation: students (hasMany) --}}
<div class="tcard" x-data="{
    search: '',
    hubunganFilter: '',
    parents: @js($parents->map(fn($p) => [
        'id'           => $p->id,
        'name'         => $p->name,
        'relationship' => $p->relationship,
        'phone'        => $p->phone,
        'email'        => $p->email,
        'address'      => $p->address,
        'students'     => $p->students->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values(),
    ])->values()),
    get filtered() {
        return this.parents.filter(p => {
            const q = this.search.toLowerCase();
            const matchS = !q
                || p.name.toLowerCase().includes(q)
                || (p.phone||'').includes(q)
                || (p.students||[]).some(s => s.name.toLowerCase().includes(q));
            const matchH = !this.hubunganFilter || p.relationship === this.hubunganFilter;
            return matchS && matchH;
        });
    },
    initials(n) { return n.split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase(); },
    hubBadge(h) {
        if (h === 'ayah') return 'bdg-blue';
        if (h === 'ibu')  return 'bdg-amber';
        return 'bdg-teal';
    }
}">

    <div class="filter-strip">
        <div class="filter-search" style="flex:1;min-width:180px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="search" placeholder="Cari nama, HP, santri…" x-model="search" autocomplete="off">
        </div>
        <select class="filter-select" x-model="hubunganFilter" style="min-width:140px;">
            <option value="">Semua Hubungan</option>
            <option value="ayah">Ayah</option>
            <option value="ibu">Ibu</option>
            <option value="wali">Wali</option>
        </select>
    </div>

    <div class="result-bar">
        <span>Menampilkan <strong x-text="filtered.length"></strong> dari <strong x-text="parents.length"></strong> wali</span>
    </div>

    <div style="overflow-x:auto;">
        <table class="dt">
            <thead>
                <tr>
                    <th style="width:48px;text-align:center;">No</th>
                    <th>Nama Wali</th>
                    <th style="text-align:center;">Hubungan</th>
                    <th>No. HP</th>
                    <th>Email</th>
                    <th>Santri</th>
                    <th style="text-align:center;width:90px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="empty-row" x-show="filtered.length === 0">
                    <td colspan="7">
                        <div class="empty-ico-wrap">👨‍👩‍👧</div>
                        <p style="font-size:.82rem;font-weight:600;">Tidak ada data wali ditemukan</p>
                    </td>
                </tr>
                <template x-for="(p, idx) in filtered" :key="p.id">
                    <tr>
                        <td style="text-align:center;">
                            <span style="font-size:.75rem;color:#94a3b8;font-weight:600;" x-text="idx+1"></span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:11px;">
                                <div class="ava" style="background:#ccfbf1;color:#0f766e;">
                                    <span x-text="initials(p.name)"></span>
                                </div>
                                <div>
                                    <span style="font-size:.82rem;font-weight:700;color:#1e293b;display:block;" x-text="p.name"></span>
                                    <span style="font-size:.72rem;color:#94a3b8;" x-text="p.address || '-'"></span>
                                </div>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <span class="bdg" :class="hubBadge(p.relationship)" x-text="p.relationship"></span>
                        </td>
                        <td><span style="font-size:.8rem;color:#475569;" x-text="p.phone || '-'"></span></td>
                        <td><span style="font-size:.78rem;color:#64748b;" x-text="p.email || '-'"></span></td>
                        <td>
                            <template x-if="p.students && p.students.length">
                                <div>
                                    <template x-for="s in p.students" :key="s.id">
                                        <span class="bdg bdg-slate" style="margin-bottom:4px;display:block;" x-text="s.name"></span>
                                    </template>
                                </div>
                            </template>
                            <template x-if="!p.students || !p.students.length">
                                <span style="font-size:.75rem;color:#cbd5e1;font-style:italic;">—</span>
                            </template>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;justify-content:center;gap:6px;">
                                <a :href="`/parents/${p.id}/edit`" class="ico-btn edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </a>
                                <button class="ico-btn del" title="Hapus" @click="openDeleteModal(p)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
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
</div>

{{-- MODAL HAPUS --}}
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
                    <div class="smodal-title" style="color:#dc2626;">Hapus Data Wali</div>
                    <div class="smodal-sub">Tindakan ini tidak dapat dibatalkan</div>
                </div>
            </div>
            <button class="smodal-close" onclick="closeDeleteModal()">✕</button>
        </div>
        <div class="smodal-body">
            <div class="del-warning-box">
                <p style="font-size:.82rem;color:#7f1d1d;line-height:1.6;">
                    Anda akan menghapus data wali <strong id="delParentName" style="font-weight:800;">—</strong>.<br>
                    Data yang dihapus <strong>tidak dapat dikembalikan</strong>.
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
            <button onclick="submitDelete()"
                style="padding:8px 18px;border-radius:10px;background:#e11d48;color:#fff;font-size:.8rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:7px;">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6" />
                    <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                </svg>
                Ya, Hapus
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showToast(type, title, msg, duration = 4000) {
        const wrap = document.getElementById('toastWrap');
        const t = document.createElement('div');
        const icons = {
            success: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,
            error: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>`
        };
        t.className = `toast ${type}`;
        t.innerHTML = `<div class="toast-ico">${icons[type]||icons.success}</div><div><div class="toast-title">${title}</div><div class="toast-msg">${msg}</div></div>`;
        wrap.appendChild(t);
        requestAnimationFrame(() => requestAnimationFrame(() => t.classList.add('show')));
        setTimeout(() => {
            t.classList.remove('show');
            setTimeout(() => t.remove(), 400);
        }, duration);
    }

    function initSessionToasts() {
        const wrap = document.getElementById('toastWrap');
        if (!wrap) return;
        if (wrap.dataset.success) showToast('success', 'Berhasil!', wrap.dataset.success);
        if (wrap.dataset.error) showToast('error', 'Gagal!', wrap.dataset.error);
    }

    document.readyState === 'loading' ?
        document.addEventListener('DOMContentLoaded', initSessionToasts) :
        initSessionToasts();

    let deleteTargetUrl = null;

    function openDeleteModal(parent) {
        deleteTargetUrl = `/parents/${parent.id}`;
        document.getElementById('delParentName').textContent = parent.name;
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

    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeDeleteModal();
    });
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeDeleteModal();
    });
</script>
@endpush