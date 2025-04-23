@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <h1>Manajemen Data</h1>
            <select class="form-select" aria-label="Default select example" name="mode" id="mode" onchange="changeMode()">
                <option value="santri">Santri</option>
                <option value="ortu">Orang tua</option>
                <option value="staf">Staf</option>
            </select>
            <h3 id="text"></h3>
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
    </script>
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
                { title: 'Orang Tua', data: 'nama' },
                { title: 'Sidik Jari', data: 'sidik_jari' },
                { title: 'Status', data: 'status' },
                { title: 'Aksi', data: 'status' }
            ],
            // detail properti tiap kolom
            columnDefs: [
                { targets: 3, orderable: false, searchable: false },
                { targets: 4, orderable: false },
                { targets: 5, orderable: false, searchable: false },
            ]
        });
        // function for changes datatable will be display
        function changeMode() {
            var text = document.getElementById('mode').value;

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
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($santri->toArray()) !!},
                        columns: [
                            { title: 'ID', data: 'id_santri' },
                            { title: 'Nama Lengkap', data: 'nama' },
                            { title: 'Orang Tua', data: 'nama' },
                            { title: 'Sidik Jari', data: 'sidik_jari' },
                            { title: 'Status', data: 'status' },
                            { title: 'Aksi', data: 'status' }
                        ],
                        columnDefs: [
                            { targets: 3, orderable: false, searchable: false },
                            { targets: 4, orderable: false },
                            { targets: 5, orderable: false, searchable: false },
                        ]
                    });
                    break;
                case "ortu":
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($ortu->toArray()) !!},
                        columns: [
                            { title: 'ID', data: 'id_ortu' },
                            { title: 'Nama Orang Tua', data: 'nama_lengkap' },
                            { title: 'Alamat', data: 'alamat' },
                            { title: 'No. Telp', data: 'no_telp' },
                            { title: 'Nama Santri', data: 'santri[, ].nama' },
                            { title: 'Aksi', data: 'id_ortu' },
                        ],
                        columnDefs: [
                            { targets: 3, orderable: false },
                            { targets: 5, orderable: false, searchable: false },
                        ]
                    });
                    break;
                case 'staf':
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($staff->toArray()) !!},
                        columns: [
                            { title: 'ID', data: 'id_staf' },
                            { title: 'Nama Lengkap', data: 'nama' },
                            { title: 'Alamat', data: 'alamat' },
                            { title: 'No. Telp', data: 'no_telp' },
                            { title: 'Jabatan', data: 'jabatan' },
                            { title: 'Aksi', data: 'id_staf' },
                        ],
                        columnDefs: [
                            { targets: 3, orderable: false },
                            { targets: 5, orderable: false, searchable: false },
                        ]
                    });
                    break;
                default:
                    break;
            }
        }
    </script>
@endpush