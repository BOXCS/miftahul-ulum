@extends('layouts.app')
@section('content')
    @isset($edit)@dd($santri->toArray())@endisset
    <div class="container pt-3 pb-5">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header border-0 rounded-top-4" style="background: linear-gradient(135deg, #018183f3);">
                <h4 class="mb-0 text-center fw-bold py-3 text-white">Formulir Pendaftaran Santri</h4>
            </div>
            <div class="card-body px-5 py-4">
                <form action="{{ route('santri.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- kolom kiri -->
                        <div class="col-md-6 pe-md-5">
                            <div class="mb-4">
                                <label for="id_santri" class="form-label fw-semibold text-black">ID Santri</label>
                                <input type="text" name="id_santri" id="id_santri" class="form-control border border-black shadow-sm py-2">
                            </div>
                            <div class="mb-4">
                                <label for="nama" class="form-label fw-semibold text-black">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" class="form-control border border-black shadow-sm py-2">
                            </div>
                            <div class="mb-4">
                                <label for="tahun_angkatan" class="form-label fw-semibold text-black">Tahun Angkatan</label>
                                <input type="number" name="tahun_angkatan" id="tahun_angkatan" class="form-control border border-black shadow-sm py-2" min="2000" max="{{ date('Y') }}">
                            </div>
                            <div class="mb-4">
                                <label for="id_ortu" class="form-label fw-semibold text-black">ID Orang Tua</label>
                                <input type="text" name="id_ortu" id="id_ortu" class="form-control border border-black shadow-sm py-2">
                            </div>
                            <input type="text" name="status" value="aktif" hidden>
                        </div>

                        <!-- kolom kanan -->
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-black">Upload Gambar Pendukung</label>
                            <div class="image-upload-container position-relative rounded-4 shadow-sm overflow-hidden" style="height: 360px; border: 2px dashed #20c997; background-color: #ffffff; transition: 0.3s;">
                                <input type="file" name="foto" id="foto" class="position-absolute w-100 h-100 opacity-0" style="z-index: 10; cursor: pointer;" accept="image/*" onchange="previewImage(event)">
                                <div id="upload-placeholder" class="d-flex flex-column justify-content-center align-items-center h-100 w-100 position-absolute top-0 start-0" style="z-index: 2;">
                                    <div class="text-center p-4">
                                        <i class="fas fa-cloud-upload-alt fa-3x mb-3 text-teal"></i>
                                        <h5 class="fw-semibold text-dark">Unggah Gambar</h5>
                                        <p class="text-muted mb-0">Klik atau tarik file ke sini</p>
                                    </div>
                                </div>
                                <div id="preview-container" class="position-absolute top-0 start-0 w-100 h-100" style="z-index: 4; display: none;">
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
                        <button type="reset" class="btn btn-danger px-4 py-2 rounded-3 shadow me-2">Reset</button>
                        <button type="submit" class="btn btn-success px-4 py-2 rounded-3 shadow">Selanjutnya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection