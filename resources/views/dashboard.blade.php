@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row g-4">
            <h1>Dashboard</h1>

            <!-- Statistik & Grafik Kehadiran -->
            <div class="col-md-8">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card shadow-sm p-3" style="background-color: #E8F4F8; color: black;">
                            <p class="text-muted">Total Santri</p>
                            <h2 class="fw-bold">350</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white shadow-sm p-3">
                            <p>Hadir Hari Ini</p>
                            <h2 class="fw-bold">325 (93%) ✅</h2>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-danger text-white shadow-sm p-3">
                            <p>Absen</p>
                            <h2 class="fw-bold">25 (7%) ❌</h2>
                        </div>
                    </div>
                </div>

                <!-- Grafik Statistik Kehadiran -->
                <div class="card shadow-sm p-3 mt-3">
                    <h5 class="fw-bold">Statistik Kehadiran Mingguan</h5>
                    <canvas id="chartAttendance"></canvas>
                </div>
            </div>

            <!-- Jadwal Shalat & Santri Teraktif -->
            <div class="col-md-4">
                <div class="row g-3 h-90">
                    <div class="col-15">
                        <div class="card shadow-sm p-3 h-90">
                            <h5 class="fw-bold">Jadwal Shalat Hari Ini</h5>
                            <div id="prayer-times" class="d-flex flex-column gap-2"></div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card shadow-sm p-3 h-100">
                            <h5 class="fw-bold">Santri Teraktif</h5>
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">1. Ahmad Fauzi - 99%</li>
                                <li class="list-group-item">2. Zaki Alamsyah - 98%</li>
                                <li class="list-group-item">3. Rizky Maulana - 96%</li>
                                <li class="list-group-item">4. Budi Santoso - 95%</li>
                                <li class="list-group-item">5. Farhan Hidayat - 94%</li>
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
