@extends('layouts.app')

@section('title', 'Manajemen FAQ')
@section('breadcrumb', 'FAQ')

@push('styles')
<style>
    :root {
        --sh-indigo: 0 8px 28px rgba(99, 102, 241, .22);
        --sh-indigo-sm: 0 4px 14px rgba(99, 102, 241, .18);
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
        box-shadow: 0 4px 14px rgba(13, 148, 136, .28);
        transition: background .2s, transform .2s, box-shadow .2s;
    }

    .btn-teal:hover {
        background: linear-gradient(135deg, #0f766e, #115e59);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(13, 148, 136, .35);
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

    .st-card.tc-indigo::before {
        background: linear-gradient(90deg, #6366f1, #a5b4fc);
    }

    .st-card.tc-green::before {
        background: linear-gradient(90deg, #16a34a, #4ade80);
    }

    .st-card.tc-amber::before {
        background: linear-gradient(90deg, #d97706, #fbbf24);
    }

    .st-card.tc-slate::before {
        background: linear-gradient(90deg, #475569, #94a3b8);
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
        background: #eef2ff;
        color: #6366f1;
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
        background: #6366f1;
        color: #fff;
    }

    .tab-btn:not(.active) .tab-count {
        background: #e2e8f0;
        color: #64748b;
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
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .12);
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
        background: #f5f3ff;
    }

    .dt tbody tr:last-child td {
        border-bottom: none;
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

    .bdg-indigo {
        background: #eef2ff;
        color: #4f46e5;
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

    .bdg-blue {
        background: #dbeafe;
        color: #1d4ed8;
    }

    .bdg-purple {
        background: #ede9fe;
        color: #6d28d9;
    }

    .bdg-teal {
        background: #ccfbf1;
        color: #0f766e;
    }

    .bdg-slate {
        background: #f1f5f9;
        color: #475569;
    }

    .jawaban-preview {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: .78rem;
        color: #64748b;
        line-height: 1.5;
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
        border-color: #6366f1;
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
        background: #eef2ff;
        color: #6366f1;
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
    .faq-stats {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 22px;
    }

    @media(max-width: 900px) {
        .faq-stats { grid-template-columns: repeat(2, 1fr); }
    }

    @media(max-width: 480px) {
        .faq-stats { grid-template-columns: repeat(2, 1fr); gap: 10px; }
        .st-card { padding: 14px; }
    }

    .tab-strip {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }

    .tab-buttons {
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
    }

    .tab-search {
        position: relative;
        width: 240px;
        flex-shrink: 0;
    }

    .tab-search svg {
        position: absolute;
        left: 11px;
        top: 50%;
        transform: translateY(-50%);
        pointer-events: none;
        color: #94a3b8;
    }

    .tab-search input {
        width: 100%;
        padding: 9px 14px 9px 36px;
        border-radius: 10px;
        border: 1.5px solid #e2e8f0;
        font-size: .8rem;
        background: #f8fafc;
        font-family: inherit;
        outline: none;
        transition: border .2s, box-shadow .2s;
        color: #334155;
    }

    .tab-search input:focus {
        border-color: #0d9488;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, .12);
        background: #fff;
    }

    @media(max-width: 640px) {
        .tab-strip { flex-direction: column; align-items: stretch; }
        .tab-buttons { width: 100%; }
        .tab-search { width: 100%; }
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
        <h1 style="font-size:1.75rem;font-weight:800;color:#0f172a;letter-spacing:-.04em;line-height:1;">Manajemen FAQ</h1>
        <p style="font-size:.85rem;color:#64748b;margin-top:4px;">Kelola pertanyaan yang sering ditanyakan santri dan wali</p>
    </div>
    <div style="display:flex;align-items:center;gap:10px;">
        <a href="{{ route('faqs.create') }}" class="btn-teal">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19" />
                <line x1="5" y1="12" x2="19" y2="12" />
            </svg>
            Tambah FAQ
        </a>
    </div>
</div>

{{-- STAT CARDS --}}
<div class="faq-stats">
    <div class="st-card tc-indigo">
        <div class="st-ico" style="background:#eef2ff;color:#6366f1;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3" />
                <line x1="12" y1="17" x2="12.01" y2="17" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ count($faqs) }}</div>
            <div class="st-lbl">Total FAQ</div>
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
            <div class="st-val">{{ collect($faqs)->where('is_active', true)->count() }}</div>
            <div class="st-lbl">Aktif</div>
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
            <div class="st-val">{{ collect($faqs)->where('is_active', false)->count() }}</div>
            <div class="st-lbl">Nonaktif</div>
        </div>
    </div>
    <div class="st-card tc-slate">
        <div class="st-ico" style="background:#f1f5f9;color:#475569;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6" />
                <line x1="8" y1="12" x2="21" y2="12" />
                <line x1="8" y1="18" x2="21" y2="18" />
                <line x1="3" y1="6" x2="3.01" y2="6" />
                <line x1="3" y1="12" x2="3.01" y2="12" />
                <line x1="3" y1="18" x2="3.01" y2="18" />
            </svg>
        </div>
        <div>
            <div class="st-val">{{ count($kategoris) }}</div>
            <div class="st-lbl">Kategori</div>
        </div>
    </div>
</div>

{{-- MAIN TABLE CARD --}}
<div class="tcard" x-data="{
    activeTab: 'semua',
    search: '',
    deleteModal: false,
    deleteTarget: null,
    faqs: @js($faqs),
    get filtered() {
        return this.faqs.filter(f => {
            const q = this.search.toLowerCase();
            const matchS = !q || f.pertanyaan.toLowerCase().includes(q) || f.kategori.toLowerCase().includes(q) || f.jawaban.toLowerCase().includes(q);
            const matchT = this.activeTab === 'semua'
                || (this.activeTab === 'aktif'    &&  f.is_active)
                || (this.activeTab === 'nonaktif' && !f.is_active);
            return matchS && matchT;
        });
    },
    get aktifCount()    { return this.faqs.filter(f =>  f.is_active).length; },
    get nonaktifCount() { return this.faqs.filter(f => !f.is_active).length; },
    kategoriColor(k) {
        const palette = ['bdg-indigo','bdg-teal','bdg-blue','bdg-purple','bdg-amber','bdg-green'];
        let hash = 0;
        for (let i = 0; i < k.length; i++) hash = k.charCodeAt(i) + ((hash << 5) - hash);
        return palette[Math.abs(hash) % palette.length];
    },
    openDelete(faq) {
        this.deleteTarget = faq;
        this.deleteModal = true;
    },
    submitDelete() {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '/faqs/' + this.deleteTarget.id;
        const t = document.createElement('input'); t.type='hidden'; t.name='_token'; t.value=document.querySelector('meta[name=csrf-token]').content; f.appendChild(t);
        const m = document.createElement('input'); m.type='hidden'; m.name='_method'; m.value='DELETE'; f.appendChild(m);
        document.body.appendChild(f); f.submit();
    }
}">

    {{-- Tab strip --}}
    <div class="tab-strip">
        <div class="tab-buttons">
            <button class="tab-btn" :class="activeTab==='semua' ? 'active' : ''" @click="activeTab='semua'">
                Semua <span class="tab-count" x-text="faqs.length"></span>
            </button>
            <button class="tab-btn" :class="activeTab==='aktif' ? 'active' : ''" @click="activeTab='aktif'">
                Aktif <span class="tab-count" x-text="aktifCount"></span>
            </button>
            <button class="tab-btn" :class="activeTab==='nonaktif' ? 'active' : ''" @click="activeTab='nonaktif'">
                Nonaktif <span class="tab-count" x-text="nonaktifCount"></span>
            </button>
        </div>

        <div class="tab-search">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8" />
                <line x1="21" y1="21" x2="16.65" y2="16.65" />
            </svg>
            <input type="search" placeholder="Cari pertanyaan, kategori…" x-model="search" autocomplete="off">
        </div>
    </div>

    <div class="result-bar">
        <span>Menampilkan <strong x-text="filtered.length"></strong> dari <strong x-text="faqs.length"></strong> FAQ</span>
    </div>

    <div style="overflow-x:auto;">
        <table class="dt">
            <thead>
                <tr>
                    <th style="width:48px;text-align:center;">No</th>
                    <th>Pertanyaan</th>
                    <th style="text-align:center;">Kategori</th>
                    <th>Jawaban</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr class="empty-row" x-show="filtered.length === 0">
                    <td colspan="6">
                        <div class="empty-ico-wrap">❓</div>
                        <p style="font-size:.82rem;font-weight:600;">Tidak ada FAQ ditemukan</p>
                    </td>
                </tr>
                <template x-for="(faq, idx) in filtered" :key="faq.id">
                    <tr>
                        <td style="text-align:center;">
                            <span style="font-size:.75rem;font-weight:700;color:#6366f1;background:#eef2ff;width:26px;height:26px;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;" x-text="faq.urutan"></span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:flex-start;gap:10px;">
                                <div style="width:4px;min-height:36px;border-radius:4px;flex-shrink:0;margin-top:2px;background:linear-gradient(180deg,#6366f1,#a5b4fc);"></div>
                                <span style="font-size:.85rem;font-weight:700;color:#1e293b;" x-text="faq.pertanyaan"></span>
                            </div>
                        </td>
                        <td style="text-align:center;">
                            <span class="bdg" :class="kategoriColor(faq.kategori)" x-text="faq.kategori"></span>
                        </td>
                        <td style="max-width:240px;">
                            <div class="jawaban-preview" x-text="faq.jawaban"></div>
                        </td>
                        <td style="text-align:center;">
                            <template x-if="faq.is_active">
                                <span class="bdg bdg-green">Aktif</span>
                            </template>
                            <template x-if="!faq.is_active">
                                <span class="bdg bdg-slate">Nonaktif</span>
                            </template>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;justify-content:center;gap:5px;">
                                <a :href="`/faqs/${faq.id}/edit`" class="ico-btn edit" title="Edit">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                    </svg>
                                </a>
                                <button class="ico-btn del" title="Hapus" @click="openDelete(faq)">
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

    <div style="padding:12px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:.75rem;color:#64748b;">
            Menampilkan <strong x-text="filtered.length" style="color:#0f172a;"></strong>
            dari <strong x-text="faqs.length" style="color:#0f172a;"></strong> FAQ
        </span>
    </div>

    {{-- MODAL HAPUS --}}
    <div class="smodal-overlay" :class="{ open: deleteModal }" @click.self="deleteModal=false">
        <div class="smodal-box" style="max-width:420px;">
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
                <div style="font-size:1.1rem;font-weight:800;color:#0f172a;margin-bottom:6px;">Hapus FAQ?</div>
                <p style="font-size:.82rem;color:#64748b;margin-bottom:14px;">Tindakan ini tidak dapat dibatalkan. FAQ berikut akan dihapus permanen:</p>
                <div style="padding:12px 14px;background:#fff7f7;border:1px solid #fecaca;border-radius:12px;text-align:left;">
                    <div style="font-size:.85rem;font-weight:700;color:#0f172a;" x-text="deleteTarget?.pertanyaan"></div>
                    <div style="font-size:.75rem;color:#64748b;margin-top:3px;" x-text="deleteTarget?.kategori"></div>
                </div>
            </div>
            <div class="smodal-ftr">
                <button @click="deleteModal=false"
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
                </button>
            </div>
        </div>
    </div>

</div>{{-- end tcard --}}

@endsection

@push('scripts')
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