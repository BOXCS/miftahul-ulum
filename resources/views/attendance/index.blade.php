@extends('layouts.app')

@section('title', 'Presensi Santri')
@section('breadcrumb', 'Presensi')

@section('content')

<div x-data="{
    tanggal: '{{ $today }}',
    waktu_shalat: '{{ $waktu_shalat }}',
    students: @js($students),
    attendances: @js($attendances),
    stats: @js($stats),
    inputModal: false,
    form: { student_id: '', status: 'hadir', keterangan: '' },

    getAttendance(studentId) {
        return this.attendances[studentId] || { status: 'alpha', keterangan: '-', jam_masuk: '-' };
    },

    openInput(student = null) {
        if (student) {
            const att = this.getAttendance(student.id);
            this.form = { student_id: student.id, status: att.status || 'hadir', keterangan: att.keterangan || '' };
        } else {
            this.form = { student_id: '', status: 'hadir', keterangan: '' };
        }
        this.inputModal = true;
    }
}">

<div class="page-header animate-fade-in">
    <div>
        <h1 class="page-title">Presensi Santri</h1>
        <p class="page-subtitle">Pencatatan kehadiran santri per waktu shalat</p>
    </div>

    <div class="flex items-center gap-3">
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

{{-- STATS --}}
<div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="stat-card green"><p class="text-xs font-bold text-slate-400 uppercase">Hadir</p><p class="text-2xl font-bold">{{ $stats['hadir'] }}</p></div>
    <div class="stat-card blue"><p class="text-xs font-bold text-slate-400 uppercase">Terlambat</p><p class="text-2xl font-bold">{{ $stats['terlambat'] }}</p></div>
    <div class="stat-card amber"><p class="text-xs font-bold text-slate-400 uppercase">Izin</p><p class="text-2xl font-bold">{{ $stats['izin'] }}</p></div>
    <div class="stat-card amber"><p class="text-xs font-bold text-slate-400 uppercase">Sakit</p><p class="text-2xl font-bold">{{ $stats['sakit'] }}</p></div>
    <div class="stat-card red"><p class="text-xs font-bold text-slate-400 uppercase">Alpha</p><p class="text-2xl font-bold">{{ $stats['alpha'] }}</p></div>
</div>

{{-- TABLE --}}
<div class="card">
    <div class="table-wrapper">
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:50px">No</th>
                    <th>Santri</th>
                    <th>Kelas</th>
                    <th>Status</th>
                    <th>Jam Masuk</th>
                    <th>Keterangan</th>
                    <th style="width:100px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(student, index) in students" :key="student.id">
                    <tr>
                        <td><span class="text-slate-400 text-sm" x-text="index + 1"></span></td>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="avatar avatar-sm" style="background:#f1f5f9; color:#64748b;">
                                    <span class="text-xs font-bold" x-text="student.name.charAt(0)"></span>
                                </div>
                                <span class="font-semibold text-slate-800 text-sm" x-text="student.name"></span>
                            </div>
                        </td>
                        <td><span class="badge badge-blue" x-text="student.class"></span></td>
                        <td>
                            <span class="badge"
                                  :class="{
                                      'badge-green': getAttendance(student.id).status === 'hadir',
                                      'badge-blue': getAttendance(student.id).status === 'terlambat',
                                      'badge-amber': ['izin', 'sakit'].includes(getAttendance(student.id).status),
                                      'badge-red': getAttendance(student.id).status === 'alpha'
                                  }"
                                  x-text="getAttendance(student.id).status">
                            </span>
                        </td>
                        <td><span class="text-sm font-mono text-slate-500" x-text="getAttendance(student.id).jam_masuk ? new Date(getAttendance(student.id).jam_masuk).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : '-'"></span></td>
                        <td><span class="text-sm text-slate-500" x-text="getAttendance(student.id).keterangan || '-'"></span></td>
                        <td>
                            <button @click="openInput(student)" class="btn btn-sm btn-primary">Edit</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</div>

{{-- MODAL INPUT --}}
<div class="modal-overlay" x-show="inputModal" style="display:none;" @click.self="inputModal = false">
    <div class="modal-box max-w-md w-full mx-4">
        <div class="modal-header">
            <h3 class="font-semibold text-slate-800">Update Presensi</h3>
            <button @click="inputModal = false">&times;</button>
        </div>
        <form action="{{ route('attendance.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tanggal" :value="tanggal">
            <input type="hidden" name="waktu_shalat" :value="waktu_shalat">

            <div class="modal-body space-y-4">
                <input type="hidden" :name="'attendances[' + form.student_id + '][status]'" :value="form.status">

                <div>
                    <label class="form-label">Status Kehadiran</label>
                    <div class="grid grid-cols-2 gap-2">
                        <template x-for="opt in ['hadir', 'terlambat', 'izin', 'sakit', 'alpha']" :key="opt">
                            <label class="flex items-center gap-2 p-2 rounded border cursor-pointer" :class="form.status === opt ? 'bg-blue-50 border-blue-400' : 'border-slate-200'">
                                <input type="radio" x-model="form.status" :value="opt" class="sr-only">
                                <span class="text-xs font-bold uppercase" x-text="opt"></span>
                            </label>
                        </template>
                    </div>
                </div>

                <div>
                    <label class="form-label">Keterangan</label>
                    <textarea :name="'attendances[' + form.student_id + '][keterangan]'" class="form-control" x-model="form.keterangan" rows="2"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" @click="inputModal = false" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

</div>
@endsection
