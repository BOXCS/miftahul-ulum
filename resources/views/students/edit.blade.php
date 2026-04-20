@extends('layouts.app')

@section('title', 'Edit Data Santri')
@section('breadcrumb', 'Edit Santri')

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
            <h1 class="page-title animate-fade-in">Edit Data Santri</h1>
            <p class="page-subtitle animate-fade-in stagger-1">
                Perbarui informasi santri
                @isset($student)
                    <span class="font-semibold" style="color: #16a34a;">
                        {{ $student->nama ?? 'N/A' }}
                    </span>
                @endisset
            </p>
        </div>
        <div class="flex items-center gap-2 animate-fade-in stagger-2">
            @isset($student)
                <div class="flex items-center gap-3 card px-4 py-2.5">
                    <div class="avatar avatar-sm flex-shrink-0"
                         style="background: linear-gradient(135deg, #16a34a, #15803d);
                                color: #fff; font-size: 0.6rem; font-weight: 800;">
                        {{ strtoupper(substr($student->nama ?? 'S', 0, 1)) }}{{ strtoupper(substr(strstr($student->nama ?? '', ' '), 1, 1)) }}
                    </div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold leading-tight truncate" style="color: #1e293b;">
                            {{ $student->nama ?? 'Nama Santri' }}
                        </p>
                        <p class="text-xs leading-tight" style="color: #94a3b8;">
                            NIS: {{ $student->nis ?? '-' }} &middot; Kelas {{ $student->kelas ?? '-' }}
                        </p>
                    </div>
                </div>
            @endisset
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- SESSION ALERTS                                              --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
@if (session('success'))
    <div class="alert alert-success animate-fade-in mb-5 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 flex-shrink-0"
             viewBox="0 0 24 24" fill="none" stroke="currentColor"
             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <p class="text-sm font-medium">{{ session('success') }}</p>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger animate-fade-in mb-5">
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


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- MAIN FORM                                                   --}}
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

    <form action="{{ route('students.update', $student->id ?? $id ?? 1) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf
        @method('PUT')


        {{-- ── Section: Identitas Santri ───────────────────────────── --}}
        <div class="card animate-fade-in stagger-1">

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
                    <p class="text-xs" style="color: #94a3b8;">
                        Edit data identitas dasar santri
                    </p>
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

                        {{-- Preview --}}
                        <div class="relative flex-shrink-0">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden flex items-center justify-center"
                                 style="background: #f1f5f9; border: 2px dashed #cbd5e1;">
                                <template x-if="fotoPreview">
                                    <img :src="fotoPreview"
                                         alt="Preview foto santri"
                                         class="w-full h-full object-cover">
                                </template>
                                <template x-if="!fotoPreview">
                                    {{-- Existing photo or placeholder --}}
                                    @isset($student->foto)
                                        <img src="{{ asset('storage/' . $student->foto) }}"
                                             alt="Foto {{ $student->nama ?? 'santri' }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="flex flex-col items-center gap-1">
                                            <div class="avatar avatar-lg"
                                                 style="background: linear-gradient(135deg, #16a34a, #15803d);
                                                        color: #fff; font-weight: 800; font-size: 1.1rem;">
                                                {{ strtoupper(substr($student->nama ?? 'S', 0, 1)) }}
                                            </div>
                                        </div>
                                    @endisset
                                </template>
                            </div>
                            {{-- Remove new preview --}}
                            <button type="button"
                                    x-show="fotoPreview"
                                    @click="removeFoto()"
                                    class="absolute -top-2 -right-2 w-6 h-6 rounded-full flex items-center justify-center shadow-md"
                                    style="background: #ef4444; color: white;"
                                    aria-label="Hapus foto baru">
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
                                Ganti Foto
                            </label>
                            <input id="foto_input"
                                   type="file"
                                   name="foto"
                                   accept="image/jpeg,image/png,image/webp"
                                   class="sr-only"
                                   @change="handleFoto($event)">
                            <p class="text-xs mt-2 leading-relaxed" style="color: #94a3b8;">
                                Format: JPG, PNG, WebP. Maks. 2 MB.<br>
                                Biarkan kosong untuk mempertahankan foto saat ini.
                            </p>
                            @error('foto')
                                <p class="text-xs mt-1 font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- NIS (readonly on edit) + Nama --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="nis" class="form-label">NIS</label>
                        <div class="relative">
                            <input id="nis"
                                   type="text"
                                   name="nis"
                                   class="form-control font-mono opacity-60 cursor-not-allowed pr-10"
                                   value="{{ old('nis', $student->nis ?? '') }}"
                                   readonly
                                   aria-describedby="nis-hint">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                     style="color: #94a3b8;"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                     stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                </svg>
                            </div>
                        </div>
                        <p id="nis-hint" class="text-xs mt-1" style="color: #94a3b8;">
                            NIS tidak dapat diubah
                        </p>
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
                               value="{{ old('nama', $student->nama ?? '') }}"
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
                            <option value="" disabled>Pilih kelas</option>
                            @foreach (['7A', '7B', '8A', '8B', '9A', '9B'] as $kelas)
                                <option value="{{ $kelas }}"
                                        @selected(old('kelas', $student->kelas ?? '') === $kelas)>
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
                            <option value="" disabled>Pilih jenis kelamin</option>
                            <option value="Laki-laki"
                                    @selected(old('jenis_kelamin', $student->jenis_kelamin ?? '') === 'Laki-laki')>
                                Laki-laki
                            </option>
                            <option value="Perempuan"
                                    @selected(old('jenis_kelamin', $student->jenis_kelamin ?? '') === 'Perempuan')>
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
                               value="{{ old('tanggal_lahir', isset($student->tanggal_lahir) ? \Carbon\Carbon::parse($student->tanggal_lahir)->format('Y-m-d') : '') }}"
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
                               value="{{ old('tempat_lahir', $student->tempat_lahir ?? '') }}"
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
                    <p class="text-xs" style="color: #94a3b8;">Perbarui alamat dan informasi kontak</p>
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
                              required>{{ old('alamat', $student->alamat ?? '') }}</textarea>
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
                               value="{{ old('kota', $student->kota ?? '') }}"
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
                               value="{{ old('provinsi', $student->provinsi ?? '') }}"
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
                               value="{{ old('no_hp_wali', $student->no_hp_wali ?? '') }}"
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
                                    @selected(old('status', $student->status ?? 'Aktif') === 'Aktif')>
                                Aktif
                            </option>
                            <option value="Cuti"
                                    @selected(old('status', $student->status ?? '') === 'Cuti')>
                                Cuti
                            </option>
                            <option value="Keluar"
                                    @selected(old('status', $student->status ?? '') === 'Keluar')>
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
                               value="{{ old('tahun_masuk', $student->tahun_masuk ?? date('Y')) }}"
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
                              placeholder="Catatan khusus tentang santri…">{{ old('catatan', $student->catatan ?? '') }}</textarea>
                </div>

            </div>
        </div>{{-- end info tambahan card --}}


        {{-- ── Danger Zone: Hapus Santri ───────────────────────────── --}}
        <div class="card animate-fade-in stagger-4"
             style="border-color: #fecaca;">
            <div class="flex items-center gap-3 px-6 pt-5 pb-4 border-b"
                 style="border-color: #fecaca; background: #fff7f7; border-radius: 16px 16px 0 0;">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background: #fee2e2; color: #dc2626;">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="1.5"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-bold" style="color: #dc2626;">Zona Berbahaya</h2>
                    <p class="text-xs" style="color: #94a3b8;">
                        Tindakan berikut bersifat permanen dan tidak dapat dibatalkan
                    </p>
                </div>
            </div>

            <div class="px-6 py-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4
                            rounded-xl p-4"
                     style="background: #fff7f7; border: 1px solid #fecaca;">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold" style="color: #1e293b;">
                            Hapus Data Santri Ini
                        </p>
                        <p class="text-xs mt-0.5" style="color: #94a3b8;">
                            Menghapus santri akan menghapus seluruh riwayat absensi,
                            perizinan, dan data terkait secara permanen.
                        </p>
                    </div>
                    <button type="button"
                            class="btn btn-danger btn-sm flex-shrink-0"
                            x-data
                            @click="$dispatch('open-confirm-delete', {
                                id: '{{ $student->id ?? $id ?? 1 }}',
                                name: '{{ addslashes($student->nama ?? 'Santri ini') }}',
                                url: '{{ route('students.destroy', $student->id ?? $id ?? 1) }}'
                            })">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6"/>
                            <path d="M14 11v6"/>
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                        Hapus Santri
                    </button>
                </div>
            </div>
        </div>{{-- end danger zone --}}


        {{-- ── Action Buttons ──────────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 animate-fade-in stagger-5">
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
                <button type="button"
                        class="btn btn-ghost btn-sm"
                        onclick="window.location.reload()">
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
                    Simpan Perubahan
                </button>
            </div>
        </div>

    </form>
</div>{{-- end max-w-3xl --}}


{{-- ═══════════════════════════════════════════════════════════ --}}
{{-- CONFIRM DELETE MODAL                                         --}}
{{-- ═══════════════════════════════════════════════════════════ --}}
<div x-data="confirmDelete"
     @open-confirm-delete.window="prompt($event.detail.id, $event.detail.name, $event.detail.url)"
     @keydown.escape.window="cancel()">

    <div class="modal-overlay"
         x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="cancel()"
         style="display:none;"
         role="alertdialog"
         aria-modal="true"
         aria-labelledby="delete-modal-title">

        <div class="modal-box max-w-md w-full mx-4"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2">

            {{-- Header --}}
            <div class="modal-header">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background: #fee2e2; color: #dc2626;">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <div>
                        <h2 id="delete-modal-title"
                            class="text-base font-bold"
                            style="color: #1e293b;">Hapus Data Santri</h2>
                        <p class="text-xs" style="color: #94a3b8;">Tindakan ini tidak dapat dibatalkan</p>
                    </div>
                </div>
                <button type="button"
                        class="btn-icon flex-shrink-0"
                        style="color: #94a3b8;"
                        @click="cancel()"
                        aria-label="Tutup">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/>
                        <line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                <div class="rounded-xl p-4 mb-4"
                     style="background: #fff7f7; border: 1px solid #fecaca;">
                    <p class="text-sm" style="color: #7f1d1d;">
                        Anda akan menghapus data santri
                        <strong class="font-bold" x-text="targetName"></strong>.
                        Seluruh data terkait termasuk riwayat absensi dan perizinan akan
                        ikut terhapus secara permanen.
                    </p>
                </div>
                <p class="text-sm" style="color: #64748b;">
                    Ketik
                    <code class="px-1.5 py-0.5 rounded text-xs font-mono"
                          style="background: #f1f5f9; color: #ef4444;">HAPUS</code>
                    untuk mengkonfirmasi penghapusan.
                </p>
                <input type="text"
                       id="deleteConfirmInput"
                       class="form-control mt-3"
                       placeholder="Ketik HAPUS untuk konfirmasi"
                       x-ref="confirmInput"
                       autocomplete="off">
            </div>

            {{-- Footer --}}
            <div class="modal-footer">
                <div class="flex items-center gap-2 ml-auto">
                    <button type="button"
                            class="btn btn-secondary"
                            @click="cancel()">
                        Batal
                    </button>
                    <button type="button"
                            class="btn btn-danger"
                            @click="$refs.confirmInput.value === 'HAPUS' ? confirm() : $refs.confirmInput.focus()">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="1.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                            <path d="M10 11v6"/>
                            <path d="M14 11v6"/>
                            <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                        </svg>
                        Ya, Hapus Sekarang
                    </button>
                </div>
            </div>

        </div>{{-- end modal-box --}}
    </div>{{-- end modal-overlay --}}

</div>{{-- end confirmDelete x-data --}}

@endsection
