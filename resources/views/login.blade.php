@extends('layouts.auth')

@section('content')
    <div class="d-flex w-100 min-vh-100">
        <!-- Form Login - Memenuhi setengah layar kiri -->
        <div class="col-md-6 d-flex flex-column justify-content-center px-5 flex-grow-1">
            <h2 class="fw-bold mb-3">Masuk Ke Akun</h2>
            <p class="text-muted">Masukkan Informasi Akun Anda</p>
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
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control" name="email" required placeholder="Masukkan Alamat Email Anda">
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control" name="password" required placeholder="Masukkan Password Anda">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
            </form>
        </div>

        <!-- Gambar - Memenuhi setengah layar kanan -->
        <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center flex-grow-2">
            <img src="{{ asset('image/login2.png') }}" alt="Login Image" class="img-fluid" style="max-width: 100%; height: auto;">
        </div>
    </div>

    <!-- JavaScript untuk Toggle Password -->
    {{-- <script>
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
    </script> --}}

    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
