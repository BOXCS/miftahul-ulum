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
</div>
</div>
@endsection
