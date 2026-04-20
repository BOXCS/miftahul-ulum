@extends('layouts.app')

@section('title', 'Buat Permintaan Izin')
@section('breadcrumb', 'Buat Permintaan Izin')

@section('content')
<div class="page-header">
    <h1 class="page-title">Buat Permintaan Izin Santri</h1>
</div>

<div class="card p-6">
    <form action="{{ route('permissions.store') }}" method="POST">
        @csrf

        <div class="mb-4">
            <label for="student_id" class="form-label">Santri</label>
            <select id="student_id" name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                <option value="">-- Pilih Santri --</option>
                @foreach($students as $student)
                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                        {{ $student->name }} ({{ $student->nis }}) - {{ $student->class }}
                    </option>
                @endforeach
            </select>
            @error('student_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-4">
            <label for="jenis" class="form-label">Jenis Izin</label>
            <select id="jenis" name="jenis" class="form-select @error('jenis') is-invalid @enderror" required>
                <option value="">-- Pilih Jenis --</option>
                <option value="keluar" {{ old('jenis') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                <option value="pulang" {{ old('jenis') == 'pulang' ? 'selected' : '' }}>Pulang</option>
                <option value="kegiatan" {{ old('jenis') == 'kegiatan' ? 'selected' : '' }}>Kegiatan</option>
                <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
            @error('jenis')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai"
                       class="form-control @error('tanggal_mulai') is-invalid @enderror"
                       value="{{ old('tanggal_mulai') }}" required>
                @error('tanggal_mulai')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai"
                       class="form-control @error('tanggal_selesai') is-invalid @enderror"
                       value="{{ old('tanggal_selesai') }}" required>
                @error('tanggal_selesai')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-4">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="4"
                      class="form-control @error('keterangan') is-invalid @enderror"
                      placeholder="Jelaskan alasan permintaan izin ini...">{{ old('keterangan') }}</textarea>
            @error('keterangan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="flex items-center gap-2">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
