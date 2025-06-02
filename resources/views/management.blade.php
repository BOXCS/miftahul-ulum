@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .controls {
            background: white;
            padding: 1.5rem 2rem;
            margin-top: 0 !important;
        }

        .header {
            background: rgb(172, 199, 238);
            padding: 2rem;
            border-bottom: 1px solid rgb(235, 243, 255);
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }

        .main-title {
            font-size: 2rem;
            font-weight: 700;
            color: black;
            margin: 0;
            text-align: center;
        }

        .subTitle {
            color: #4f46e5;
            font-weight: 600;
        }

        #datatable_wrapper .row:first-child {
            display: none;
        }

        .dt-layout-table+.row {
            padding: 20px;
            padding-inline: 40px;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        #datatable_wrapper {
            margin-top: 0;
            background: white;
            border-top: 0.1px solid black;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }

        .dt-layout-table {
            margin-top: 0;
            padding-block: 3px;
        }
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row g-4">
<div class="header">
    <h1 class="main-title">
        Manajemen Data <span class="subTitle">Santri</span>
    </h1>
    <p>Role user: {{ auth()->user()->hak_akses }}</p>
</div>

<div class="controls">
    <div class="row g-3 align-items-center">
        <div class="col-md-3">
            <select class="form-select" name="mode" id="mode" oninput="changeMode()">
                <option value="santri">Santri</option>
                <option value="ortu">Orang Tua</option>
                @if (auth()->user()->hak_akses === 'superadmin')
                    <option value="staf">Staf</option>
                @endif
            </select>
        </div>
        <div class="col-md-6">
            <div class="input-group p-0">
                <input type="text" class="form-control" id="myInput" placeholder="Cari...">
                <span class="input-group-text">
                    <i class="bi bi-search"></i>
                </span>
            </div>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#TambahSantri" id="add-button">
                <i class="bi bi-plus-circle me-1"></i>
                Tambah Santri Baru
            </button>
        </div>
    </div>
</div>

<div class="modal fade" id="TambahSantri" tabindex="-1" aria-labelledby="Tambah santri">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Tambah Santri</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah siswa yang akan ditambahkan sudah memiliki data orang tua dalam sistem?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <a href="{{ route('orangtua.create') }}" class="btn btn-success" style="color:white">Belum</a>
                <a href="{{ route('santri.create') }}" class="btn btn-primary">Sudah</a>
            </div>
        </div>
    </div>
</div>

