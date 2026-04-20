@extends('layouts.app')

@section('title', 'Tambah Wali Santri')
@section('breadcrumb', 'Tambah Wali Santri')

@section('content')
<div class="page-header">
    <h1 class="page-title">Tambah Wali Santri</h1>
</div>

<div class="card">
    <form action="{{ route('parents.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="nama" class="form-label">Nama Wali Santri</label>
            <input type="text" id="nama" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                   value="{{ old('nama') }}" placeholder="Nama lengkap wali santri" required>
            @error('nama')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="student_id" class="form-label">Santri</label>
            <select id="student_id" name="student_id" class="form-control @error('student_id') is-invalid @enderror" required>
                <option value="">-- Pilih Santri --</option>
                @foreach($santriList as $santri)
                    <option value="{{ $santri['id'] }}" {{ old('student_id') == $santri['id'] ? 'selected' : '' }}>
                        {{ $santri['name'] }} ({{ $santri['nis'] }})
                    </option>
                @endforeach
            </select>
            @error('student_id')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="hubungan" class="form-label">Hubungan</label>
            <select id="hubungan" name="hubungan" class="form-control @error('hubungan') is-invalid @enderror" required>
                <option value="">-- Pilih Hubungan --</option>
                @foreach($hubunganOptions as $option)
                    <option value="{{ $option }}" {{ old('hubungan') == $option ? 'selected' : '' }}>{{ $option }}</option>
                @endforeach
            </select>
            @error('hubungan')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="no_hp" class="form-label">Nomor HP</label>
            <input type="text" id="no_hp" name="no_hp" class="form-control @error('no_hp') is-invalid @enderror" 
                   value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required>
            @error('no_hp')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email (Opsional)</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}" placeholder="email@example.com">
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="alamat" class="form-label">Alamat</label>
            <textarea id="alamat" name="alamat" rows="3" class="form-control @error('alamat') is-invalid @enderror" 
                      placeholder="Alamat lengkap" required>{{ old('alamat') }}</textarea>
            @error('alamat')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('parents.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
