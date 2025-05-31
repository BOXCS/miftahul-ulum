@extends('layouts.app')

@section('title', 'Edit Profil')

@section('content')
    <div class="container-fluid px-3">
        <div class="row g-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <span>{{ session('success') }}</span>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    </div>
                @endif

                <div class="card shadow-lg border-0 overflow-hidden">
                    {{-- Card Header with Gradient Background --}}
                    <div class="card-header bg-gradient-primary text-white py-3 position-relative">
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="h4 mb-0 fw-semibold">
                                <i class="bi bi-person-gear me-2"></i>Edit Profil Admin
                            </h2>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline-light rounded-pill px-3">
                                    <i class="bi bi-box-arrow-right me-1"></i>Logout
                                </button>
                            </form>
                        </div>
                        <div class="position-absolute bottom-0 start-0 w-100">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-white opacity-25" style="width: 100%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Card Body with Page-like Layout --}}
                    <div class="card-body p-0">
                        <form action="{{ route('profile.update', $akun->id_akun) }}" method="POST" class="needs-validation"
                            novalidate>
                            @csrf
                            @method('PUT')

                            <div class="p-4 pb-0">
                                {{-- Personal Information Section --}}
                                <div class="mb-4">
                                    <h5 class="d-flex align-items-center text-primary mb-4">
                                        <span class="bg-primary bg-opacity-10 p-2 rounded me-3">
                                            <i class="bi bi-person-badge"></i>
                                        </span>
                                        Informasi Pribadi
                                    </h5>

                                    <div class="row g-3">
                                        <div class="col-md-12">
                                            <label for="nama" class="form-label fw-medium">Nama Lengkap</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                                                <input type="text" class="form-control" id="nama" name="nama"
                                                    value="{{ old('nama', $staff->nama) }}" required>
                                                <div class="invalid-feedback">
                                                    Harap masukkan nama lengkap
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-8">
                                            <label for="alamat" class="form-label fw-medium">Alamat</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="bi bi-geo-alt"></i></span>
                                                <input type="text" class="form-control" id="alamat" name="alamat"
                                                    value="{{ old('alamat', $staff->alamat) }}" required>
                                                <div class="invalid-feedback">
                                                    Harap masukkan alamat
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="no_telp" class="form-label fw-medium">No Telepon</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i
                                                        class="bi bi-telephone"></i></span>
                                                <input type="text" class="form-control" id="no_telp" name="no_telp"
                                                    value="{{ old('no_telp', $staff->no_telp) }}" required>
                                                <div class="invalid-feedback">
                                                    Harap masukkan nomor telepon
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                {{-- Account Information Section --}}
                                <div class="mb-4">
                                    <h5 class="d-flex align-items-center text-secondary mb-4">
                                        <span class="bg-secondary bg-opacity-10 p-2 rounded me-3">
                                            <i class="bi bi-shield-lock"></i>
                                        </span>
                                        Informasi Akun
                                    </h5>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="email" class="form-label fw-medium">Alamat Email</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i
                                                        class="bi bi-envelope"></i></span>
                                                <input type="email" class="form-control" id="email" name="email"
                                                    value="{{ old('email', $akun->email) }}" required>
                                                <div class="invalid-feedback">
                                                    Harap masukkan email yang valid
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <label for="username" class="form-label fw-medium">Username</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i
                                                        class="bi bi-person-circle"></i></span>
                                                <input type="text" class="form-control" id="username" name="username"
                                                    value="{{ old('username', $akun->username) }}" required>
                                                <div class="invalid-feedback">
                                                    Harap masukkan username
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <label for="password" class="form-label fw-medium">Password Baru</label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                                                <input type="password" class="form-control" id="password"
                                                    name="password" placeholder="Kosongkan jika tidak ingin mengubah">
                                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">Minimal 8 karakter</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="card-footer bg-light p-4 border-top">
                                <div class="d-flex justify-content-between">
                                    <button type="reset" class="btn btn-outline-secondary rounded-pill px-4">
                                        <i class="bi bi-arrow-counterclockwise me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">
                                        <i class="bi bi-save me-2"></i>Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
    </div>

    <style>
        .bg-gradient-primary {
            background: linear-gradient(135deg, #449098, #80c3ca);
        }

        .card {
            border-radius: 12px;
            border: none;
        }

        .card-header {
            border-radius: 12px 12px 0 0 !important;
        }

        .form-control,
        .input-group-text {
            border-radius: 8px !important;
        }

        .input-group-text {
            min-width: 45px;
            justify-content: center;
        }

        hr {
            opacity: 0.15;
        }

        .card-footer {
            border-radius: 0 0 12px 12px !important;
        }
    </style>

    <script>
        // Password toggle functionality
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function() {
                const passwordInput = this.previousElementSibling;
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' :
                    '<i class="bi bi-eye-slash"></i>';
            });
        });

        // Form validation
        (function() {
            'use strict';
            window.addEventListener('load', function() {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function(form) {
                    form.addEventListener('submit', function(event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
@endsection
