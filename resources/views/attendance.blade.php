@extends('layouts.app')

@section('content')
    <div class="container-fluid px-3">
        <div class="row g-4">
            <div class="col-12">
                <h1 class="text-center text-md-start">Laporan Kehadiran</h1>
            </div>

            <div class="d-flex gap-2 flex-wrap mb-3">
                <select class="form-select w-auto">
                    <option selected>Januari</option>
                    <option>Februari</option>
                    <option>Maret</option>
                </select>
                <select class="form-select w-auto">
                    <option>2022</option>
                    <option selected>2023</option>
                    <option>2024</option>
                </select>
                <select class="form-select w-auto">
                    <option selected>Semua Santri</option>
                </select>
                <select class="form-select w-auto">
                    <option selected>Semua Waktu Salat</option>
                </select>
                <button class="btn btn-success ms-auto">Export Data 📄</button>
            </div>

            <div class="table-responsive mt-3">
                <table id="tabelKehadiran" class="table table-bordered text-center bg-white">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Santri</th>
                            <th>Tanggal</th>
                            <th>Subuh</th>
                            <th>Zuhur</th>
                            <th>Asar</th>
                            <th>Maghrib</th>
                            <th>Isya</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($dataKehadiran as $id_santri => $byTanggal)
                            @foreach($byTanggal as $tanggal => $records)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>{{ $records->first()->nama }}</td>
                                    <td>{{ $tanggal }}</td>
                                    @php
                                        $shalatMap = ['Subuh' => null, 'Dzuhur' => null, 'Ashar' => null, 'Maghrib' => null, 'Isya' => null];
                                        $total = 0;
                                        foreach($records as $r) {
                                            $shalatMap[$r->waktu_shalat] = $r;
                                        }
                                    @endphp
                    
                                    @foreach(['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'] as $sholat)
                                        @php
                                            $entry = $shalatMap[$sholat];
                                        @endphp
                                        <td>
                                            @if ($entry && $entry->jam_masuk)
                                                @php $total++; @endphp
                                                <span class="badge bg-success">Hadir</span>
                                            @else
                                                <span class="badge bg-danger">Tidak Hadir</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td>{{ $total }}/5</td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
            

            
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="card p-3 text-center h-100">
                        <h5>Rata-rata Kehadiran</h5>
                        <h2 class="text-success">85%</h2>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card p-3 text-center h-100">
                        <h5>Kehadiran Tertinggi</h5>
                        <h6>Ahmad Fauzi</h6>
                        <h2 class="text-success">95%</h2>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card p-3 text-center h-100">
                        <h5>Kehadiran Terendah</h5>
                        <h6>Eko Prasetyo</h6>
                        <h2 class="text-danger">65%</h2>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @push('scripts')
    <script>
        $(document).ready(function() {
            $('#tabelKehadiran').DataTable({
                "pageLength": 12,
                "lengthChange": false, // tidak bisa ganti jumlah per halaman
                "ordering": false,     // nonaktifkan sorting kolom (opsional)
                "language": {
                    "search": "Cari:",
                    "paginate": {
                        "first": "Pertama",
                        "last": "Terakhir",
                        "next": "Berikutnya",
                        "previous": "Sebelumnya"
                    },
                    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                }
            });
        });
        </script>
        
    @endpush