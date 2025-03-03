<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil total santri
        $totalSantri = DB::table('santri')->count();
    
        // Ambil tanggal hari ini, kemarin, minggu ini, dan bulan ini
        $today = Carbon::today()->toDateString(); // Format: Y-m-d
        $yesterday = Carbon::yesterday()->toDateString();
        $weekStart = Carbon::now()->startOfWeek()->toDateString();
        $monthStart = Carbon::now()->startOfMonth()->toDateString();
    
        // Fungsi untuk menghitung persentase kehadiran berdasarkan waktu shalat
        function getAttendancePercentage($dateFilter)
        {
            $waktuShalat = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
            $result = [];
    
            foreach ($waktuShalat as $waktu) {
                $totalHadir = DB::table('kehadiran')
                    ->whereDate('tanggal_waktu', '>=', $dateFilter)
                    ->where('kehadiran.waktu_shalat', $waktu) // Tentukan tabel 'kehadiran'
                    ->where('kehadiran.status', 'Hadir') // Tentukan tabel 'kehadiran'
                    ->count();
    
                $totalSantri = DB::table('santri')->count();
                $percentage = $totalSantri > 0 ? round(($totalHadir / $totalSantri) * 100) : 0;
    
                $result[] = $percentage;
            }
    
            return $result;
        }
    
        // Ambil data kehadiran berdasarkan filter waktu
        $attendanceData = [
            'today' => getAttendancePercentage($today),
            'yesterday' => getAttendancePercentage($yesterday),
            'week' => getAttendancePercentage($weekStart),
            'month' => getAttendancePercentage($monthStart)
        ];
    
        // Hitung jumlah santri yang Hadir & Tidak Hadir hari ini
        $hadirHariIni = DB::table('kehadiran')
            ->whereDate('tanggal_waktu', $today)
            ->where('kehadiran.status', 'Hadir') // Tentukan tabel 'kehadiran'
            ->count();
    
        $absenHariIni = DB::table('kehadiran')
            ->whereDate('tanggal_waktu', $today)
            ->where('kehadiran.status', 'Tidak Hadir') // Tentukan tabel 'kehadiran'
            ->count();
    
        // Hitung persentase kehadiran hari ini
        $totalKehadiran = $hadirHariIni + $absenHariIni;
        $persentaseHadir = $totalKehadiran > 0 ? round(($hadirHariIni / $totalKehadiran) * 100) : 0;
        $persentaseAbsen = $totalKehadiran > 0 ? round(($absenHariIni / $totalKehadiran) * 100) : 0;
    
        // Ambil daftar santri dengan persentase kehadiran tertinggi
        $santriTeraktif = DB::table('kehadiran')
            ->join('santri', 'kehadiran.santri_id', '=', 'santri.id')
            ->select(
                'santri.id',
                'santri.nama_lengkap as nama_santri',
                DB::raw('COUNT(CASE WHEN kehadiran.status = "Hadir" THEN 1 END) as jumlah_hadir'),
                DB::raw('COUNT(*) as total_kehadiran'),
                DB::raw('ROUND((COUNT(CASE WHEN kehadiran.status = "Hadir" THEN 1 END) / COUNT(*)) * 100, 2) as persentase_hadir')
            )
            ->groupBy('santri.id', 'santri.nama_lengkap')
            ->orderByDesc('persentase_hadir')
            ->limit(5)
            ->get();
    
        // Ambil data Hadir Hari Ini untuk ditampilkan di tabel
        $hadirHariIniData = DB::table('kehadiran')
            ->join('santri', 'kehadiran.santri_id', '=', 'santri.id')
            ->whereDate('tanggal_waktu', $today)
            ->where('kehadiran.status', 'Hadir') // Tentukan tabel 'kehadiran'
            ->select(
                'santri.nama_lengkap as nama_santri',
                'kehadiran.waktu_shalat',
                'kehadiran.jam_masuk',
                'kehadiran.jam_keluar'
            )
            ->get();
    
        // Ambil data Absen Hari Ini untuk ditampilkan di tabel
        $absenHariIniData = DB::table('kehadiran')
            ->join('santri', 'kehadiran.santri_id', '=', 'santri.id')
            ->whereDate('tanggal_waktu', $today)
            ->where('kehadiran.status', 'Tidak Hadir') // Tentukan tabel 'kehadiran'
            ->select(
                'santri.nama_lengkap as nama_santri',
                'kehadiran.waktu_shalat',
                'kehadiran.jam_masuk',
                'kehadiran.jam_keluar'
            )
            ->get();
    
        // Kirim data ke view
        return view('dashboard', compact(
            'totalSantri',
            'hadirHariIni',
            'persentaseHadir',
            'absenHariIni',
            'persentaseAbsen',
            'santriTeraktif',
            'attendanceData',
            'hadirHariIniData', // Data Hadir Hari Ini
            'absenHariIniData'  // Data Absen Hari Ini
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Santri $santri)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Santri $santri)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Santri $santri)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Santri $santri)
    {
        //
    }
}
