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
                    Manajemen Data <span class="subTitle">Perizinan</span>
                </h1>
            </div>

            <div class="controls">
                <div class="row g-3 align-items-center justify-content-between">
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" id="add-button">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Izin Santri
                        </button>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group p-0">
                            <input type="text" class="form-control" id="myInput" placeholder="Cari...">
                            <span class="input-group-text">
                                <i class="bi bi-search"></i>
                            </span>
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
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.2/js/dataTables.bootstrap5.js"></script>
    <script>
        tableObj = new DataTable('#datatable', {
            // get data dari variabel laravel
            data: {!! json_encode($perizinan->toArray()) !!},
            // membuat tabel dengan title=judul column dan data=data yg ditampilkan
            // yang langsung terhubung dengan option data/statement diatas
            columns: [
                { title: 'ID', data: 'id_izin' },
                { title: 'Nama Santri', data: 'santri.nama' },
                { title: 'Izin Tanggal', data: 'waktu' },
                { title: 'Jenis Izin', data: 'jenis_izin' },
                { title: 'Keterangan', data: 'keterangan' },
                { title: 'Aksi', data: 'id_izin', width: '200px',
                    
                    // add button edit and delete
                    render: function (data, type, row) {
                        const test = `
                            <a href="{{ route('perizinan.edit', ['perizinan' => '__ID__']) }}" class="btn btn-primary">Edit</a>
                            <a type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal${data}">Hapus</a>
                            <div class="modal fade" id="exampleModal${data}" tabindex="-1" aria-labelledby="exampleModal__ID__" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Konfirmasi Hapus</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p style="text-align=left">Apakah anda ingin menghapus perizinan santri yang bernama <span class="fw-bold">${row.santri.nama}</span>?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form action="/perizinan/${data}" method="POST">
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
                { targets: 4, orderable: false, searchable: false },
                { targets: 5, orderable: false, searchable: false, className: "dt-center" },
            ],
            order: [[2, 'desc']]
        });
        document.getElementById('myInput').addEventListener('keyup', function () {
            tableObj.search(this.value).draw();
        });
        document.getElementById('add-button').addEventListener('click', function() { window.location.href = '{{ route("perizinan.create") }}'; });
    </script>
@endpush