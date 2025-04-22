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
                <option value="santri">santri</option>
                <option value="ortu">Orang tua</option>
                <option value="guru">Guru</option>
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
                        data: {!! json_encode($Santris->toArray()) !!},
                        columns: [
                            { title: 'ID'},
                            { title: 'Nama Lengkap'},
                            { title: 'Orang Tua'},
                            { title: 'Sidik Jari'},
                            { title: 'Status'},
                            { title: 'Aksi'},
                        ]
                    });
                    break;
                case "ortu":
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($Santris->values()->toArray()) !!},
                        columns: [
                            { title: 'ID'},
                            { title: 'Nama Orang Tua'},
                            { title: 'Alamat'},
                            { title: 'Email'},
                            { title: 'No. Telp'},
                            { title: 'Nama Santri'},
                            { title: 'Aksi'},
                        ],
                        columnDefs: [
                            { targets: 3, orderable: false, searchable: false },
                            { targets: 5, orderable: false, searchable: false },
                        ]
                    });
                    break;
                case 'guru':
                    tableObj = new DataTable('#datatable', {
                        data: {!! json_encode($Santris->toArray()) !!},
                        columns: [
                            { title: 'ID'},
                            { title: 'Nama Lengkap'},
                            { title: 'Alamat'},
                            { title: 'Email'},
                            { title: 'No. Telp'},
                            { title: 'Jabatan'},
                            { title: 'Aksi'},
                        ]
                    });
                    break;
                default:
                    break;
            }
        }
        tableObj = new DataTable('#datatable', {
            data: {!! json_encode($Santris->values()->toArray()) !!},
            columns: [
                { title: 'ID'},
                { title: 'Nama Lengkap'},
                { title: 'Orang Tua'},
                { title: 'Sidik Jari'},
                { title: 'Status'},
                { title: 'Aksi'},
            ],
            columnDefs: [
                { targets: 3, orderable: false, searchable: false },
                { targets: 5, orderable: false, searchable: false },
            ]
        });
    </script>
@endpush