@extends('layouts.app')

@section('title', 'Manajemen Perizinan')
@section('breadcrumb', 'Perizinan')

@section('content')

{{-- ═══════════════════════════════════════════════════════════
     MAIN PAGE COMPONENT
════════════════════════════════════════════════════════════ --}}
<div
    x-data="{
        activeTab: 'semua',
        detailModal: false,
        approveModal: false,
        rejectModal: false,
        selectedItem: null,
        rejectReason: '',

        permissions: @js($permissions),

        get filtered() {
            if (this.activeTab === 'semua')     return this.permissions;
            if (this.activeTab === 'pending')   return this.permissions.filter(p => p.status === 'pending');
            if (this.activeTab === 'disetujui') return this.permissions.filter(p => p.status === 'disetujui');
            if (this.activeTab === 'ditolak')   return this.permissions.filter(p => p.status === 'ditolak');
            return this.permissions;
        },
        get pendingCount()   { return this.permissions.filter(p => p.status === 'pending').length; },
        get approvedCount()  { return this.permissions.filter(p => p.status === 'disetujui').length; },
        get rejectedCount()  { return this.permissions.filter(p => p.status === 'ditolak').length; },

        openDetail(item) {
            this.selectedItem = item;
            this.detailModal  = true;
        },
        openApprove(item) {
            this.selectedItem = item;
            this.approveModal = true;
        },
        openReject(item) {
            this.selectedItem  = item;
            this.rejectReason  = '';
            this.rejectModal   = true;
        },
        confirmApprove() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('/permissions') }}/${this.selectedItem.id}/approve`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = csrfToken.getAttribute('content');
                form.appendChild(input);
            }
            
            const approvedByInput = document.createElement('input');
            approvedByInput.type = 'hidden';
            approvedByInput.name = 'approved_by';
            approvedByInput.value = 'Admin';
            form.appendChild(approvedByInput);
            
            document.body.appendChild(form);
            form.submit();
        },
        confirmReject() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('/permissions') }}/${this.selectedItem.id}/reject`;
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = csrfToken.getAttribute('content');
                form.appendChild(input);
            }
            
            const approvedByInput = document.createElement('input');
            approvedByInput.type = 'hidden';
            approvedByInput.name = 'approved_by';
            approvedByInput.value = 'Admin';
            form.appendChild(approvedByInput);
            
            document.body.appendChild(form);
            form.submit();
        },

        jenisColor(j) {
            const map = { Pulang: 'badge-purple', Sakit: 'badge-blue', Kegiatan: 'badge-teal', Keluar: 'badge-amber' };
            return map[j] || 'badge-gray';
        },
        jenisIcon(j) {
            const icons = {
                Pulang:   'home',
                Sakit:    'heart',
                Kegiatan: 'star',
                Keluar:   'log-out',
            };
            return icons[j] || 'file';
        },
        statusColor(s) {
            if (s === 'Pending')   return 'badge-amber';
            if (s === 'Disetujui') return 'badge-green';
            if (s === 'Ditolak')   return 'badge-red';
            return 'badge-gray';
        },
        avatarBg(s) {
            if (s === 'Pending')   return 'background:linear-gradient(135deg,#d97706,#fbbf24)';
            if (s === 'Disetujui') return 'background:linear-gradient(135deg,#16a34a,#4ade80)';
            if (s === 'Ditolak')   return 'background:linear-gradient(135deg,#dc2626,#f87171)';
            return 'background:linear-gradient(135deg,#64748b,#94a3b8)';
        },
        pengajuColor(p) {
            if (p === 'Wali')    return 'badge-blue';
            if (p === 'Guru')    return 'badge-purple';
            if (p === 'Petugas') return 'badge-teal';
            return 'badge-gray';
        },
    }"
>

{{-- ══════════════════════════════════════════════════════════
     PAGE HEADER
══════════════════════════════════════════════════════════ --}}
<div class="page-header animate-fade-in">
    <div>
        {{-- Breadcrumb --}}
        <nav class="breadcrumb" aria-label="breadcrumb">
            <a href="{{ url('/dashboard') }}" class="breadcrumb-item">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                     class="inline -mt-0.5 mr-0.5">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
                Dashboard
            </a>
            <span class="breadcrumb-sep">/</span>
            <span class="breadcrumb-item active">Perizinan</span>
        </nav>
        <h1 class="page-title">Manajemen Perizinan</h1>
        <p class="page-subtitle">Kelola permohonan izin santri dengan cepat dan transparan</p>
    </div>

    <div class="flex items-center gap-3 shrink-0">
        <button class="btn btn-secondary btn-lg gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="7 10 12 15 17 10"/>
                <line x1="12" y1="15" x2="12" y2="3"/>
            </svg>
            Export
        </button>
        <button class="btn btn-primary btn-lg gap-2" onclick="location.href='{{ route('permissions.create') }}'">
            <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Tambah Izin
        </button>
    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     STATS CARDS
