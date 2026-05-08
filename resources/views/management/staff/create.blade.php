@extends('layouts.app')
@push('styles')
<style>
    .btn-radio{
        --bs-btn-active-color: black;
    }
</style>
@endpush
@section('content')
    <div class="container pt-3 pb-5">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header border-0 rounded-top-4" style="background: linear-gradient(135deg, #018183f3);">
                <h4 class="mb-0 text-center fw-bold py-3 text-white">Formulir Pendaftaran Staf</h4>
            </div>
            <div class="card-body px-5 py-4">
                <form action="{{ isset($staff)?route('staff.update', $staff->id_staf):route('staff.store') }}" method="POST" enctype="multipart/form-data">
                    @if (isset($staff))
                        @method('PUT')
                    @endif
                    @csrf
                    <div class="row">
                        <!-- kolom kiri -->
                        <div class="col-md-6 pe-md-5">
                            <div class="mb-4">
                                <label for="nama" class="form-label fw-semibold text-black">Nama Lengkap</label>
                                <input type="text" name="nama" id="nama" class="form-control border border-black shadow-sm py-2" value="{{ isset($staff)? $staff->nama:'' }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="alamat" class="form-label fw-semibold text-black">Alamat Lengkap</label>
                                <input type="text" name="alamat" id="alamat" class="form-control border border-black shadow-sm py-2" value="{{ isset($staff)? $staff->alamat:'' }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="nomorTelepon" class="form-label fw-semibold text-black">Nomor Telepon</label>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text border border-black bg-white text-black px-3 py-2" style="font-weight: 500;">+62</span>
                                        <input type="tel" name="no_telp" id="nomorTelepon" class="form-control border border-black bg-white text-black py-2" placeholder="8123456789" pattern="8[0-9]{8,11}" oninvalid="this.setCustomValidity('Nomor harus dimulai dengan 8 dan terdiri dari 9 hingga 12 digit angka')" oninput="this.setCustomValidity('');" value="{{ isset($staff)? $staff->no_telp:'' }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="jabatan" class="form-label fw-semibold text-black">Jabatan</label>
                                <input type="text" name="jabatan" id="jabatan" class="form-control border border-black shadow-sm py-2" value="{{ isset($staff)? $staff->jabatan:'' }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="tgl_bergabung" class="form-label fw-semibold text-black">Tanggal Bergabung</label>
                                <input type="date" name="tgl_bergabung" id="tgl_bergabung" class="form-control border border-black shadow-sm py-2" value="{{ isset($staff)? $staff->tgl_bergabung:'' }}" required>
                            </div>
                        </div>

                        <!-- kolom kanan -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-black">Email</label>
                                <input type="email" name="email" id="email" class="form-control border border-black shadow-sm py-2" value="{{ isset($staff)? $staff->akun->email:'' }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="username" class="form-label fw-semibold text-black">Username</label>
                                <input type="text" name="username" id="username" class="form-control border border-black shadow-sm py-2" value="{{ isset($staff)? $staff->akun->username:'' }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-black">Password</label>
                                <input type="password" name="password" id="password" class="form-control border border-black shadow-sm py-2" pattern="(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d@#$%^&+=]{8,}" oninvalid="this.setCustomValidity('Gunakan minimal 8 karakter, kombinasi huruf dan angka (boleh tambah simbol)')" oninput="this.setCustomValidity('');" required>
                            </div>
                            <div class="mb-4">
                                <label for="hak_akses" class="form-label fw-semibold text-black d-block">Hak Akses</label>
                                <input type="radio" class="btn-check" name="hak_akses" id="ortu" autocomplete="off" value="ortu" {{ isset($staff)?($staff->akun->hak_akses=='ortu'?'checked':''):''}}>
                                <label class="btn btn-outline-primary btn-radio" for="ortu">Orang tua</label>
                                <input type="radio" class="btn-check" name="hak_akses" id="admin" autocomplete="off" value="admin" {{ isset($staff)?($staff->akun->hak_akses=='admin'?'checked':''):'checked' }}>
                                <label class="btn btn-outline-info btn-radio" for="admin">Admin</label>
                                <input type="radio" class="btn-check" name="hak_akses" id="superadmin" autocomplete="off" value="superadmin" {{ isset($staff)?($staff->akun->hak_akses=='superadmin'?'checked':''):'' }}>
                                <label class="btn btn-outline-warning btn-radio" for="superadmin">Superadmin</label>
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