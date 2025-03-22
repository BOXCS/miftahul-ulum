<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function getAttendanceData(Request $request)
    {
        $filter = $request->query('filter');
        $waktuShalat = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
        $data = [];

        // Ambil total santri aktif
        $totalSantri = DB::table('santri')->where('status', 'aktif')->count();

        // Jika tidak ada santri, langsung return data 0%
        if ($totalSantri === 0) {
            return response()->json(array_fill(0, count($waktuShalat), 0));
        }

        foreach ($waktuShalat as $shalat) {
            $query = DB::table('kehadiran')->where('waktu_shalat', $shalat);

            // Filter berdasarkan tanggal
            switch ($filter) {
                case 'today':
                    $query->whereDate('waktu', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('waktu', Carbon::yesterday());
                    break;
                case 'week':
                    $query->whereBetween('waktu', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('waktu', Carbon::now()->month);
                    break;
                default:
                    return response()->json([]);
            }

            // Hitung total kehadiran pada waktu shalat tertentu
            $totalHadir = $query->whereNotNull('jam_masuk')->count();

            // Hitung persentase dengan total santri
            $persentase = round(($totalHadir / ($totalSantri * 5)) * 100, 2);

            $data[] = $persentase;
        }

        return response()->json($data);
    }
}
