@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Pengumuman & FAQ</h2>

    <!-- Menampilkan pesan error jika ada -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <label>Judul Pengumuman</label>
            <input type="text" name="judul" class="form-control" required>
        </div>

        <div>
            <label>Isi Pengumuman</label>
            <textarea name="isi" class="form-control" rows="4" required></textarea>
        </div>

        <div>
            <label>Kategori</label>
            <select name="kategori" class="form-control" required>
                <option value="akademik">Akademik</option>
                <option value="administrasi">Administrasi</option>
                <option value="kegiatan">Kegiatan</option>
            </select>
        </div>

        <div>
            <label>Tanggal Mulai</label>
            <input type="date" name="tgl_mulai" class="form-control" required>
        </div>

        <div>
            <label>Tanggal Selesai</label>
            <input type="date" name="tgl_selesai" class="form-control" required>
        </div>

        <div>
            <label>Upload Gambar</label>
            <input type="file" name="foto" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary mt-3">Simpan</button>
    </form>

</div>
@endsection
