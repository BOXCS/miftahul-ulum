@extends('layouts.app')

@section('content')
<div class="container-fluid px-3">
    <div class="row g-4">
        <div class="col-12">
            <h1 class="text-center text-md-start">Dashboard</h1>
        </div>

        <!-- Statistik Kehadiran -->
        <div class="col-lg-8">
            <div class="row g-3">
                <div class="col-md-4 col-6">
                    <div class="card shadow-sm p-3 text-center" style="background-color: #E8F4F8; color: black;">
                        <p class="text-muted">Total Santri</p>
                        <h2 class="fw-bold">{{ $totalSantri }}</h2>
                    </div>
                </div>
                <div class="col-md-4 col-6">
                    <div class="card bg-success text-white shadow-sm p-3 text-center">
                        <p>Hadir Hari Ini</p>
                        <h2 class="fw-bold">{{ $hadirHariIni }} ({{ $persentaseHadir }}%) ✅</h2>
                    </div>
                </div>
                <div class="col-md-4 col-6 mx-auto">
                    <div class="card bg-danger text-white shadow-sm p-3 text-center">
                        <p>Absen</p>
                        <h2 class="fw-bold">{{ $absenHariIni }} ({{ $persentaseAbsen }}%) ❌</h2>
                    </div>
                </div>
                
            </div>

            <!-- Grafik Kehadiran -->
            <div class="container mt-4 p-4 text-black rounded-3" style="background-color: #EFF0F5;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Persentase Kehadiran</h5>
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            Hari Ini
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                            <li><a class="dropdown-item" href="#" data-filter="today">Hari Ini</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="yesterday">Kemarin</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="week">Minggu Ini</a></li>
                            <li><a class="dropdown-item" href="#" data-filter="month">Bulan Ini</a></li>
                        </ul>
                    </div>
                </div>
                <hr class="mt-2 mb-4 border-light"> <!-- Garis dengan warna lebih soft -->
                <canvas id="chartAttendance"></canvas>
            </div>
            
        </div>

        <!-- Jadwal Shalat & Santri Teraktif -->
        <div class="col-lg-4">
            <div class="row g-3">
                <div class="col-12">
                    <div class="card shadow-sm p-3">
                        <h5 class="fw-bold text-center text-md-start">Jadwal Shalat Hari Ini</h5>
                        <div id="prayer-times" class="d-flex flex-column gap-2"></div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card shadow-sm p-3">
                        <h5 class="fw-bold text-center text-md-start">Santri Teraktif</h5>
                        <ul class="list-group list-group-flush">
                            @foreach ($santriTeraktif as $index => $santri)
                                <li class="list-group-item">
                                    {{ $index + 1 }}. {{ $santri->nama_santri }} - {{ $santri->persentase_hadir }}%
                                </li>
                            @endforeach
                        </ul>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="{{ asset('js/chart-attendance.js') }}"></script>
    <script src="{{ asset('js/prayer-times.js') }}"></script>
@endpush
@endsection
