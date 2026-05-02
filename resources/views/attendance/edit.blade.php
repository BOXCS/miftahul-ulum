{{--
    File: resources/views/attendance/edit.blade.php
    Di-include dari attendance/index.blade.php
    Menerima event Alpine: 'open-edit-modal' { studentId, studentName, waktu, att }
--}}

<div
    x-data="{
        open: false,
        tanggal: '{{ $today }}',
        form: { student_id: '', student_name: '', waktu: '', status: 'hadir', keterangan: '' },

        init() {
            window.addEventListener('open-edit-modal', (e) => {
                const d = e.detail;
                this.form = {
                    student_id:   d.studentId,
                    student_name: d.studentName,
                    waktu:        d.waktu,
                    status:       d.att.status || 'hadir',
                    keterangan:   d.att.keterangan || ''
                };
                this.open = true;
                document.body.style.overflow = 'hidden';
            });
        },
        close() {
            this.open = false;
            document.body.style.overflow = '';
        },
        statusLabel(s) {
            const m = { hadir:'Hadir', terlambat:'Terlambat', izin:'Izin', sakit:'Sakit', alpha:'Alpha' };
            return m[s] || s;
        },
        avatarStyle(s) {
            const m = {
                hadir:     'background:linear-gradient(135deg,#16a34a,#4ade80)',
                terlambat: 'background:linear-gradient(135deg,#2563eb,#60a5fa)',
                izin:      'background:linear-gradient(135deg,#d97706,#fbbf24)',
                sakit:     'background:linear-gradient(135deg,#db2777,#f472b6)',
                alpha:     'background:linear-gradient(135deg,#dc2626,#f87171)'
            };
            return m[s] || 'background:linear-gradient(135deg,#64748b,#94a3b8)';
        }
    }"
    @keydown.escape.window="open && close()">
    {{-- Overlay --}}
    <div
        style="position:fixed;inset:0;background:rgba(15,23,42,.5);backdrop-filter:blur(5px);z-index:9900;display:flex;align-items:center;justify-content:center;padding:20px;transition:opacity .25s ease;"
        :style="open ? 'opacity:1;pointer-events:all;' : 'opacity:0;pointer-events:none;'"
        @click.self="close()">
        <div
            style="background:#fff;border-radius:22px;width:100%;max-width:480px;box-shadow:0 24px 64px rgba(15,23,42,.18);overflow:hidden;max-height:90vh;display:flex;flex-direction:column;transition:transform .28s cubic-bezier(.22,.68,0,1.2);"
            :style="open ? 'transform:translateY(0) scale(1);' : 'transform:translateY(22px) scale(.96);'">
            {{-- Header --}}
            <div style="display:flex;align-items:center;justify-content:space-between;padding:18px 24px 14px;border-bottom:1px solid #f1f5f9;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:12px;">
                    <div style="width:36px;height:36px;border-radius:11px;background:#dcfce7;color:#15803d;display:flex;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-size:1rem;font-weight:800;color:#0f172a;">Edit Presensi</div>
                        <div style="font-size:.72rem;color:#94a3b8;" x-text="form.student_name + ' · ' + form.waktu + ' · {{ \Carbon\Carbon::parse($today)->format('d/m/Y') }}'"></div>
                    </div>
                </div>
                <button @click="close()"
                    style="width:30px;height:30px;border-radius:8px;background:#f1f5f9;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#64748b;transition:all .18s;"
                    onmouseover="this.style.background='#e2e8f0';this.style.color='#0f172a'"
                    onmouseout="this.style.background='#f1f5f9';this.style.color='#64748b'">✕</button>
            </div>

            {{-- Body --}}
            <div style="overflow-y:auto;flex:1;padding:20px 24px;">
                <form action="{{ route('attendance.store') }}" method="POST" id="editAttForm">
                    @csrf
                    <input type="hidden" name="tanggal" :value="tanggal">
                    <input type="hidden" name="waktu_shalat" :value="form.waktu">

                    {{-- Student info --}}
                    <div style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#f8fafc;border-radius:12px;border:1px solid #e2e8f0;margin-bottom:20px;">
                        <div style="width:40px;height:40px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;color:#fff;flex-shrink:0;"
                            :style="avatarStyle(form.status)">
                            <span x-text="form.student_name ? form.student_name.charAt(0).toUpperCase() : ''"></span>
                        </div>
                        <div>
                            <div style="font-size:.88rem;font-weight:700;color:#0f172a;" x-text="form.student_name"></div>
                            <div style="font-size:.72rem;color:#64748b;" x-text="form.waktu + ' · {{ \Carbon\Carbon::parse($today)->isoFormat('D MMMM Y') }}'"></div>
                        </div>
                    </div>

                    {{-- Status Grid --}}
                    <div style="margin-bottom:18px;">
                        <label style="display:block;font-size:.72rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:10px;">
                            Status Kehadiran <span style="color:#e11d48;">*</span>
                        </label>
                        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                            <template x-for="opt in ['hadir','terlambat','izin','sakit','alpha']" :key="opt">
                                <label
                                    style="display:flex;flex-direction:column;align-items:center;gap:5px;padding:10px 8px;border-radius:12px;border:2px solid;cursor:pointer;transition:all .18s;font-size:.72rem;font-weight:700;"
                                    :style="form.status === opt
                                        ? (opt==='hadir'     ? 'border-color:#16a34a;background:#f0fdf4;color:#15803d;'
                                        : opt==='terlambat'  ? 'border-color:#2563eb;background:#eff6ff;color:#1d4ed8;'
                                        : opt==='izin'       ? 'border-color:#d97706;background:#fffbeb;color:#b45309;'
                                        : opt==='sakit'      ? 'border-color:#db2777;background:#fdf2f8;color:#be185d;'
                                        :                      'border-color:#dc2626;background:#fff5f5;color:#dc2626;')
                                        : 'border-color:#e2e8f0;background:#f8fafc;color:#64748b;'">
                                    <input type="radio"
                                        :name="'attendances[' + form.student_id + '][status]'"
                                        :value="opt"
                                        x-model="form.status"
                                        style="display:none;">

                                    <template x-if="opt === 'hadir'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                            <polyline points="22 4 12 14.01 9 11.01" />
                                        </svg>
                                    </template>
                                    <template x-if="opt === 'terlambat'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <polyline points="12 6 12 12 16 14" />
                                        </svg>
                                    </template>
                                    <template x-if="opt === 'izin'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                        </svg>
                                    </template>
                                    <template x-if="opt === 'sakit'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z" />
                                        </svg>
                                    </template>
                                    <template x-if="opt === 'alpha'">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="15" y1="9" x2="9" y2="15" />
                                            <line x1="9" y1="9" x2="15" y2="15" />
                                        </svg>
                                    </template>
                                    <span style="font-size:.7rem;text-transform:capitalize;" x-text="opt"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    {{-- Keterangan --}}
                    <div>
                        <label style="display:block;font-size:.72rem;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:.08em;margin-bottom:7px;">
                            Keterangan <span style="font-size:.68rem;font-weight:500;text-transform:none;color:#94a3b8;">(opsional)</span>
                        </label>
                        <textarea
                            :name="'attendances[' + form.student_id + '][keterangan]'"
                            rows="2"
                            x-model="form.keterangan"
                            style="width:100%;padding:10px 14px;border-radius:11px;border:1.5px solid #e2e8f0;font-size:.83rem;font-family:inherit;color:#1e293b;background:#f8fafc;outline:none;resize:vertical;box-sizing:border-box;transition:border .2s;"
                            placeholder="Tambahkan keterangan jika perlu…"
                            onfocus="this.style.borderColor='#16a34a';this.style.boxShadow='0 0 0 3px rgba(22,163,74,.1)'"
                            onblur="this.style.borderColor='#e2e8f0';this.style.boxShadow='none'"></textarea>
                    </div>
                </form>
            </div>

            {{-- Footer --}}
            <div style="padding:14px 24px;border-top:1px solid #f1f5f9;display:flex;justify-content:flex-end;gap:10px;flex-shrink:0;background:#f8fafc;">
                <button type="button" @click="close()"
                    style="padding:9px 18px;border-radius:10px;border:1.5px solid #e2e8f0;background:#fff;font-size:.82rem;font-weight:700;color:#475569;cursor:pointer;font-family:inherit;">
                    Batal
                </button>
                <button type="submit" form="editAttForm"
                    style="padding:9px 22px;border-radius:10px;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;font-size:.82rem;font-weight:700;border:none;cursor:pointer;font-family:inherit;display:flex;align-items:center;gap:6px;box-shadow:0 4px 12px rgba(22,163,74,.25);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z" />
                        <polyline points="17 21 17 13 7 13 7 21" />
                        <polyline points="7 3 7 8 15 8" />
                    </svg>
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>