<table id="datatable" class="table table-striped bg-light">

                <thead>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const userRole = "{{ auth()->user()->hak_akses }}";
    </script>
    <script></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        // pembuatan objek agar objek bisa dipanggil kembali, ketika akan reinilisasi
        tableObj = new DataTable('#datatable', {
            // get data dari variabel laravel
            data: {!! json_encode($santri->toArray()) !!},
            // membuat tabel dengan title=judul column dan data=data yg ditampilkan
            // yang langsung terhubung dengan option data/statement diatas
            columns: [
                { title: 'ID', data: 'id_santri' },
                { title: 'Nama Lengkap', data: 'nama' },
                { title: 'Orang Tua', data: 'ortu.nama_lengkap' },
                { title: 'Sidik Jari', data: 'sidik_jari' },
                { title: 'Status', data: 'status' },
                { title: 'Aksi', data: 'id_santri', width: '200px',
                    
                    // add button edit and delete
                    render: function (data, type, row) {
                        const test = `
                            <a href="{{ route('santri.edit', ['santri' => '__ID__']) }}" class="btn btn-primary">Edit</a>
                            <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal${data}">Hapus</a>
                            <div class="modal fade" id="exampleModal${data}" tabindex="-1" aria-labelledby="exampleModal__ID__" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Hapus</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Apakah anda ingin menghapus santri yang bernama <span class="fw-bold">${row.nama}</span>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="/santri/${data}" method="POST">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <button type="submit" class="btn btn-danger">Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                        return test.replace('__ID__', data);
                    }
                }
            ],
            // detail properti tiap kolom
            columnDefs: [
                { className: "dt-head-center align-middle", targets: '_all' },
                { targets: 0, className: 'dt-center' },
                { targets: 3, orderable: false, searchable: false },
                { targets: 4, orderable: false },
                { targets: 5, orderable: false, searchable: false, className: 'dt-center' },
            ]
        });
        document.getElementById('myInput').addEventListener('keyup', function () {
            tableObj.search(this.value).draw();
        });
        // function for changes datatable will be display
        function changeMode() {
            var text = document.getElementById('mode').value;
            var addButton = document.getElementById('add-button');
            var subTitle = document.getElementsByClassName('subTitle')[0];

            // jika admin mencoba memilih staf secara manual via devtools
            if (text === "staf" && userRole !== "superadmin") {
                alert("Anda tidak memiliki akses ke data staf.");
                document.getElementById('mode').value = "santri"; // reset kembali
                return;
            }

            // function for clear table (before reinitialize)
            var tableId = "#datatable";
            // clear first
            if (tableObj != null) {
                tableObj.clear();
                tableObj.destroy();
            }

            //2nd empty html
            $(tableId + " tbody").empty();
            $(tableId + " thead").empty();

            //3rd reCreate Datatable object
            switch (text) {
                case "santri":
                    // ganti tombol "tambah"
                    addButton.innerHTML = `<i class="bi bi-plus-circle me-1"></i>Tambah Santri Baru`;
                    addButton.setAttribute("data-bs-toggle", "modal");
                    addButton.setAttribute("data-bs-target", "#TambahSantri");
                    subTitle.innerText = "Santri";

                    // ganti data dari datatable
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($santri->toArray()) !!},
                        columns: [
                            { title: 'ID', data: 'id_santri' },
                            { title: 'Nama Lengkap', data: 'nama' },
                            { title: 'Orang Tua', data: 'ortu.nama_lengkap' },
                            { title: 'Sidik Jari', data: 'sidik_jari' },
                            { title: 'Status', data: 'status' },
                            { title: 'Aksi', data: 'id_santri', width: '200px',
                                
                                // add button edit and delete
                                render: function (data, type, row) {
                                    const test = `
                                        <a href="{{ route('santri.edit', ['santri' => '__ID__']) }}" class="btn btn-primary">Edit</a>
                                        <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal${data}">Hapus</a>
                                        <div class="modal fade" id="exampleModal${data}" tabindex="-1" aria-labelledby="exampleModal__ID__" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Hapus</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah anda ingin menghapus santri yang bernama <span class="fw-bold">${row.nama}</span>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="/santri/${data}" method="POST">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    return test.replace('__ID__', data);
                                }
                            }
                        ],
                        columnDefs: [
                            { className: "dt-head-center align-middle", targets: '_all' },
                            { targets: 0, className: 'dt-center' },
                            { targets: 3, orderable: false, searchable: false },
                            { targets: 4, orderable: false },
                            { targets: 5, orderable: false, searchable: false, className: 'dt-center' },
                        ]
                    });
                    break;
                case "ortu":
                    // ganti tombol "tambah"
                    addButton.innerHTML = `<i class="bi bi-plus-circle me-1"></i>Tambah Orang Tua Baru`;
                    addButton.addEventListener('click', function() { window.location.href = '{{ route("orangtua.create") }}'; });
                    addButton.removeAttribute("data-bs-toggle");
                    addButton.removeAttribute("data-bs-target");
                    subTitle.innerText = "Orang Tua";

                    // ganti data dari datatable
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($ortu->toArray()) !!},
                        columns: [
                            { title: 'ID', data: 'id_ortu' },
                            { title: 'Nama Orang Tua', data: 'nama_lengkap' },
                            { title: 'Alamat', data: 'alamat' },
                            { title: 'No. Telp', data: 'no_telp' },
                            { title: 'Nama Santri', data: 'santri[, ].nama' },
                            { title: 'Aksi', data: 'id_ortu', width: '200px',
                                
                                // add button edit and delete
                                render: function (data, type, row) {
                                    const test = `
                                        <a href="{{ route('orangtua.edit', ['orang_tua' => '__ID__']) }}" class="btn btn-primary">Edit</a>
                                        <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal${data}">Hapus</a>
                                        <div class="modal fade" id="exampleModal${data}" tabindex="-1" aria-labelledby="exampleModal__ID__" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Hapus</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah anda ingin menghapus orang tua santri yang bernama <span class="fw-bold">${row.nama_lengkap}</span>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="/orang-tua/${data}" method="POST">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    return test.replace('__ID__', data);
                                }
                            }
                        ],
                        columnDefs: [
                            { className: "dt-head-center align-middle", targets: '_all' },
                            { targets: 0, className: 'dt-center' },
                            { targets: 3, orderable: false },
                            { targets: 5, orderable: false, searchable: false, className: 'dt-center' },
                        ]
                    });
                    break;
                case 'staf':
                    // ganti tombol "tambah"
                    addButton.innerHTML = `<i class="bi bi-plus-circle me-1"></i>Tambah Staf Baru`;
                    addButton.setAttribute('href', '{{ route("staff.create") }}');
                    addButton.removeAttribute("data-bs-toggle");
                    addButton.removeAttribute("data-bs-target");
                    subTitle.innerText = "Staf";

                    // ganti data dari datatable
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($staff->toArray()) !!},
                        columns: [
                            { title: 'ID', data: 'id_staf' },
                            { title: 'Nama Lengkap', data: 'nama' },
                            { title: 'Alamat', data: 'alamat' },
                            { title: 'No. Telp', data: 'no_telp' },
                            { title: 'Jabatan', data: 'jabatan' },
                            { title: 'Aksi', data: 'id_staf', width: '200px',
                                
                                // add button edit and delete
                                render: function (data, type, row) {
                                    const test = `
                                        <a href="{{ route('staff.edit', ['staff' => '__ID__']) }}" class="btn btn-primary">Edit</a>
                                        <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal${data}">Hapus</a>
                                        <div class="modal fade" id="exampleModal${data}" tabindex="-1" aria-labelledby="exampleModal__ID__" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Hapus</h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Apakah anda ingin menghapus staf yang bernama <span class="fw-bold">${row.nama}</span>?</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <form action="/staff/${data}" method="POST">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                            <button type="submit" class="btn btn-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    return test.replace('__ID__', data);
                                }
                            }
                        ],
                        columnDefs: [
                            { className: "dt-head-center align-middle", targets: '_all' },
                            { targets: 0, className: 'dt-center' },
                            { targets: 3, orderable: false },
                            { targets: 5, orderable: false, searchable: false, className: 'dt-center' },

                        ]
                    });
                    break;
                default:
                    break;
            }
        }
    </script>
@endpush