══════════════════════════════════════════════════════════ --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5 animate-fade-in stagger-1">

    {{-- Total Izin --}}
    <div class="stat-card purple">
        <div class="stat-icon purple">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
                <polyline points="10 9 9 9 8 9"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-500 mb-0.5">Total Izin</p>
            <p class="text-3xl font-bold text-slate-800" x-text="permissions.length">8</p>
            <p class="text-xs text-slate-400 mt-1">Semua permohonan</p>
        </div>
    </div>

    {{-- Menunggu Persetujuan (amber, highlighted) --}}
    <div class="stat-card amber" style="box-shadow:0 0 0 2px #fbbf24, 0 10px 15px -3px rgb(0 0 0/0.08);">
        <div class="stat-icon amber">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="8" x2="12" y2="12"/>
                <line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-500 mb-0.5">Menunggu</p>
            <div class="flex items-end gap-2">
                <p class="text-3xl font-bold text-amber-600" x-text="pendingCount">3</p>
                <span class="mb-1 inline-flex items-center gap-1 text-xs font-semibold text-amber-600 animate-pulse">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Perlu tindakan
                </span>
            </div>
        </div>
    </div>

    {{-- Disetujui --}}
    <div class="stat-card green">
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-500 mb-0.5">Disetujui</p>
            <p class="text-3xl font-bold text-slate-800" x-text="approvedCount">4</p>
            <p class="text-xs text-slate-400 mt-1">Izin dikabulkan</p>
        </div>
    </div>

    {{-- Ditolak --}}
    <div class="stat-card red">
        <div class="stat-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-slate-500 mb-0.5">Ditolak</p>
            <p class="text-3xl font-bold text-slate-800" x-text="rejectedCount">1</p>
            <p class="text-xs text-slate-400 mt-1">Permohonan ditolak</p>
        </div>
    </div>

</div>


{{-- ══════════════════════════════════════════════════════════
     FILTER TABS
══════════════════════════════════════════════════════════ --}}
<div class="card px-6 py-4 mb-5 animate-fade-in stagger-2">
    <div class="tab-list">

        {{-- Semua --}}
        <button
            @click="activeTab = 'semua'"
            :class="activeTab === 'semua' ? 'tab-item active' : 'tab-item'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="8" y1="6" x2="21" y2="6"/>
                <line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/>
                <line x1="3" y1="6" x2="3.01" y2="6"/>
                <line x1="3" y1="12" x2="3.01" y2="12"/>
                <line x1="3" y1="18" x2="3.01" y2="18"/>
            </svg>
            Semua
            <span
                class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-xs font-bold"
                :class="activeTab === 'semua' ? 'bg-green-600 text-white' : 'bg-slate-200 text-slate-600'"
                x-text="permissions.length"
            ></span>
        </button>

        {{-- Pending --}}
        <button
            @click="activeTab = 'pending'"
            :class="activeTab === 'pending' ? 'tab-item active' : 'tab-item'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12 6 12 12 16 14"/>
            </svg>
            Pending
            <span
                class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-xs font-bold"
                :class="activeTab === 'pending' ? 'bg-green-600 text-white' : 'bg-amber-100 text-amber-700'"
                x-text="pendingCount"
            ></span>
        </button>

        {{-- Disetujui --}}
        <button
            @click="activeTab = 'disetujui'"
            :class="activeTab === 'disetujui' ? 'tab-item active' : 'tab-item'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>
            Disetujui
            <span
                class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-xs font-bold"
                :class="activeTab === 'disetujui' ? 'bg-green-600 text-white' : 'bg-slate-200 text-slate-600'"
                x-text="approvedCount"
            ></span>
        </button>

        {{-- Ditolak --}}
        <button
            @click="activeTab = 'ditolak'"
            :class="activeTab === 'ditolak' ? 'tab-item active' : 'tab-item'"
        >
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
            Ditolak
            <span
                class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full text-xs font-bold"
                :class="activeTab === 'ditolak' ? 'bg-green-600 text-white' : 'bg-slate-200 text-slate-600'"
                x-text="rejectedCount"
            ></span>
        </button>

    </div>
