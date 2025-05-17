@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Pengumuman & FAQ</h2>
        <div class="badge bg-primary p-2">
            <i class="fas fa-bullhorn me-2"></i>Buat Pengumuman Baru
        </div>
    </div>

    {{-- Menampilkan pesan error jika ada --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <h5 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>Error!</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form action="{{ route('announcement.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- Kiri --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="judul" class="form-label fw-bold">Judul Pengumuman</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-heading"></i></span>
                                <input type="text" name="judul" class="form-control" placeholder="Masukkan judul pengumuman" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="isi" class="form-label fw-bold">Isi Pengumuman</label>
                            <textarea name="isi" class="form-control" rows="5" placeholder="Tulis isi pengumuman disini..." required></textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tgl_mulai" class="form-label fw-bold">Tanggal Mulai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="far fa-calendar-alt"></i></span>
                                    <input type="date" name="tgl_mulai" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="tgl_selesai" class="form-label fw-bold">Tanggal Selesai</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="far fa-calendar-check"></i></span>
                                    <input type="date" name="tgl_selesai" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Kanan --}}
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="kategori" class="form-label fw-bold">Kategori</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fas fa-tag"></i></span>
                                <select name="kategori" class="form-select" required>
                                    <option value="" selected disabled>Pilih Kategori</option>
                                    <option value="akademik">Akademik</option>
                                    <option value="administrasi">Administrasi</option>
                                    <option value="kegiatan">Kegiatan</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label fw-bold d-block">Upload Gambar</label>
                            <div class="border border-2 border-dashed rounded p-4 text-center hover-shadow" 
                                id="dropArea" 
                                style="cursor: pointer; background-color: #f8f9fa; transition: all 0.3s;">
                                <input type="file" name="foto" class="form-control d-none" id="fileUpload" accept="image/*">
                                <label for="fileUpload" class="d-block">
                                    <div class="mb-3">
                                        <i class="fas fa-cloud-upload-alt fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="mb-2">Seret & Lepaskan Gambar Disini</h5>
                                    <p class="mb-1 text-muted">Atau klik untuk memilih file</p>
                                    <small class="text-muted d-block">Format yang didukung: SVG, PNG, JPG, GIF</small>
                                    <small class="text-muted d-block">Ukuran maksimal: 2MB</small>
                                </label>
                                <div id="preview" class="mt-3 d-none">
                                    <img id="previewImage" src="#" alt="Preview" class="img-thumbnail" style="max-height: 150px;">
                                    <button type="button" class="btn btn-sm btn-danger mt-2" id="removeImage">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="reset" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-undo me-1"></i>Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Simpan Pengumuman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('fileUpload');
        const preview = document.getElementById('preview');
        const previewImage = document.getElementById('previewImage');
        const removeBtn = document.getElementById('removeImage');

        // Highlight drop area when item is dragged over it
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropArea.classList.add('border-primary');
            dropArea.style.backgroundColor = '#e9f5ff';
        }

        function unhighlight() {
            dropArea.classList.remove('border-primary');
            dropArea.style.backgroundColor = '#f8f9fa';
        }

        // Handle dropped files
        dropArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                fileInput.files = files;
                handleFiles(files);
            }
        }

        // Handle selected files
        fileInput.addEventListener('change', function() {
            handleFiles(this.files);
        });

        function handleFiles(files) {
            const file = files[0];
            if (file && file.type.match('image.*')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        }

        // Remove image
        removeBtn.addEventListener('click', function() {
            fileInput.value = '';
            preview.classList.add('d-none');
            previewImage.src = '#';
        });
    });
</script>
@endsection
@endsection