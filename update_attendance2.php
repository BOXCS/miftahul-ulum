<?php
$content = file_get_contents("resources/views/attendance/index.blade.php");

$modal = <<<HTML
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
        tanggalInput.value = document.querySelector('input[name=\"tanggal\"]').value || '{{ \$today }}';
        form.appendChild(tanggalInput);
        
        const waktuShalatInput = document.createElement('input');
        waktuShalatInput.type = 'hidden';
        waktuShalatInput.name = 'waktu_shalat';
        waktuShalatInput.value = document.querySelector('select[name=\"waktu_shalat\"]').value || '{{ \$waktu_shalat }}';
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
HTML;

$content = str_replace("</div>\n@endsection", $modal . "\n</div>\n@endsection", $content);
file_put_contents("resources/views/attendance/index.blade.php", $content);
echo "Done2\n";
