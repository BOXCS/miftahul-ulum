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
        <table class="table table-bordered text-center bg-white">
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
                <tr>
                    <td>1</td>
                    <td>Ahmad Fauzi</td>
                    <td>2024-02-20</td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-warning text-dark">Terlambat</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td>4/5</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>Budi Santoso</td>
                    <td>2024-02-20</td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-warning text-dark">Terlambat</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td>4/5</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>Cahya Wijaya</td>
                    <td>2024-02-20</td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-warning text-dark">Terlambat</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td>4/5</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>Deni Rahman</td>
                    <td>2024-02-20</td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-warning text-dark">Terlambat</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td>4/5</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>Eko Prasetyo</td>
                    <td>2024-02-20</td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-warning text-dark">Terlambat</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td><span class="badge bg-success">Hadir</span></td>
                    <td>4/5</td>
                </tr>
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
