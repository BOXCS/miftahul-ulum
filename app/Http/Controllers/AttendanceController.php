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

        // Hitung persentase kehadiran berdasarkan filter
        foreach ($waktuShalat as $shalat) {
            $query = DB::table('kehadiran')
                ->where('waktu_shalat', $shalat);

            // Filter berdasarkan tanggal
            switch ($filter) {
                case 'today':
                    $query->whereDate('tanggal_waktu', Carbon::today());
                    break;
                case 'yesterday':
                    $query->whereDate('tanggal_waktu', Carbon::yesterday());
                    break;
                case 'week':
                    $query->whereBetween('tanggal_waktu', [
                        Carbon::now()->startOfWeek(),
                        Carbon::now()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('tanggal_waktu', Carbon::now()->month);
                    break;
                default:
                    // Jika filter tidak valid, kembalikan array kosong
                    return response()->json([]);
            }

            // Hitung total kehadiran dan yang hadir
            $total = $query->count();
            $hadir = $query->where('status', 'Hadir')->count();

            // Hitung persentase kehadiran
            $persentase = ($total > 0) ? round(($hadir / $total) * 100, 2) : 0;
            $data[] = $persentase;
        }

        return response()->json($data);
    }
}