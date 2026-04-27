@extends('layouts.app')

@section('title', 'Data Santri')
@section('breadcrumb', 'Data Santri')

@section('content')

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- PAGE HEADER                                                  --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="page-header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="page-title animate-fade-in">Data Santri</h1>
                <p class="page-subtitle animate-fade-in stagger-1">
                    Kelola data santri pesantren Miftahul Ulum
                </p>
            </div>
            <div class="flex items-center gap-2 animate-fade-in stagger-2">
                <button type="button" class="btn btn-primary" x-data
                    @click="
            console.log('Button clicked');
            const payload = { mode: 'create' };
            console.log('Dispatching event: open-student-modal', payload);
            $dispatch('open-student-modal', payload);
        ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>
                    Tambah Santri
                </button>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- MINI STATS BAR                                              --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5 animate-fade-in stagger-1">

        <div class="card px-5 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: #dcfce7; color: #16a34a;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold leading-none" style="color: #1e293b;">{{ $stats['total'] }}</p>
                <p class="text-xs font-medium mt-0.5" style="color: #94a3b8;">Total Santri</p>
            </div>
        </div>

        <div class="card px-5 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: #dbeafe; color: #2563eb;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold leading-none" style="color: #1e293b;">{{ $stats['laki_laki'] }}</p>
                <p class="text-xs font-medium mt-0.5" style="color: #94a3b8;">Laki-laki</p>
            </div>
        </div>

        <div class="card px-5 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: #fce7f3; color: #db2777;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold leading-none" style="color: #1e293b;">{{ $stats['perempuan'] }}</p>
                <p class="text-xs font-medium mt-0.5" style="color: #94a3b8;">Perempuan</p>
            </div>
        </div>

        <div class="card px-5 py-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background: #ccfbf1; color: #0d9488;">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                    <polyline points="22 4 12 14.01 9 11.01" />
                </svg>
            </div>
            <div>
                <p class="text-xl font-bold leading-none" style="color: #1e293b;">{{ $stats['aktif'] }}</p>
                <p class="text-xs font-medium mt-0.5" style="color: #94a3b8;">Aktif</p>
            </div>
        </div>

    </div>


    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- MAIN TABLE CARD                                             --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="card animate-fade-in stagger-2" x-data="{
        search: '',
        kelasFilter: '',
        statusFilter: '',
        students: @js($students),
        get filtered() {
            return this.students.filter(s => {
                const matchSearch = this.search === '' ||
                    s.name.toLowerCase().includes(this.search.toLowerCase()) ||
                    (s.nis && s.nis.includes(this.search)) ||
                    (s.class && s.class.toLowerCase().includes(this.search.toLowerCase()));
                const matchKelas = this.kelasFilter === '' || s.class === this.kelasFilter;
                const matchStatus = this.statusFilter === '' || s.status === this.statusFilter;
                return matchSearch && matchKelas && matchStatus;
            });
        },
        initials(name) {
            return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
        }
    }">

        {{-- Filter Row --}}
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 px-5 py-4 border-b border-slate-100">
            <div class="relative flex-1 min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color: #94a3b8;"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="search" class="form-control pl-9 pr-4" placeholder="Cari nama, NIS, kelas…"
                    x-model="search">
            </div>
            <select class="form-select sm:w-36" x-model="kelasFilter">
                <option value="">Semua Kelas</option>
                @foreach ($kelas_options as $opt)
                    <option value="{{ $opt }}">Kelas {{ $opt }}</option>
                @endforeach
            </select>
            <select class="form-select sm:w-36" x-model="statusFilter">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="alumni">Alumni</option>
                <option value="keluar">Keluar</option>
            </select>
        </div>

        {{-- Result Summary --}}
        <div class="flex items-center justify-between px-5 py-2.5 border-b border-slate-50" style="background: #f8fafc;">
            <p class="text-xs" style="color: #64748b;">
                Menampilkan <span class="font-semibold" style="color: #1e293b;" x-text="filtered.length"></span> dari
                <span class="font-semibold" style="color: #1e293b;" x-text="students.length"></span> santri
            </p>
        </div>

        {{-- Data Table --}}
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th scope="col" class="w-12 text-center">No</th>
                        <th scope="col">NIS</th>
                        <th scope="col">Nama Lengkap</th>
                        <th scope="col" class="text-center">Kelas</th>
                        <th scope="col" class="text-center">Jenis Kelamin</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr x-show="filtered.length === 0">
                        <td colspan="7" class="py-16 text-center text-slate-400 italic">Tidak ada data ditemukan</td>
                    </tr>
                    <template x-for="(student, idx) in filtered" :key="student.id">
                        <tr>
                            <td class="text-center"><span class="text-sm font-medium text-slate-400"
                                    x-text="idx + 1"></span></td>
                            <td><span class="text-sm font-mono text-slate-500" x-text="student.nis || '-'"></span></td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="avatar avatar-sm"
                                        :style="student.gender === 'Perempuan' ? 'background:#fce7f3; color:#db2777;' :
                                            'background:#dbeafe; color:#2563eb;'">
                                        <span class="text-xs font-bold" x-text="initials(student.name)"></span>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-800" x-text="student.name"></p>
                                </div>
                            </td>
                            <td class="text-center"><span class="badge badge-blue" x-text="student.class"></span></td>
                            <td class="text-center">
                                <span class="badge"
                                    :class="student.gender === 'Perempuan' ? 'badge-purple' : 'badge-blue'"
                                    x-text="student.gender"></span>
                            </td>
                            <td class="text-center">
                                <span class="badge"
                                    :class="student.status === 'aktif' ? 'badge-green' : (student.status === 'keluar' ?
                                        'badge-red' : 'badge-amber')"
                                    x-text="student.status"></span>
                            </td>
                            <td class="text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button type="button" class="btn btn-ghost btn-icon btn-sm"
                                        @click="$dispatch('open-student-modal', { mode: 'edit', student: student })">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                            fill="none" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                                        </svg>
                                    </button>
                                    <form :action="`/students/${student.id}`" method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-icon btn-sm text-red-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" />
                                                <path d="M10 11v6" />
                                                <path d="M14 11v6" />
                                                <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" />
                                            </svg>
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


    {{-- TAMBAH / EDIT MODAL --}}
    <div x-data="{
        open: false,
        mode: 'create',
        form: { id: '', name: '', nis: '', parent_id: '', class: '', gender: '', status: 'aktif', tanggal_lahir: '', tahun_angkatan: '{{ date('Y') }}', address: '' },
        openModal(payload) {
            this.mode = payload.mode || 'create';
            if (payload.student) {
                this.form = { ...payload.student, tanggal_lahir: payload.student.tanggal_lahir ? payload.student.tanggal_lahir.split('T')[0] : '' };
            } else {
                this.form = { id: '', name: '', nis: '', parent_id: '', class: '', gender: '', status: 'aktif', tanggal_lahir: '', tahun_angkatan: '{{ date('Y') }}', address: '' };
            }
            this.open = true;
        },
        closeModal() { this.open = false; }
    }" @open-student-modal.window="openModal($event.detail)">

        <div class="modal-overlay" x-show="open" style="display:none;" @click.self="closeModal()">
            <div class="modal-box max-w-2xl w-full mx-4">
                <div class="modal-header">
                    <h2 class="text-base font-bold text-slate-800"
                        x-text="mode === 'create' ? 'Tambah Santri Baru' : 'Edit Data Santri'"></h2>
                    <button type="button" @click="closeModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form :action="mode === 'create' ? '{{ route('students.store') }}' : `/students/${form.id}`"
                        method="POST">
                        @csrf
                        <template x-if="mode === 'edit'">
                            <input type="hidden" name="_method" value="PUT">
                        </template>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div class="col-span-2">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" x-model="form.name" required>
                            </div>
                            <div>
                                <label class="form-label">NIS</label>
                                <input type="text" name="nis" class="form-control" x-model="form.nis" required>
                            </div>
                            <div>
                                <label class="form-label">Wali Santri</label>
                                <select name="parent_id" class="form-select" x-model="form.parent_id" required>
                                    <option value="">Pilih Wali</option>
                                    @foreach (\App\Models\ParentModel::all() as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Kelas</label>
                                <select name="class" class="form-select" x-model="form.class" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas_options as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Jenis Kelamin</label>
                                <select name="gender" class="form-select" x-model="form.gender" required>
                                    <option value="">Pilih</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control"
                                    x-model="form.tanggal_lahir" required>
                            </div>
                            <div>
                                <label class="form-label">Tahun Angkatan</label>
                                <input type="text" name="tahun_angkatan" class="form-control"
                                    x-model="form.tahun_angkatan" required>
                            </div>
                            <div class="col-span-2">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select" x-model="form.status" required>
                                    <option value="aktif">Aktif</option>
                                    <option value="alumni">Alumni</option>
                                    <option value="keluar">Keluar</option>
                                </select>
                            </div>
                            <div class="col-span-2">
                                <label class="form-label">Alamat</label>
                                <textarea name="address" class="form-control" rows="3" x-model="form.address" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="closeModal()">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
