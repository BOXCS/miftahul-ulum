@extends('layouts.app')

@section('title', 'Data Staff')
@section('breadcrumb', 'Data Staff')

@section('content')

<div class="animate-fade-in" x-data="{
    search: '',
    roleFilter: '',
    addModal: false,
    editModal: false,
    form: { id: '', name: '', email: '', role: 'admin', password: '', password_confirmation: '' },
    staffList: @js($staff),
    get filtered() {
        return this.staffList.filter(s => {
            const matchSearch = this.search === '' ||
                s.name.toLowerCase().includes(this.search.toLowerCase()) ||
                s.email.toLowerCase().includes(this.search.toLowerCase());
            const matchRole = this.roleFilter === '' || s.role === this.roleFilter;
            return matchSearch && matchRole;
        });
    },
    openAdd() {
        this.form = { id: '', name: '', email: '', role: 'admin', password: '', password_confirmation: '' };
        this.addModal = true;
    },
    openEdit(s) {
        this.form = { id: s.id, name: s.name, email: s.email, role: s.role, password: '', password_confirmation: '' };
        this.editModal = true;
    },
    initials(name) {
        return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
    }
}">

    {{-- PAGE HEADER --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title animate-fade-in">Data Staff</h1>
                <p class="page-subtitle animate-fade-in stagger-1">
                    Kelola akun staff Pondok Pesantren Miftahul Ulum
                </p>
            </div>
            @if(auth()->user()->isSuperAdmin())
            <div class="flex items-center gap-2 animate-fade-in stagger-2">
                <button type="button" class="btn btn-primary" @click="openAdd()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19"/>
                        <line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    Tambah Staff
                </button>
            </div>
            @endif
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
        <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl border animate-fade-in"
             style="background:#f0fdf4; border-color:#bbf7d0; color:#15803d;">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- STATS --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5 animate-fade-in stagger-1">

        <div class="card px-5 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                style="background:#dcfce7; color:#16a34a;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold leading-none" style="color:#1e293b;">{{ $stats['total'] }}</p>
                <p class="text-xs font-medium mt-0.5" style="color:#94a3b8;">Total Staff</p>
            </div>
        </div>

        <div class="card px-5 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                style="background:#fef3c7; color:#d97706;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold leading-none" style="color:#1e293b;">{{ $stats['superadmin'] }}</p>
                <p class="text-xs font-medium mt-0.5" style="color:#94a3b8;">Super Admin</p>
            </div>
        </div>

        <div class="card px-5 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                style="background:#dbeafe; color:#2563eb;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold leading-none" style="color:#1e293b;">{{ $stats['admin'] }}</p>
                <p class="text-xs font-medium mt-0.5" style="color:#94a3b8;">Admin</p>
            </div>
        </div>

    </div>

    {{-- TABLE --}}
    <div class="card animate-fade-in stagger-2">

        {{-- Filter Row --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 px-5 py-4 border-b border-slate-100">
            <div class="relative flex-1 min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color:#94a3b8;"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="search" class="form-control pl-9 pr-4" placeholder="Cari nama atau email…"
                    x-model="search">
            </div>
            <select class="form-select sm:w-40" x-model="roleFilter">
                <option value="">Semua Role</option>
                <option value="superadmin">Super Admin</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        {{-- Result Summary --}}
        <div class="flex items-center justify-between px-5 py-2.5 border-b border-slate-50" style="background:#f8fafc;">
            <p class="text-xs" style="color:#64748b;">
                Menampilkan <span class="font-semibold" style="color:#1e293b;" x-text="filtered.length"></span> dari
                <span class="font-semibold" style="color:#1e293b;" x-text="staffList.length"></span> staff
            </p>
            @if(!auth()->user()->isSuperAdmin())
            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full font-medium"
                style="background:#fef3c7; color:#92400e;">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Mode Lihat Saja
            </span>
            @endif
        </div>

        {{-- Table --}}
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col" class="w-12 text-center">No</th>
                        <th scope="col">Nama Staff</th>
                        <th scope="col">Email</th>
                        <th scope="col" class="text-center">Role</th>
                        <th scope="col" class="text-center">Bergabung</th>
                        @if(auth()->user()->isSuperAdmin())
                        <th scope="col" class="text-center w-24">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr x-show="filtered.length === 0">
                        <td colspan="{{ auth()->user()->isSuperAdmin() ? 6 : 5 }}"
                            class="py-16 text-center text-slate-400 italic">
                            Tidak ada data ditemukan
                        </td>
                    </tr>
                    <template x-for="(s, idx) in filtered" :key="s.id">
                        <tr>
                            <td class="text-center">
                                <span class="text-sm font-medium text-slate-400" x-text="idx + 1"></span>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-sm"
                                        :style="s.role === 'superadmin'
                                            ? 'background:#fef3c7; color:#d97706;'
                                            : 'background:#dbeafe; color:#2563eb;'">
                                        <span class="text-xs font-bold" x-text="initials(s.name)"></span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800" x-text="s.name"></p>
                                        {{-- tandai jika ini akun sendiri --}}
                                        <span x-show="s.id === {{ auth()->id() }}"
                                            class="text-xs text-teal-600 font-medium">(Anda)</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-sm text-slate-600" x-text="s.email"></span>
                            </td>
                            <td class="text-center">
                                <span class="badge"
                                    :class="s.role === 'superadmin' ? 'badge-amber' : 'badge-blue'"
                                    x-text="s.role === 'superadmin' ? 'Super Admin' : 'Admin'">
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="text-sm text-slate-500"
                                    x-text="new Date(s.created_at).toLocaleDateString('id-ID', { day:'numeric', month:'short', year:'numeric' })">
                                </span>
                            </td>
                            @if(auth()->user()->isSuperAdmin())
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    {{-- Edit (semua bisa diedit kecuali diri sendiri) --}}
                                    <button type="button"
                                        class="btn btn-ghost btn-icon btn-sm"
                                        :disabled="s.id === {{ auth()->id() }}"
                                        :class="s.id === {{ auth()->id() }} ? 'opacity-30 cursor-not-allowed' : ''"
                                        @click="s.id !== {{ auth()->id() }} && openEdit(s)">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </button>
                                    {{-- Delete --}}
                                    <form :action="`/staff/${s.id}`" method="POST"
                                        x-show="s.id !== {{ auth()->id() }}"
                                        onsubmit="return confirm('Yakin ingin menghapus staff ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-icon btn-sm text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6"/>
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                                                <path d="M10 11v6"/><path d="M14 11v6"/>
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══════════ MODAL TAMBAH (superadmin only) ═══════════ --}}
    @if(auth()->user()->isSuperAdmin())
    <div class="modal-overlay" x-show="addModal" style="display:none;" @click.self="addModal = false">
        <div class="modal-box max-w-md w-full mx-4">
            <div class="modal-header">
                <h2 class="text-base font-bold text-slate-800">Tambah Staff Baru</h2>
                <button type="button" @click="addModal = false">&times;</button>
            </div>
            <form action="{{ route('staff.store') }}" method="POST">
                @csrf
                <div class="modal-body space-y-4">
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" x-model="form.name" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" x-model="form.email" required>
                    </div>
                    <div>
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" x-model="form.role" required>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control"
                            x-model="form.password" required minlength="8"
                            placeholder="Minimal 8 karakter">
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            x-model="form.password_confirmation" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="addModal = false">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════ MODAL EDIT (superadmin only) ═══════════ --}}
    <div class="modal-overlay" x-show="editModal" style="display:none;" @click.self="editModal = false">
        <div class="modal-box max-w-md w-full mx-4">
            <div class="modal-header">
                <h2 class="text-base font-bold text-slate-800">Edit Data Staff</h2>
                <button type="button" @click="editModal = false">&times;</button>
            </div>
            <form :action="`/staff/${form.id}`" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body space-y-4">
                    <div>
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" x-model="form.name" required>
                    </div>
                    <div>
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" x-model="form.email" required>
                    </div>
                    <div>
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" x-model="form.role" required>
                            <option value="admin">Admin</option>
                            <option value="superadmin">Super Admin</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Password Baru
                            <span class="text-slate-400 font-normal text-xs">(kosongkan jika tidak diubah)</span>
                        </label>
                        <input type="password" name="password" class="form-control"
                            x-model="form.password" minlength="8"
                            placeholder="Minimal 8 karakter">
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" class="form-control"
                            x-model="form.password_confirmation">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="editModal = false">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
    @endif

</div>
@endsection
