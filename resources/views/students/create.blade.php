@extends('layouts.app')

@section('title', 'Tambah Santri Baru')
@section('breadcrumb', 'Tambah Santri')

@section('content')

{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- PAGE HEADER                                                  --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="page-header">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <a href="{{ route('students.index') }}"
                   class="flex items-center gap-1.5 text-xs font-medium transition-colors hover:underline"
                   style="color: #94a3b8;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                    Kembali ke Data Santri
                </a>
            </div>
            <h1 class="page-title animate-fade-in">Tambah Santri Baru</h1>
            <p class="page-subtitle animate-fade-in stagger-1">
                Isi formulir berikut untuk mendaftarkan santri baru
            </p>
        </div>
        <div class="flex items-center gap-2 animate-fade-in stagger-2">
            <span class="badge badge-green flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold">
                <span class="inline-block w-1.5 h-1.5 rounded-full bg-green-500"></span>
                Formulir Pendaftaran
            </span>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- MAIN FORM CARD                                              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div class="max-w-3xl"
     x-data="{
        fotoPreview: null,
        handleFoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => { this.fotoPreview = e.target.result; };
            reader.readAsDataURL(file);
        },
        removeFoto() {
            this.fotoPreview = null;
            document.getElementById('foto_input').value = '';
        }
     }">

    <form action="{{ route('students.store') }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-5"
          x-ref="form">
        @csrf

        {{-- ── Section: Foto & Identitas Dasar ────────────────────── --}}
        <div class="card animate-fade-in stagger-1">

            {{-- Card Header --}}
            <div class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: #dcfce7; color: #16a34a;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold" style="color: #1e293b;">Identitas Santri</h2>
                    <p class="text-xs" style="color: #94a3b8;">Data identitas dasar santri</p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-5">

                {{-- Foto Upload --}}
                <div>
                    <label class="form-label">
                        Foto Santri
                        <span class="text-xs font-normal" style="color: #94a3b8;">(Opsional)</span>
                    </label>
                    <div class="flex items-start gap-5">

                        {{-- Preview Circle --}}
                        <div class="relative flex-shrink-0">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden flex items-center justify-center"
                                 style="background: #f1f5f9; border: 2px dashed #cbd5e1;">
                                <template x-if="fotoPreview">
                                    <img :src="fotoPreview"
                                         alt="Preview foto santri"
                                         class="w-full h-full object-cover">
                                </template>
                                <template x-if="!fotoPreview">
                                    <div class="flex flex-col items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8"
                                             style="color: #cbd5e1;"
                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                            <circle cx="8.5" cy="8.5" r="1.5"/>
                                            <polyline points="21 15 16 10 5 21"/>
                                        </svg>
                                        <span class="text-xs" style="color: #94a3b8;">Foto</span>
                                    </div>
                                </template>
                            </div>
                            {{-- Remove button --}}
                            <button type="button"
                                    x-show="fotoPreview"
                                    @click="removeFoto()"
                                    class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center shadow-md transition-colors"
                                    style="background: #ef4444; color: white;"
                                    aria-label="Hapus foto">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24"
                                     fill="none" stroke="currentColor" stroke-width="2.5"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Upload Controls --}}
                        <div class="flex-1 min-w-0 pt-1">
                            <label for="foto_input"
                                   class="btn btn-secondary btn-sm cursor-pointer inline-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                    <polyline points="17 8 12 3 7 8"/>
                                    <line x1="12" y1="3" x2="12" y2="15"/>
                                </svg>
                                Pilih Foto
                            </label>
                            <input id="foto_input"
                                   type="file"
                                   name="foto"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="sr-only"
                                   @change="handleFoto($event)">
                            <p class="text-xs mt-2 leading-relaxed" style="color: #94a3b8;">
                                Format yang didukung: JPG, PNG, WebP<br>
                                Ukuran maksimal: 2 MB
                            </p>
                            @error('foto')
                                <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- NIS + Nama --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nis" class="form-label">
                            NIS <span class="text-red-500">*</span>
                        </label>
                        <input id="nis"
                               type="text"
                               name="nis"
                               class="form-control font-mono @error('nis') error @enderror"
                               placeholder="Contoh: 2024001"
                               value="{{ old('nis') }}"
                               required
                               autocomplete="off">
                        @error('nis')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="nama" class="form-label">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input id="nama"
                               type="text"
                               name="nama"
                               class="form-control @error('nama') error @enderror"
                               placeholder="Nama lengkap sesuai akta"
                               value="{{ old('nama') }}"
                               required
                               autocomplete="off">
                        @error('nama')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Kelas + Jenis Kelamin --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="kelas" class="form-label">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select id="kelas"
                                name="kelas"
                                class="form-select @error('kelas') error @enderror"
                                required>
                            <option value="" disabled {{ old('kelas') ? '' : 'selected' }}>
                                Pilih kelas
                            </option>
                            @foreach (['7A', '7B', '8A', '8B', '9A', '9B'] as $kelas)
                                <option value="{{ $kelas }}"
                                        {{ old('kelas') === $kelas ? 'selected' : '' }}>
                                    Kelas {{ $kelas }}
                                </option>
                            @endforeach
                        </select>
                        @error('kelas')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="jenis_kelamin" class="form-label">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select id="jenis_kelamin"
                                name="jenis_kelamin"
                                class="form-select @error('jenis_kelamin') error @enderror"
                                required>
                            <option value="" disabled {{ old('jenis_kelamin') ? '' : 'selected' }}>
                                Pilih jenis kelamin
                            </option>
                            <option value="Laki-laki"
                                    {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>
                                Laki-laki
                            </option>
                            <option value="Perempuan"
                                    {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>
                                Perempuan
                            </option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Tanggal Lahir + Tempat Lahir --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tanggal_lahir" class="form-label">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input id="tanggal_lahir"
                               type="date"
                               name="tanggal_lahir"
                               class="form-control @error('tanggal_lahir') error @enderror"
                               value="{{ old('tanggal_lahir') }}"
                               required>
                        @error('tanggal_lahir')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="tempat_lahir" class="form-label">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input id="tempat_lahir"
                               type="text"
                               name="tempat_lahir"
                               class="form-control @error('tempat_lahir') error @enderror"
                               placeholder="Kota tempat lahir"
                               value="{{ old('tempat_lahir') }}"
                               required
                               autocomplete="off">
                        @error('tempat_lahir')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

            </div>
        </div>{{-- end identitas card --}}


        {{-- ── Section: Alamat & Kontak ────────────────────────────── --}}
        <div class="card animate-fade-in stagger-2">

            <div class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: #dbeafe; color: #2563eb;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold" style="color: #1e293b;">Alamat &amp; Kontak</h2>
                    <p class="text-xs" style="color: #94a3b8;">Alamat tempat tinggal dan informasi kontak</p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4">

                {{-- Alamat --}}
                <div>
                    <label for="alamat" class="form-label">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea id="alamat"
                              name="alamat"
                              class="form-control @error('alamat') error @enderror"
                              rows="3"
                              placeholder="Jl. Contoh No. 1, RT 01/RW 01, Desa/Kel., Kecamatan…"
                              required>{{ old('alamat') }}</textarea>
                    @error('alamat')
                        <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kota + Provinsi --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="kota" class="form-label">Kota / Kabupaten</label>
                        <input id="kota"
                               type="text"
                               name="kota"
                               class="form-control @error('kota') error @enderror"
                               placeholder="Nama kota/kabupaten"
                               value="{{ old('kota') }}"
                               autocomplete="off">
                        @error('kota')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="provinsi" class="form-label">Provinsi</label>
                        <input id="provinsi"
                               type="text"
                               name="provinsi"
                               class="form-control @error('provinsi') error @enderror"
                               placeholder="Nama provinsi"
                               value="{{ old('provinsi') }}"
                               autocomplete="off">
                        @error('provinsi')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- No. HP Wali --}}
                <div>
                    <label for="no_hp_wali" class="form-label">
                        No. HP Wali
                        <span class="text-xs font-normal" style="color: #94a3b8;">(Opsional)</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <span class="text-sm font-medium" style="color: #94a3b8;">+62</span>
                        </div>
                        <input id="no_hp_wali"
                               type="tel"
                               name="no_hp_wali"
                               class="form-control pl-12 @error('no_hp_wali') error @enderror"
                               placeholder="812 3456 7890"
                               value="{{ old('no_hp_wali') }}"
                               autocomplete="off">
                    </div>
                    @error('no_hp_wali')
                        <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                    @enderror
                </div>

            </div>
        </div>{{-- end alamat card --}}


        {{-- ── Section: Informasi Tambahan ─────────────────────────── --}}
        <div class="card animate-fade-in stagger-3">

            <div class="flex items-center gap-3 px-6 pt-5 pb-4 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: #ede9fe; color: #7c3aed;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold" style="color: #1e293b;">Informasi Tambahan</h2>
                    <p class="text-xs" style="color: #94a3b8;">Status dan catatan santri</p>
                </div>
            </div>

            <div class="px-6 py-5 space-y-4">

                {{-- Status + Tahun Masuk --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="status" class="form-label">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select id="status"
                                name="status"
                                class="form-select @error('status') error @enderror"
                                required>
                            <option value="Aktif"
                                    {{ old('status', 'Aktif') === 'Aktif' ? 'selected' : '' }}>
                                Aktif
                            </option>
                            <option value="Cuti"
                                    {{ old('status') === 'Cuti' ? 'selected' : '' }}>
                                Cuti
                            </option>
                            <option value="Keluar"
                                    {{ old('status') === 'Keluar' ? 'selected' : '' }}>
                                Keluar
                            </option>
                        </select>
                        @error('status')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="tahun_masuk" class="form-label">Tahun Masuk</label>
                        <input id="tahun_masuk"
                               type="number"
                               name="tahun_masuk"
                               class="form-control @error('tahun_masuk') error @enderror"
                               placeholder="{{ date('Y') }}"
                               value="{{ old('tahun_masuk', date('Y')) }}"
                               min="2000"
                               max="{{ date('Y') }}">
                        @error('tahun_masuk')
                            <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Catatan --}}
                <div>
                    <label for="catatan" class="form-label">
                        Catatan
                        <span class="text-xs font-normal" style="color: #94a3b8;">(Opsional)</span>
                    </label>
                    <textarea id="catatan"
                              name="catatan"
                              class="form-control"
                              rows="3"
                              placeholder="Catatan khusus tentang santri…">{{ old('catatan') }}</textarea>
                </div>

            </div>
        </div>{{-- end info tambahan card --}}


        {{-- ── Session Errors Alert ────────────────────────────────── --}}
        @if ($errors->any())
            <div class="alert alert-danger animate-fade-in">
                <div class="flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0 mt-0.5"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor"
                         stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <div>
                        <p class="text-sm font-semibold mb-1">Terdapat kesalahan pada formulir:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif


        {{-- ── Action Buttons ──────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 animate-fade-in stagger-4">
            <a href="{{ route('students.index') }}"
               class="btn btn-secondary w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                     fill="none" stroke="currentColor" stroke-width="1.5"
                     stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"/>
                    <polyline points="12 19 5 12 12 5"/>
                </svg>
                Batal
            </a>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="reset"
                        class="btn btn-ghost btn-sm"
                        @click="fotoPreview = null">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="1 4 1 10 7 10"/>
                        <path d="M3.51 15a9 9 0 1 0 .49-3.72"/>
                    </svg>
                    Reset
                </button>
                <button type="submit"
                        class="btn btn-primary flex-1 sm:flex-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                        <polyline points="17 21 17 13 7 13 7 21"/>
                        <polyline points="7 3 7 8 15 8"/>
                    </svg>
                    Simpan Santri
                </button>
            </div>
        </div>

    </form>
</div>{{-- end max-w-3xl --}}

@endsection