</div>


{{-- ══════════════════════════════════════════════════════════
     PENDING ALERT BANNER
══════════════════════════════════════════════════════════ --}}
<div
    x-show="pendingCount > 0 && activeTab !== 'ditolak'"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    class="flex items-start gap-3 p-4 mb-5 bg-amber-50 border border-amber-200 rounded-2xl animate-fade-in"
>
    <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center shrink-0 mt-0.5">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
             stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
            <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
    </div>
    <div class="flex-1 min-w-0">
        <p class="text-sm font-semibold text-amber-800">
            Ada <span x-text="pendingCount" class="font-bold"></span> permohonan izin yang menunggu persetujuan Anda
        </p>
        <p class="text-xs text-amber-600 mt-0.5">
            Harap segera ditindaklanjuti agar santri mendapatkan kepastian izin tepat waktu.
        </p>
    </div>
    <button
        @click="activeTab = 'pending'"
        class="btn btn-sm shrink-0"
        style="background:#d97706; color:#fff; border:none;"
    >
        Tinjau Sekarang
    </button>
</div>


{{-- ══════════════════════════════════════════════════════════
     DATA TABLE CARD
══════════════════════════════════════════════════════════ --}}
<div class="card animate-fade-in stagger-3">

    {{-- Card Header --}}
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-purple-100 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                     stroke="#7c3aed" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                    <polyline points="14 2 14 8 20 8"/>
                    <line x1="16" y1="13" x2="8" y2="13"/>
                    <line x1="16" y1="17" x2="8" y2="17"/>
                </svg>
            </div>
            <div>
                <h2 class="text-base font-semibold text-slate-800">Daftar Permohonan Izin</h2>
                <p class="text-xs text-slate-500">
                    Menampilkan
                    <span class="font-semibold text-slate-700" x-text="filtered.length"></span>
                    permohonan
                    <span x-show="activeTab !== 'semua'" class="capitalize" x-text="'— ' + activeTab"></span>
                </p>
            </div>
        </div>

        {{-- Search --}}
        <div class="relative">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
            </span>
            <input
                type="text"
                placeholder="Cari santri…"
                class="form-control pl-9"
                style="width:220px;"
            >
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:50px;">No</th>
                    <th>Santri</th>
                    <th>Kelas</th>
                    <th>Jenis Izin</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Status</th>
                    <th>Diajukan</th>
                    <th style="width:180px;">Aksi</th>
                </tr>
            </thead>
            <tbody>

                {{-- Empty state --}}
                <template x-if="filtered.length === 0">
                    <tr>
                        <td colspan="9" class="py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24"
                                         fill="none" stroke="#94a3b8" stroke-width="1.5"
                                         stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="12" y1="8" x2="12" y2="12"/>
                                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-600">Tidak ada permohonan ditemukan</p>
                                    <p class="text-sm text-slate-400 mt-1">Belum ada izin dalam kategori ini</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                </template>

                <template x-for="(item, index) in filtered" :key="item.id">
                    <tr :class="item.status === 'Pending' ? 'bg-amber-50/30' : ''">

                        {{-- No --}}
                        <td>
                            <span class="text-slate-400 text-sm font-medium" x-text="index + 1"></span>
                        </td>

                        {{-- Santri --}}
                        <td>
                            <div class="flex items-center gap-3">
                                <div
                                    class="avatar avatar-sm shrink-0"
                                    :style="avatarBg(item.status)"
                                >
                                    <span class="text-white text-xs font-bold" x-text="item.avatar"></span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm" x-text="item.santri"></p>
                                    <p class="text-xs text-slate-400">NIS: 2024<span x-text="item.id.toString().padStart(3,'0')"></span></p>
                                </div>
                            </div>
                        </td>

                        {{-- Kelas --}}
                        <td>
                            <span
                                class="inline-flex items-center justify-center w-10 h-7 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold"
                                x-text="item.kelas"
                            ></span>
                        </td>

                        {{-- Jenis Izin --}}
                        <td>
                            <span class="badge" :class="jenisColor(item.jenis)" x-text="item.jenis"></span>
                        </td>

                        {{-- Tanggal --}}
                        <td>
                            <div class="flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24"
                                     fill="none" stroke="#94a3b8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                <span class="text-sm text-slate-600 whitespace-nowrap" x-text="item.tanggal"></span>
                            </div>
                        </td>

                        {{-- Keterangan --}}
                        <td>
                            <p
                                class="text-sm text-slate-600 max-w-[200px] truncate"
                                :title="item.keterangan"
                                x-text="item.keterangan"
                            ></p>
                        </td>

                        {{-- Status --}}
                        <td>
                            <div class="flex items-center gap-1.5">
                                <template x-if="item.status === 'Pending'">
                                    <span class="relative flex h-2 w-2">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2 w-2 bg-amber-500"></span>
                                    </span>
                                </template>
                                <span class="badge" :class="statusColor(item.status)" x-text="item.status"></span>
                            </div>
                        </td>

                        {{-- Diajukan --}}
                        <td>
                            <div class="flex flex-col gap-1">
                                <span class="badge" :class="pengajuColor(item.diajukan)" x-text="item.diajukan"></span>
                                <span class="text-xs text-slate-400" x-text="item.tglAjuan"></span>
                            </div>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="flex items-center gap-1.5">
                                {{-- Pending actions: Setujui + Tolak --}}
                                <template x-if="item.status === 'Pending'">
                                    <div class="flex items-center gap-1.5">
                                        {{-- Setujui --}}
                                        <button
                                            @click="openApprove(item)"
                                            class="btn btn-sm gap-1"
                                            style="background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0;"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="2.5"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="20 6 9 17 4 12"/>
                                            </svg>
                                            Setujui
                                        </button>

                                        {{-- Tolak --}}
                                        <button
                                            @click="openReject(item)"
                                            class="btn btn-sm gap-1"
                                            style="background:#fff1f2; color:#e11d48; border:1px solid #fecdd3;"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="2.5"
                                                 stroke-linecap="round" stroke-linejoin="round">
                                                <line x1="18" y1="6" x2="6" y2="18"/>
                                                <line x1="6" y1="6" x2="18" y2="18"/>
                                            </svg>
                                            Tolak
                                        </button>
                                    </div>
                                </template>

                                {{-- Non-pending: Detail button --}}
                                <template x-if="item.status !== 'Pending'">
                                    <button
                                        @click="openDetail(item)"
                                        class="btn btn-sm gap-1.5"
                                        style="background:#f8fafc; color:#475569; border:1px solid #e2e8f0;"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24"
                                             fill="none" stroke="currentColor" stroke-width="2.5"
                                             stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="11" cy="11" r="8"/>
                                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                        </svg>
                                        Detail
                                    </button>
                                </template>
                            </div>
                        </td>
                    </tr>
                </template>

            </tbody>
        </table>
    </div>

    {{-- Table Footer --}}
    <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between flex-wrap gap-3">
        <p class="text-sm text-slate-500">
            Menampilkan
            <span class="font-semibold text-slate-700" x-text="filtered.length"></span>
            dari
            <span class="font-semibold text-slate-700" x-text="permissions.length"></span>
            permohonan izin
        </p>
        <div class="flex items-center gap-1">
            <button class="btn btn-ghost btn-sm btn-icon opacity-40" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>
            <button class="btn btn-primary btn-sm px-3">1</button>
            <button class="btn btn-ghost btn-sm btn-icon opacity-40" disabled>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     MODAL: DETAIL PERIZINAN
