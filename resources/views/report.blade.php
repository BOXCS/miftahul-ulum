@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <div class="col-12">
                <h1 class="text-center text-md-start">Laporan Kehadiran</h1>
            </div>

            <!-- Export PDF Button -->
            <div class="col-12">
                <button class="btn btn-danger">
                    <i class="fas fa-file-pdf me-2"></i>Export PDF
                </button>
            </div>

            <!-- Sort Options -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label for="sortBy" class="form-label">Sort By</label>
                                <select class="form-select" id="sortBy">
                                    <option selected>Nama Santri</option>
                                    <option>Tanggal</option>
                                    <option>Total Kehadiran</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label for="sortOrder" class="form-label">Order</label>
                                <select class="form-select" id="sortOrder">
                                    <option selected>Ascending</option>
                                    <option>Descending</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <label for="dateFilter" class="form-label">Tanggal</label>
                                <input type="date" class="form-control" id="dateFilter">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DataTable -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="attendanceTable" class="table table-striped table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Santri</th>
                                        <th>Tanggal</th>
                                        <th>Subuh</th>
                                        <th>Zuhur</th>
                                        <th>Asar</th>
                                        <th>Magrib</th>
                                        <th>Isya</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Sample Data -->
                                    <tr>
                                        <td>1</td>
                                        <td>Ahmad Budiman</td>
                                        <td>2023-05-01</td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td><span class="badge bg-danger">Tidak</span></td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td>3</td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Muhammad Ali</td>
                                        <td>2023-05-01</td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td>4</td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Abdullah Rahman</td>
                                        <td>2023-05-01</td>
                                        <td><span class="badge bg-danger">Tidak</span></td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td><span class="badge bg-success">Hadir</span></td>
                                        <td><span class="badge bg-danger">Tidak</span></td>
                                        <td><span class="badge bg-danger">Tidak</span></td>
                                        <td>2</td>
                                    </tr>
                                    <!-- More rows can be added here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Rata-rata Kehadiran</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <h2 class="mb-0">75%</h2>
                            <i class="fas fa-chart-line fa-3x opacity-50"></i>
                        </div>
                        <p class="card-text mt-2">Dari total shalat wajib</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5 class="card-title">Kehadiran Tertinggi</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0">Muhammad Ali</h2>
                                <p class="mb-0">95% Kehadiran</p>
                            </div>
                            <i class="fas fa-user-graduate fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5 class="card-title">Kehadiran Terendah</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h2 class="mb-0">Abdullah Rahman</h2>
                                <p class="mb-0">45% Kehadiran</p>
                            </div>
                            <i class="fas fa-user-clock fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#attendanceTable').DataTable({
                responsive: true,
                dom: '<"top"f>rt<"bottom"lip><"clear">',
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data per halaman",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan halaman _PAGE_ dari _PAGES_",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });
    </script>
@endpush

@push('styles')
    <style>
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .badge {
            font-size: 0.85em;
            padding: 0.35em 0.65em;
        }
        
        #attendanceTable th {
            background-color: #f8f9fa; 
            font-weight: 600;
        }
        
        .dataTables_wrapper .dataTables_filter input {
            border-radius: 5px;
            border: 1px solid #dee2e6;
            padding: 0.375rem 0.75rem;
        }
    </style>
@endpush