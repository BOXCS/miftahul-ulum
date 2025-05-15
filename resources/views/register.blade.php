@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Register</h2>

    <ul class="nav nav-tabs" id="registerTabs">
        <li class="nav-item">
            <a class="nav-link active" id="tab-ortu" data-bs-toggle="tab" href="#form-ortu">Orang Tua</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-admin" data-bs-toggle="tab" href="#form-admin">Admin</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="tab-superadmin" data-bs-toggle="tab" href="#form-superadmin">Superadmin</a>
        </li>
    </ul>

    <div class="tab-content mt-3">
        <!-- Form Orang Tua -->
        <div class="tab-pane fade show active" id="form-ortu">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="hak_akses" value="ortu">

                <div class="mb-3">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Alamat:</label>
                    <input type="text" name="alamat_ortu" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>No. Telepon:</label>
                    <input type="text" name="no_telp_ortu" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>

        <!-- Form Admin -->
        <div class="tab-pane fade" id="form-admin">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="hak_akses" value="admin">

                <div class="mb-3">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nama:</label>
                    <input type="text" name="nama_staff" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Alamat:</label>
                    <input type="text" name="alamat_staff" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>No. Telepon:</label>
                    <input type="text" name="no_telp_staff" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jabatan:</label>
                    <input type="text" name="jabatan_staff" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Tanggal Bergabung:</label>
                    <input type="date" name="tgl_bergabung_staff" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>

        <!-- Form Superadmin -->
        <div class="tab-pane fade" id="form-superadmin">
            <form method="POST" action="{{ route('register.superadmin') }}">
                @csrf

                <div class="mb-3">
                    <label>Username:</label>
                    <input type="text" name="username" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Email:</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password:</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Nama:</label>
                    <input type="text" name="nama_staff" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Alamat:</label>
                    <input type="text" name="alamat_staff" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>No. Telepon:</label>
                    <input type="text" name="no_telp_staff" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Jabatan:</label>
                    <input type="text" name="jabatan_staff" value="Superadmin" class="form-control" readonly>
                </div>

                <div class="mb-3">
                    <label>Tanggal Bergabung:</label>
                    <input type="date" name="tgl_bergabung_staff" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Register Superadmin</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        @if(session('success'))
            Swal.fire({
                title: "Sukses!",
                text: "{{ session('success') }}",
                icon: "success",
                confirmButtonText: "OK"
            }).then(() => {
                window.location.href = "{{ route('login') }}";
            });
        @endif

        @if(session('error'))
            Swal.fire({
                title: "Gagal!",
                text: "{{ session('error') }}",
                icon: "error",
                confirmButtonText: "OK"
            });
        @endif
    </script>
@endsection

