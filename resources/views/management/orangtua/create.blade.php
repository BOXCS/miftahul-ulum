@extends('layouts.app')
@push('styles')
<style>
    .btn-radio{
        --bs-btn-active-color: black;
    }
</style>
@endpush
@section('content')
    @isset($edit)@dd($orangtua->toArray())@endisset
    <div class="container pt-3 pb-5">
        <div class="card shadow border-0 rounded-4">
            <div class="card-header border-0 rounded-top-4" style="background: linear-gradient(135deg, #018183f3);">
                <h4 class="mb-0 text-center fw-bold py-3 text-white">Formulir Pendaftaran Orang Tua</h4>
            </div>
            <div class="card-body px-5 py-4">
                <form action="{{ route('orangtua.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <!-- kolom kiri -->
                        <div class="col-md-6 pe-md-5">
                            <div class="mb-4">
                                <label for="nama_lengkap" class="form-label fw-semibold text-black">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control border border-black shadow-sm py-2">
                            </div>
                            <div class="mb-4">
                                <label for="alamat" class="form-label fw-semibold text-black">Alamat Lengkap</label>
                                <input type="text" name="alamat" id="alamat" class="form-control border border-black shadow-sm py-2">
                            </div>
                            <div class="mb-4">
                                <label for="nomorTelepon" class="form-label fw-semibold text-black">Nomor Telepon</label>
                                <div class="mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text border border-black bg-white text-black px-3 py-2" style="font-weight: 500;">+62</span>
                                        <input type="tel" name="no_telp" id="nomorTelepon" class="form-control border border-black bg-white text-black py-2" placeholder="8123456789" pattern="8[0-9]{8,11}" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- kolom kanan -->
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold text-black">Email</label>
                                <input type="email" name="email" id="email" class="form-control border border-black shadow-sm py-2">
                            </div>
                            <div class="mb-4">
                                <label for="username" class="form-label fw-semibold text-black">Username</label>
                                <input type="text" name="username" id="username" class="form-control border border-black shadow-sm py-2">
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold text-black">Password</label>
                                <input type="password" name="password" id="password" class="form-control border border-black shadow-sm py-2">
                            </div>
                            <div class="mb-4">
                                <label for="hak_akses" class="form-label fw-semibold text-black d-block">Hak Akses</label>
                                <input type="radio" class="btn-check" name="hak_akses" id="ortu" autocomplete="off" value="ortu" @if(Route::currentRouteName() == 'orangtua.create') checked @endif>
                                <label class="btn btn-outline-primary btn-radio" for="ortu">Orang tua</label>
                                <input type="radio" class="btn-check" name="hak_akses" id="admin" autocomplete="off" value="admin" @if(Route::currentRouteName() == 'staff.create') checked @endif>
                                <label class="btn btn-outline-info btn-radio" for="admin">Admin</label>
                                <input type="radio" class="btn-check" name="hak_akses" id="superadmin" autocomplete="off" value="superadmin">
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