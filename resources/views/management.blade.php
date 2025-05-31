@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <h1>Manajemen Data</h1>
            <p>Role user: {{ auth()->user()->hak_akses }}</p>
            <select class="form-select" aria-label="Default select example" name="mode" id="mode"
                onchange="changeMode()">
                <option value="santri">Santri</option>
                <option value="ortu">Orang Tua</option>
                @if (auth()->user()->hak_akses === 'superadmin')
                    <option value="staf">Staf</option>
                @endif
            </select>


            <a href="{{ route('santri.create') }}" class="btn btn-primary" id="add-button">Tambah Santri Baru</a>
            <table id="datatable" class="table table-striped" style="width:100%">
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
            columns: [{
                    title: 'ID',
                    data: 'id_santri'
                },
                {
                    title: 'Nama Lengkap',
                    data: 'nama'
                },
                {
                    title: 'Orang Tua',
                    data: 'ortu.nama_lengkap'
                },
                {
                    title: 'Sidik Jari',
                    data: 'sidik_jari'
                },
                {
                    title: 'Status',
                    data: 'status'
                },
                {
                    title: 'Aksi',
                    data: 'id_santri',
                    render: function(data, type, row) {
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
            columnDefs: [{
                    targets: 3,
                    orderable: false,
                    searchable: false
                },
                {
                    targets: 4,
                    orderable: false
                },
                {
                    targets: 5,
                    orderable: false,
                    searchable: false
                },
            ]
        });
        // function for changes datatable will be display
        function changeMode() {
            var text = document.getElementById('mode').value;
            var addButton = document.getElementById('add-button');

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
                    addButton.innerHTML = "Tambah Santri Baru";
                    addButton.setAttribute('href', '{{ route('santri.create') }}');

                    // ganti data dari datatable
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($santri->toArray()) !!},
                        columns: [{
                                title: 'ID',
                                data: 'id_santri'
                            },
                            {
                                title: 'Nama Lengkap',
                                data: 'nama'
                            },
                            {
                                title: 'Orang Tua',
                                data: 'ortu.nama_lengkap'
                            },
                            {
                                title: 'Sidik Jari',
                                data: 'sidik_jari'
                            },
                            {
                                title: 'Status',
                                data: 'status'
                            },
                            {
                                title: 'Aksi',
                                data: 'id_santri',
                                render: function(data, type, row) {
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
                        columnDefs: [{
                                targets: 3,
                                orderable: false,
                                searchable: false
                            },
                            {
                                targets: 4,
                                orderable: false
                            },
                            {
                                targets: 5,
                                orderable: false,
                                searchable: false
                            },
                        ]
                    });
                    break;
                case "ortu":
                    // ganti tombol "tambah"
                    addButton.innerHTML = "Tambah Orang Tua Baru";
                    addButton.setAttribute('href', '{{ route('orangtua.create') }}');

                    // ganti data dari datatable
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($ortu->toArray()) !!},
                        columns: [{
                                title: 'ID',
                                data: 'id_ortu'
                            },
                            {
                                title: 'Nama Orang Tua',
                                data: 'nama_lengkap'
                            },
                            {
                                title: 'Alamat',
                                data: 'alamat'
                            },
                            {
                                title: 'No. Telp',
                                data: 'no_telp'
                            },
                            {
                                title: 'Nama Santri',
                                data: 'santri[, ].nama'
                            },
                            {
                                title: 'Aksi',
                                data: 'id_ortu',
                                render: function(data, type, row) {
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
                        columnDefs: [{
                                targets: 3,
                                orderable: false
                            },
                            {
                                targets: 5,
                                orderable: false,
                                searchable: false
                            },
                        ]
                    });
                    break;
                case 'staf':
                    // ganti tombol "tambah"
                    addButton.innerHTML = "Tambah Staf Baru";
                    addButton.setAttribute('href', '{{ route('staff.create') }}');

                    // ganti data dari datatable
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($staff->toArray()) !!},
                        columns: [{
                                title: 'ID',
                                data: 'id_staf'
                            },
                            {
                                title: 'Nama Lengkap',
                                data: 'nama'
                            },
                            {
                                title: 'Alamat',
                                data: 'alamat'
                            },
                            {
                                title: 'No. Telp',
                                data: 'no_telp'
                            },
                            {
                                title: 'Jabatan',
                                data: 'jabatan'
                            },
                            {
                                title: 'Aksi',
                                data: 'id_staf',
                                render: function(data, type, row) {
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
                        columnDefs: [{
                                targets: 3,
                                orderable: false
                            },
                            {
                                targets: 5,
                                orderable: false,
                                searchable: false
                            },
                        ]
                    });
                    break;
                default:
                    break;
            }
        }
    </script>
@endpush