════════════════════════════════════════════════════════════ --}}
<div
    x-show="detailModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="modal-overlay"
    @keydown.escape.window="detailModal = false"
    @click.self="detailModal = false"
>
    <div
        x-show="detailModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        @click.stop
        class="modal-box"
        style="max-width:560px;"
    >
        {{-- Header --}}
        <div class="modal-header">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="#475569" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Detail Permohonan Izin</h3>
                    <p class="text-xs text-slate-500" x-text="selectedItem ? 'ID #' + selectedItem.id.toString().padStart(4,'0') : ''"></p>
                </div>
            </div>
            <button
                @click="detailModal = false"
                class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        {{-- Body --}}
        <div class="modal-body space-y-5" x-show="selectedItem">

            {{-- Student info header --}}
            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200">
                <div
                    class="avatar avatar-lg shrink-0"
                    :style="selectedItem ? avatarBg(selectedItem.status) : ''"
                >
                    <span class="text-white text-base font-bold" x-text="selectedItem ? selectedItem.avatar : ''"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-lg font-bold text-slate-800" x-text="selectedItem ? selectedItem.santri : ''"></p>
                    <p class="text-sm text-slate-500">
                        Kelas <span class="font-semibold" x-text="selectedItem ? selectedItem.kelas : ''"></span>
                        &bull; NIS: 2024<span x-text="selectedItem ? selectedItem.id.toString().padStart(3,'0') : ''"></span>
                    </p>
                </div>
                <div>
                    <span class="badge text-sm px-3 py-1.5" :class="selectedItem ? statusColor(selectedItem.status) : ''" x-text="selectedItem ? selectedItem.status : ''"></span>
                </div>
            </div>

            {{-- Detail grid --}}
            <div class="grid grid-cols-2 gap-4">
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1.5">Jenis Izin</p>
                    <span class="badge text-sm" :class="selectedItem ? jenisColor(selectedItem.jenis) : ''" x-text="selectedItem ? selectedItem.jenis : ''"></span>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1.5">Tanggal</p>
                    <p class="text-sm font-semibold text-slate-700" x-text="selectedItem ? selectedItem.tanggal : ''"></p>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1.5">Diajukan Oleh</p>
                    <span class="badge" :class="selectedItem ? pengajuColor(selectedItem.diajukan) : ''" x-text="selectedItem ? selectedItem.diajukan : ''"></span>
                </div>
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-1.5">Tanggal Ajuan</p>
                    <p class="text-sm font-semibold text-slate-700" x-text="selectedItem ? selectedItem.tglAjuan : ''"></p>
                </div>
            </div>

            {{-- Keterangan --}}
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <p class="text-xs font-medium text-slate-400 uppercase tracking-wide mb-2">Keterangan / Alasan</p>
                <p class="text-sm text-slate-700 leading-relaxed" x-text="selectedItem ? selectedItem.keterangan : ''"></p>
            </div>

            {{-- Catatan (if any) --}}
            <template x-if="selectedItem && selectedItem.catatan">
                <div
                    class="p-4 rounded-xl border"
                    :class="selectedItem.status === 'Disetujui' ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'"
                >
                    <div class="flex items-center gap-2 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                             :stroke="selectedItem.status === 'Disetujui' ? '#16a34a' : '#dc2626'"
                             stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="16" x2="12" y2="12"/>
                            <line x1="12" y1="8" x2="12.01" y2="8"/>
                        </svg>
                        <p
                            class="text-xs font-semibold uppercase tracking-wide"
                            :class="selectedItem.status === 'Disetujui' ? 'text-green-700' : 'text-red-700'"
                        >Catatan Admin</p>
                    </div>
                    <p
                        class="text-sm leading-relaxed"
                        :class="selectedItem.status === 'Disetujui' ? 'text-green-800' : 'text-red-800'"
                        x-text="selectedItem.catatan"
                    ></p>
                </div>
            </template>

        </div>

        {{-- Footer --}}
        <div class="modal-footer">
            <button @click="detailModal = false" class="btn btn-secondary flex-1">Tutup</button>
            <template x-if="selectedItem && selectedItem.status === 'Disetujui'">
                <button class="btn btn-ghost btn-sm gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Unduh Surat
                </button>
            </template>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     MODAL: KONFIRMASI SETUJUI
