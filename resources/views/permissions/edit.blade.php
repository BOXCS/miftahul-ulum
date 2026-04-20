@extends('layouts.app')

@section('title', 'Edit Permintaan Izin')
@section('breadcrumb', 'Edit Permintaan Izin')

@section('content')
<div class="page-header">
    <h1 class="page-title">Edit Permintaan Izin Santri</h1>
</div>

<div class="card">
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="student_id" class="form-label">Santri</label>
            <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                <option value="">-- Pilih Santri --</option>
                @foreach($students as $student)
                    <option value="{{ $student['id'] }}" {{ old('student_id', $permission->student_id) == $student['id'] ? 'selected' : '' }}>
                        {{ $student['name'] }} ({{ $student['nis'] }}) - {{ $student['kelas'] }}
                    </option>
                @endforeach
            </select>
            @error('student_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="jenis" class="form-label">Jenis Izin</label>
            <select id="jenis" name="jenis" class="form-control @error('jenis') is-invalid @enderror" required>
                <option value="">-- Pilih Jenis --</option>
                @foreach($jenisOptions as $option)
                    <option value="{{ $option }}" {{ old('jenis', $permission->jenis) == $option ? 'selected' : '' }}>
                        {{ ucfirst($option) }}
                    </option>
                @endforeach
            </select>
            @error('jenis')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" 
                       class="form-control @error('tanggal_mulai') is-invalid @enderror" 
                       value="{{ old('tanggal_mulai', $permission->tanggal_mulai) }}" required>
                @error('tanggal_mulai')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group col-md-6">
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                <input type="date" id="tanggal_selesai" name="tanggal_selesai" 
                       class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                       value="{{ old('tanggal_selesai', $permission->tanggal_selesai) }}" required>
                @error('tanggal_selesai')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea id="keterangan" name="keterangan" rows="4" 
                      class="form-control @error('keterangan') is-invalid @enderror" 
                      placeholder="Jelaskan alasan permintaan izin ini...">{{ old('keterangan', $permission->keterangan) }}</textarea>
            @error('keterangan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Permintaan</button>
            <a href="{{ route('permissions.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
