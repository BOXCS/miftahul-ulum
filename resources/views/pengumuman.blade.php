@extends('layouts.app')

@section('content')
    <div class="container-fluid">

        <div class="mb-4">
            <h1>Pengumuman</h1>
            {{-- <p class="text-muted">Pantau kehadiran berdasarkan filter bulan, tahun, dan waktu shalat.</p> --}}
        </div>
        <div class="card shadow border-0 rounded-4">
            {{-- <div class="card-header border-0 rounded-top-4"
             style="background: linear-gradient(135deg, #018183f3);">
            <h4 class="mb-0 text-center fw-bold py-3 text-white">
                Form Input Pengumuman
            </h4>
        </div> --}}

            <div class="card-body px-5 py-7">
                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm rounded-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: '{{ session('success') }}',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    </script>
                @endif

                <form action="{{ route('pengumuman.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6 pe-md-5">
                            <div class="mb-4">
                                <label class="form-label fw-semibold text-black">Kategori</label>
                                <select name="kategori" class="form-select border border-black shadow-sm py-2" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="akademik">Akademik</option>
                                    <option value="administrasi">Administrasi</option>
                                    <option value="kegiatan">Kegiatan</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-black">Judul Pengumuman</label>
                                <input type="text" name="judul" class="form-control border border-black shadow-sm py-2"
                                    placeholder="Contoh: Libur Lebaran" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-semibold text-black">Isi Pengumuman</label>
                                <textarea name="isi" class="form-control border border-black shadow-sm" rows="6"
                                    placeholder="Contoh: Santri akan diliburkan..." required></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold text-black">Tanggal Mulai</label>
                                    <input type="date" name="tgl_mulai" id="tgl_mulai"
                                        class="form-control border border-black shadow-sm" required>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label fw-semibold text-black">Tanggal Selesai</label>
                                    <input type="date" name="tgl_selesai" id="tgl_selesai"
                                        class="form-control border border-black shadow-sm" required>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-black">Upload Gambar Pendukung</label>

                            <div class="image-upload-container position-relative rounded-4 shadow-sm overflow-hidden"
                                style="height: 360px; border: 2px dashed #20c997; background-color: #ffffff; transition: 0.3s;">

                                <input type="file" name="foto" id="foto"
                                    class="position-absolute w-100 h-100 opacity-0" style="z-index: 10; cursor: pointer;"
                                    accept="image/*" onchange="previewImage(event)">

                                <div id="upload-placeholder"
                                    class="d-flex flex-column justify-content-center align-items-center h-100 w-100 position-absolute top-0 start-0"
                                    style="z-index: 2;">
                                    <div class="text-center p-4">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-teal"></i>
                                        <h5 class="fw-semibold text-dark">Unggah Gambar</h5>
                                        <p class="text-muted mb-0">Klik atau tarik file ke sini</p>
                                    </div>
                                </div>

                                <div id="preview-container" class="position-absolute top-0 start-0 w-100 h-100"
                                    style="z-index: 4; display: none;">
                                    <img id="preview" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <small class="text-muted">Format: SVG, PNG, JPG, GIF (max 800x400px)</small>
                                <small id="file-size" class="text-muted"></small>
                            </div>
                            <div class="mt-1">
                                <small id="file-name" class="text-muted"></small>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-3 shadow">
                            Kirim Pengumuman
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const container = document.getElementById('preview-container');
            const placeholder = document.getElementById('upload-placeholder');
            const file = event.target.files[0];

            if (file) {
                preview.src = URL.createObjectURL(file);
                container.style.display = 'block';
                placeholder.style.display = 'none';

                document.getElementById('file-size').textContent = `${(file.size / 1024).toFixed(2)} KB`;
                document.getElementById('file-name').textContent = file.name;
            }
        }
    </script>
@endsection
