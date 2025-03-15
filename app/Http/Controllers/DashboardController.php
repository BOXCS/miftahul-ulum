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
        // Total Santri Aktif
        $totalSantri = DB::table('santri')->where('status', 'aktif')->count();

        // Tanggal hari ini, kemarin, minggu ini, dan bulan ini
        $today = Carbon::today()->toDateString();
        $yesterday = Carbon::yesterday()->toDateString();
        $weekStart = Carbon::now()->startOfWeek()->toDateString();
        $monthStart = Carbon::now()->startOfMonth()->toDateString();

        // Fungsi untuk menghitung persentase kehadiran
        function getAttendancePercentage($dateFilter)
        {
            $waktuShalat = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
            $result = [];

            foreach ($waktuShalat as $waktu) {
                $totalHadir = DB::table('kehadiran')
                    ->whereDate('waktu', '>=', $dateFilter)
                    ->where('waktu_shalat', $waktu)
                    ->whereNotNull('jam_masuk') // Hadir jika ada jam masuk
                    ->count();

                $totalSantri = DB::table('santri')->where('status', 'aktif')->count();
                $percentage = $totalSantri > 0 ? round(($totalHadir / $totalSantri) * 100) : 0;

                $result[] = $percentage;
            }

            return $result;
        }

        // Data persentase kehadiran
        $attendanceData = [
            'today' => getAttendancePercentage($today),
            'yesterday' => getAttendancePercentage($yesterday),
            'week' => getAttendancePercentage($weekStart),
            'month' => getAttendancePercentage($monthStart)
        ];

        // Hitung jumlah santri Hadir & Tidak Hadir hari ini
        $hadirHariIni = DB::table('kehadiran')
            ->whereDate('waktu', $today)
            ->whereNotNull('jam_masuk') // Hadir jika ada jam masuk
            ->count();

        $absenHariIni = $totalSantri - $hadirHariIni; // Absen = Total Santri - Hadir

        // Hitung persentase kehadiran hari ini
        $persentaseHadir = $totalSantri > 0 ? round(($hadirHariIni / $totalSantri) * 100) : 0;
        $persentaseAbsen = $totalSantri > 0 ? round(($absenHariIni / $totalSantri) * 100) : 0;

        // Santri Teraktif (Paling Sering Hadir)
        $santriTeraktif = DB::table('kehadiran')
            ->join('santri', 'kehadiran.id_santri', '=', 'santri.id_santri')
            ->select(
                'santri.id_santri',
                'santri.nama',
                DB::raw('COUNT(kehadiran.id_kehadiran) as jumlah_hadir'),
                DB::raw('ROUND((COUNT(kehadiran.id_kehadiran) / (SELECT COUNT(*) FROM kehadiran)) * 100, 2) as persentase_hadir')
            )
            ->groupBy('santri.id_santri', 'santri.nama')
            ->orderByDesc('persentase_hadir')
            ->limit(5)
            ->get();

        // Data Hadir Hari Ini
        $hadirHariIniData = DB::table('kehadiran')
            ->join('santri', 'kehadiran.id_santri', '=', 'santri.id_santri')
            ->whereDate('waktu', $today)
            ->whereNotNull('jam_masuk') // Hadir jika ada jam masuk
            ->select(
                'santri.nama',
                'kehadiran.waktu_shalat',
                'kehadiran.jam_masuk',
                'kehadiran.jam_keluar'
            )
            ->get();

        // Data Absen Hari Ini
        $absenHariIniData = DB::table('santri')
            ->leftJoin('kehadiran', function ($join) use ($today) {
                $join->on('santri.id_santri', '=', 'kehadiran.id_santri')
                    ->whereDate('kehadiran.waktu', $today);
            })
            ->where('santri.status', 'aktif')
            ->whereNull('kehadiran.jam_masuk') // Tidak hadir jika tidak ada jam masuk
            ->select(
                'santri.nama'
            )
            ->get();

        // Data Perizinan Hari Ini
        $izinHariIniData = DB::table('perizinan')
            ->join('santri', 'perizinan.id_izin', '=', 'santri.id_izin')
            ->whereDate('perizinan.waktu', $today)
            ->select(
                'santri.nama',
                'perizinan.jenis_izin',
                'perizinan.keterangan'
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
            'hadirHariIniData',
            'absenHariIniData',
            'izinHariIniData' // Data perizinan hari ini
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
