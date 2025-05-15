@extends('layouts.auth')

@section('content')
    <div class="d-flex w-100 min-vh-100">
        <!-- Form Login - Memenuhi setengah layar kiri -->
        <div class="col-md-6 d-flex flex-column justify-content-center px-5 flex-grow-1">
            <h2 class="fw-bold mb-3">Masuk Ke Akun</h2>
            <p class="text-muted">Masukkan Informasi Akun Anda</p>
            <form method="POST" action="{{ route('auth.authenticate') }}">

                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" class="form-control" name="email" required
                        placeholder="Masukkan Alamat Email Anda">
                </div>
                <div class="mb-3 position-relative">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input id="password" type="password" class="form-control" name="password" required
                            placeholder="Masukkan Password Anda">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Masuk</button>
            </form>
            @if (session('error'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: '{{ session('error') }}',
                        });
                    });
                </script>
            @endif


            @if ($errors->any())
                @push('scripts')
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Validasi Gagal',
                            html: `{!! implode('<br>', $errors->all()) !!}`,
                        });
                    </script>
                @endpush
            @endif

        </div>

        <!-- Gambar - Memenuhi setengah layar kanan -->
        <div class="col-md-6 d-none d-md-flex justify-content-center align-items-center flex-grow-2">
            <img src="{{ asset('image/login2.png') }}" alt="Login Image" class="img-fluid"
                style="max-width: 100%; height: auto;">
        </div>
    </div>

    <!-- Tambahkan Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endsection