════════════════════════════════════════════════════════════ --}}
<div
    x-show="approveModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="modal-overlay"
    @keydown.escape.window="approveModal = false"
    @click.self="approveModal = false"
>
    <div
        x-show="approveModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        @click.stop
        class="modal-box"
        style="max-width:440px;"
    >
        <div class="modal-body text-center py-8">
            {{-- Success icon --}}
            <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center mx-auto mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none"
                     stroke="#16a34a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-2">Setujui Permohonan?</h3>
            <p class="text-sm text-slate-500 mb-3">
                Anda akan menyetujui permohonan izin santri:
            </p>

            {{-- Student info box --}}
            <div class="flex items-center gap-3 p-3 bg-green-50 rounded-xl border border-green-200 text-left mb-5" x-show="selectedItem">
                <div
                    class="avatar avatar-md shrink-0"
                    :style="selectedItem ? avatarBg(selectedItem.status) : ''"
                >
                    <span class="text-white text-sm font-bold" x-text="selectedItem ? selectedItem.avatar : ''"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-bold text-slate-800 text-sm" x-text="selectedItem ? selectedItem.santri : ''"></p>
                    <p class="text-xs text-slate-500">
                        <span class="badge" :class="selectedItem ? jenisColor(selectedItem.jenis) : ''" x-text="selectedItem ? selectedItem.jenis : ''"></span>
                        &nbsp;
                        <span x-text="selectedItem ? selectedItem.tanggal : ''"></span>
                    </p>
                </div>
            </div>

            {{-- Optional note --}}
            <div class="text-left">
                <label class="form-label">Catatan Persetujuan (opsional)</label>
                <textarea
                    rows="2"
                    placeholder="Tambahkan catatan untuk santri/wali…"
                    class="form-control"
                ></textarea>
            </div>
        </div>

        <div class="modal-footer">
            <button @click="approveModal = false" class="btn btn-secondary flex-1">Batal</button>
            <button
                @click="confirmApprove()"
                class="btn flex-1"
                style="background:linear-gradient(135deg,#16a34a,#15803d); color:#fff; border:none;"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Ya, Setujui
            </button>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════
     MODAL: KONFIRMASI TOLAK
