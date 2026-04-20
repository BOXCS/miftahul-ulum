@extends('layouts.app')

@section('title', 'Data Wali Santri')
@section('breadcrumb', 'Wali Santri')

@section('content')

{{-- ═══════════════════════════════════════════════════════════ --}}
<div
    x-data="{
        search: '',
        filterHubungan: '',
        addModal: false,
        editModal: false,
        selectedParent: null,
        form: { id: '', name: '', relationship: '', phone: '', email: '', address: '' },
        parents: @js($parents),
        get filtered() {
            return this.parents.filter(p => {
                const matchSearch = this.search === '' ||
                    p.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    (p.phone && p.phone.includes(this.search));
                const matchHubungan = this.filterHubungan === '' || p.relationship === this.filterHubungan;
                return matchSearch && matchHubungan;
            });
        },
        openAdd() {
            this.form = { id: '', name: '', relationship: '', phone: '', email: '', address: '' };
            this.addModal = true;
        },
        openEdit(parent) {
            this.selectedParent = parent;
            this.form = { ...parent };
            this.editModal = true;
        },
        initials(name) {
            return name.split(' ').slice(0,2).map(w => w[0]).join('').toUpperCase();
        }
    }"
>

<div class="page-header animate-fade-in">
    <div>
        <h1 class="page-title">Data Wali Santri</h1>
        <p class="page-subtitle">Kelola data orang tua & wali santri Pondok Pesantren Miftahul Ulum</p>
    </div>

    <button @click="openAdd()" class="btn btn-primary btn-lg shrink-0">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Wali
    </button>
</div>

{{-- STATS CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-6 animate-fade-in stagger-1">
    <div class="stat-card green">
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium text-slate-500 mb-0.5">Total Wali</p>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['total'] }}</p>
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        </div>
        <div class="flex-1">
            <p class="text-sm font-medium text-slate-500 mb-0.5">Terhubung ke Santri</p>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['terhubung'] }}</p>
        </div>
    </div>
</div>

{{-- DATA TABLE --}}
<div class="card animate-fade-in stagger-3">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h2 class="text-base font-semibold text-slate-800">Daftar Wali Santri</h2>
        <div class="flex items-center gap-2">
            <input type="text" x-model="search" placeholder="Cari wali..." class="form-control form-control-sm">
            <select x-model="filterHubungan" class="form-select form-select-sm">
                <option value="">Semua</option>
                <option value="ayah">Ayah</option>
                <option value="ibu">Ibu</option>
                <option value="wali">Wali</option>
            </select>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Nama Wali</th>
                    <th>Hubungan</th>
                    <th>No. HP</th>
                    <th>Santri</th>
                    <th style="width:100px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr x-show="filtered.length === 0">
                    <td colspan="6" class="py-16 text-center text-slate-400 italic">Tidak ada data wali santri</td>
                </tr>
                <template x-for="(parent, index) in filtered" :key="parent.id">
                    <tr>
                        <td><span class="text-slate-400 text-sm" x-text="index + 1"></span></td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar avatar-sm" style="background:#dcfce7; color:#16a34a;">
                                    <span class="text-xs font-bold" x-text="initials(parent.name)"></span>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm" x-text="parent.name"></p>
                                    <p class="text-xs text-slate-400" x-text="parent.email || '-'"></p>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-blue" x-text="parent.relationship"></span></td>
                        <td><span class="text-sm text-slate-600" x-text="parent.phone"></span></td>
                        <td>
                            <template x-for="student in parent.students" :key="student.id">
                                <div class="text-xs font-medium text-slate-700 bg-slate-100 px-2 py-1 rounded mb-1" x-text="student.name"></div>
                            </template>
                        </td>
                        <td>
                            <div class="flex items-center gap-1">
                                <button @click="openEdit(parent)" class="btn btn-ghost btn-sm text-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form :action="`/parents/${parent.id}`" method="POST" onsubmit="return confirm('Hapus data wali?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-ghost btn-sm text-red-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL ADD/EDIT --}}
<div class="modal-overlay" x-show="addModal || editModal" style="display:none;" @click.self="addModal = false; editModal = false;">
    <div class="modal-box max-w-md w-full mx-4">
        <div class="modal-header">
            <h3 class="font-semibold text-slate-800" x-text="addModal ? 'Tambah Wali Santri' : 'Edit Wali Santri'"></h3>
            <button @click="addModal = false; editModal = false;">&times;</button>
        </div>
        <form :action="addModal ? '{{ route('parents.store') }}' : `/parents/${form.id}`" method="POST">
            @csrf
            <template x-if="editModal">
                <input type="hidden" name="_method" value="PUT">
            </template>
            <div class="modal-body space-y-4">
                <div>
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" x-model="form.name" required>
                </div>
                <div>
                    <label class="form-label">Hubungan</label>
                    <select name="relationship" class="form-select" x-model="form.relationship" required>
                        <option value="ayah">Ayah</option>
                        <option value="ibu">Ibu</option>
                        <option value="wali">Wali</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">No. HP / WhatsApp</label>
                    <input type="text" name="phone" class="form-control" x-model="form.phone" required>
                </div>
                <div>
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" x-model="form.email">
                </div>
                <div>
                    <label class="form-label">Alamat</label>
                    <textarea name="address" class="form-control" rows="3" x-model="form.address" required></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" @click="addModal = false; editModal = false;" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection
