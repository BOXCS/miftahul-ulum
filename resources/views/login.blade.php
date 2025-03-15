@extends('layouts.auth')

@section('content')
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row w-100 shadow-lg rounded" style="max-width: 900px; overflow: hidden;">
            <!-- Form Login -->
            <div class="col-md-6 bg-white p-5 d-flex flex-column justify-content-center">
                <h2 class="fw-bold text-center mb-3">Masuk Ke Akun</h2>
                <p class="text-center text-muted">Masukkan Informasi Akun Anda</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    @if (session('error'))
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: '{{ session('error') }}',
                            });
                        </script>
                    @endif

                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input id="username" type="text" class="form-control" name="username" required
                            placeholder="Masukkan Username Anda">
                    </div>
                    <div class="mb-3 position-relative">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control" name="password" required
                                placeholder="Masukkan Password Anda">
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                <i class="fa fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
                </form>
            </div>

            <!-- Gambar -->
            <div class="col-md-6 p-0 d-none d-md-block">
                <img src="{{ asset('image/login.jpg') }}" class="img-fluid w-100 h-100 object-cover" alt="Login Image">
            </div>
        </div>
    </div>

    <!-- JavaScript untuk Toggle Password -->
    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            let passwordInput = document.getElementById("password");
            let eyeIcon = document.getElementById("eyeIcon");

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        });
    </script>


    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