════════════════════════════════════════════════════════════ --}}
<div
    x-show="rejectModal"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="modal-overlay"
    @keydown.escape.window="rejectModal = false"
    @click.self="rejectModal = false"
>
    <div
        x-show="rejectModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-4"
        @click.stop
        class="modal-box"
        style="max-width:460px;"
    >
        <div class="modal-header">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-red-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                         stroke="#dc2626" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-800">Tolak Permohonan Izin</h3>
                    <p class="text-xs text-slate-500" x-text="selectedItem ? selectedItem.santri + ' — ' + selectedItem.jenis : ''"></p>
                </div>
            </div>
            <button
                @click="rejectModal = false"
                class="w-8 h-8 rounded-lg hover:bg-slate-100 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div class="modal-body space-y-4">
            {{-- Warning --}}
            <div class="flex items-start gap-3 p-3.5 bg-red-50 rounded-xl border border-red-200">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="#dc2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                     class="shrink-0 mt-0.5">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <p class="text-sm text-red-700">
                    Penolakan izin akan diberitahukan kepada santri dan wali. Pastikan Anda mengisi alasan penolakan dengan jelas.
                </p>
            </div>

            {{-- Request summary --}}
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 space-y-2" x-show="selectedItem">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Santri</span>
                    <span class="font-semibold text-slate-800" x-text="selectedItem ? selectedItem.santri : ''"></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Jenis</span>
                    <span class="badge" :class="selectedItem ? jenisColor(selectedItem.jenis) : ''" x-text="selectedItem ? selectedItem.jenis : ''"></span>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <span class="text-slate-500">Tanggal</span>
                    <span class="font-medium text-slate-700" x-text="selectedItem ? selectedItem.tanggal : ''"></span>
                </div>
            </div>

            {{-- Reject reason --}}
            <div>
                <label class="form-label">
                    Alasan Penolakan <span class="text-red-500">*</span>
                </label>
                <textarea
                    x-model="rejectReason"
                    rows="3"
                    placeholder="Tuliskan alasan penolakan yang jelas untuk santri dan wali…"
                    class="form-control"
                    :class="rejectReason.length === 0 ? '' : 'border-red-300'"
                ></textarea>
                <p class="text-xs text-slate-400 mt-1.5">Minimal 10 karakter</p>
            </div>

            {{-- Quick reason templates --}}
            <div>
                <p class="text-xs font-medium text-slate-500 mb-2">Pilih alasan cepat:</p>
                <div class="flex flex-wrap gap-2">
                    <button
                        @click="rejectReason = 'Tidak sesuai jadwal kepulangan yang telah ditentukan pondok'"
                        class="px-3 py-1.5 rounded-full text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all border border-slate-200"
                    >
                        Tidak sesuai jadwal
                    </button>
                    <button
                        @click="rejectReason = 'Dokumen pendukung tidak lengkap atau tidak valid'"
                        class="px-3 py-1.5 rounded-full text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all border border-slate-200"
                    >
                        Dokumen tidak lengkap
                    </button>
                    <button
                        @click="rejectReason = 'Santri sedang memiliki tanggungan kegiatan pondok yang tidak dapat ditinggalkan'"
                        class="px-3 py-1.5 rounded-full text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all border border-slate-200"
                    >
                        Ada kegiatan pondok
                    </button>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button @click="rejectModal = false" class="btn btn-secondary">Batal</button>
            <button
                @click="confirmReject()"
                class="btn btn-danger"
                :disabled="rejectReason.length < 10"
                :class="rejectReason.length < 10 ? 'opacity-50 cursor-not-allowed' : ''"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="15" y1="9" x2="9" y2="15"/>
                    <line x1="9" y1="9" x2="15" y2="15"/>
                </svg>
                Tolak Izin
            </button>
        </div>
    </div>
</div>


</div>{{-- END: x-data wrapper --}}

@endsection
