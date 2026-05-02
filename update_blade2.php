<?php
$content = file_get_contents("resources/views/students/index.blade.php");

$modal = <<<HTML
    {{-- FINGERPRINT MODAL --}}
    <div x-data="{
        open: false,
        student: null,
        status: 'ready', // ready, scanning, success, failed
        quality: 0,
        template: '',
        errorMessage: '',
        openModal(payload) {
            this.student = payload.student;
            this.status = 'ready';
            this.quality = 0;
            this.template = '';
            this.errorMessage = '';
            this.open = true;
        },
        closeModal() {
            this.open = false;
        },
        async scanFingerprint() {
            this.status = 'scanning';
            this.errorMessage = '';
            this.quality = 0;
            
            try {
                // Simulasi pemanggilan API Web Fingerprint Scanner
                // Menggunakan Promise dengan setTimeout
                const result = await new Promise((resolve, reject) => {
                    setTimeout(() => {
                        // Simulasi success rate 80%
                        if (Math.random() > 0.2) {
                            resolve({
                                success: true,
                                quality: Math.floor(Math.random() * 20) + 80, // Quality 80-100
                                template: 'BASE64_TEMPLATE_' + Math.random().toString(36).substring(7)
                            });
                        } else {
                            reject(new Error('Kualitas scan buruk. Silakan coba lagi.'));
                        }
                    }, 2000);
                });
                
                this.quality = result.quality;
                this.template = result.template;
                this.status = 'success';
            } catch (error) {
                this.status = 'failed';
                this.errorMessage = error.message || 'Gagal terhubung dengan perangkat scanner.';
            }
        },
        async saveFingerprint() {
            if (!this.template || this.status !== 'success') return;
            
            try {
                const response = await fetch('/students/' + this.student.id + '/fingerprint', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        fingerprint_template: this.template,
                        fingerprint_quality: this.quality
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert('Data sidik jari berhasil disimpan!');
                    this.closeModal();
                } else {
                    throw new Error(data.message || 'Gagal menyimpan data.');
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }
    }" @open-fingerprint-modal.window="openModal(\$event.detail)">

        <div class="modal-overlay" x-show="open" style="display:none;" @click.self="closeModal()">
            <div class="modal-box max-w-md w-full mx-4">
                <div class="modal-header">
                    <h2 class="text-base font-bold text-slate-800">Scan Sidik Jari</h2>
                    <button type="button" @click="closeModal()">&times;</button>
                </div>
                <div class="modal-body text-center py-6">
                    <h3 class="font-medium text-lg mb-2" x-text="student ? student.name : ''"></h3>
                    <p class="text-sm text-slate-500 mb-6" x-text="student ? 'NIS: ' + student.nis : ''"></p>
                    
                    <div class="flex justify-center mb-6">
                        <div class="w-32 h-32 rounded-full flex items-center justify-center border-4"
                            :class="{
                                'border-slate-200 text-slate-400': status === 'ready',
                                'border-blue-500 text-blue-500 animate-pulse': status === 'scanning',
                                'border-green-500 text-green-500': status === 'success',
                                'border-red-500 text-red-500': status === 'failed'
                            }">
                            
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2a10 10 0 0 0-10 10v2a10 10 0 0 0 10 10 10 10 0 0 0 10-10v-2a10 10 0 0 0-10-10z" />
                                <path d="M12 6a6 6 0 0 0-6 6v2a6 6 0 0 0 6 6 6 6 0 0 0 6-6v-2a6 6 0 0 0-6-6z" />
                                <path d="M12 10a2 2 0 0 0-2 2v2a2 2 0 0 0 2 2 2 2 0 0 0 2-2v-2a2 2 0 0 0-2-2z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="mb-4 h-6">
                        <p x-show="status === 'ready'" class="text-slate-600">Tekan tombol di bawah untuk memulai scan</p>
                        <p x-show="status === 'scanning'" class="text-blue-600 font-medium">Sedang memindai... Letakkan jari pada scanner.</p>
                        <p x-show="status === 'success'" class="text-green-600 font-medium">
                            Scan berhasil! Kualitas: <span x-text="quality + '%'"></span>
                        </p>
                        <p x-show="status === 'failed'" class="text-red-600 font-medium" x-text="errorMessage"></p>
                    </div>
                    
                    <div x-show="status === 'success'" class="w-full bg-slate-200 rounded-full h-2.5 mb-6">
                        <div class="bg-green-600 h-2.5 rounded-full" :style="'width: ' + quality + '%'"></div>
                    </div>

                    <div class="flex justify-center gap-3">
                        <button type="button" class="btn" 
                            :class="status === 'scanning' ? 'btn-secondary opacity-50 cursor-not-allowed' : 'btn-primary'" 
                            @click="scanFingerprint()"
                            :disabled="status === 'scanning'">
                            <span x-text="status === 'success' || status === 'failed' ? 'Re-scan Fingerprint' : 'Scan Fingerprint'"></span>
                        </button>
                    </div>
                </div>
                <div class="modal-footer flex justify-between">
                    <button type="button" class="btn btn-secondary" @click="closeModal()">Batal</button>
                    <button type="button" class="btn btn-primary" 
                        @click="saveFingerprint()"
                        :disabled="status !== 'success'"
                        :class="{'opacity-50 cursor-not-allowed': status !== 'success'}">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>
HTML;

$content = str_replace("@endsection", $modal . "\n\n@endsection", $content);
file_put_contents("resources/views/students/index.blade.php", $content);
echo "Done\n";
