<?php

namespace App\Http\Controllers;

use App\Models\Kehadiran;
use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Mpdf\Mpdf;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua santri dan kehadiran mereka dikelompokkan berdasarkan tanggal
        $dataKehadiran = DB::table('santri')
            ->leftJoin('kehadiran', 'santri.id_santri', '=', 'kehadiran.id_santri')
            ->select(
                'santri.id_santri',
                'santri.nama',
                'kehadiran.waktu',
                'kehadiran.waktu_shalat',
                'kehadiran.jam_masuk',
                'kehadiran.jam_keluar'
            )
            ->get()
            ->groupBy(['id_santri', 'waktu']);

        return view('attendance', compact('dataKehadiran'));
    }

    private function getStatus($jamMasuk, $waktuShalat)
    {
        if (!$jamMasuk) return 'Tidak Hadir';

        $waktuAdzan = match ($waktuShalat) {
            'Subuh' => Carbon::parse('05:00'),
            'Dzuhur' => Carbon::parse('12:00'),
            'Ashar' => Carbon::parse('15:00'),
            'Maghrib' => Carbon::parse('18:00'),
            'Isya' => Carbon::parse('19:30'),
            default => Carbon::parse('00:00'),
        };

        $jamMasukCarbon = Carbon::parse($jamMasuk);

        return $jamMasukCarbon->lte($waktuAdzan->addMinutes(5)) ? 'Hadir' : 'Terlambat';
    }

    public function export(Request $request)
    {
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));
        $santriFilter = $request->input('santri');
        $shalatFilter = $request->input('waktu_shalat');

        $query = Kehadiran::with('santri')
            ->whereMonth('waktu', $bulan)
            ->whereYear('waktu', $tahun);

        if ($santriFilter) {
            $query->where('id_santri', $santriFilter);
        }

        if ($shalatFilter) {
            $query->where('waktu_shalat', $shalatFilter);
        }

        $kehadiran = $query->get()
            ->groupBy('id_santri')
            ->map(function ($items) {
                return $items->groupBy(fn($item) => Carbon::parse($item->waktu)->format('Y-m-d'));
            });

        $laporan = [];
        $santriList = Santri::whereIn('id', $kehadiran->keys())->get()->keyBy('id');

        foreach ($kehadiran as $idSantri => $tanggalGroup) {
            $santri = $santriList->get($idSantri);
            foreach ($tanggalGroup as $tanggal => $data) {
                $dataShalat = collect(['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'])
                    ->mapWithKeys(fn($w) => [$w => null])
                    ->toArray();

                foreach ($data as $khd) {
                    $status = $this->getStatus($khd->jam_masuk, $khd->waktu_shalat);
                    $dataShalat[$khd->waktu_shalat] = $status;
                }

                $jumlahHadir = collect($dataShalat)
                    ->filter(fn($s) => in_array($s, ['Hadir', 'Terlambat']))
                    ->count();

                $laporan[] = [
                    'nama' => $santri->nama ?? 'Tidak Ditemukan',
                    'tanggal' => $tanggal,
                    'shalat' => $dataShalat,
                    'total' => "{$jumlahHadir}/5",
                ];
            }
        }

        $html = view('attendance.export', compact('laporan'))->render();
        $mpdf = new Mpdf();
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('laporan-kehadiran.pdf', 'S'))
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="laporan-kehadiran.pdf"');
    }

    public function getAttendanceData(Request $request)
{
    $filter = $request->query('filter');
    $waktuShalat = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
    $data = [];

    $totalSantri = Santri::where('status', 'aktif')->count();
    if ($totalSantri === 0) {
        // Jika tidak ada santri aktif, kehadiran dan ketidakhadiran = 0 semua
        return response()->json([
            'present' => array_fill(0, count($waktuShalat), 0),
            'absent' => array_fill(0, count($waktuShalat), 0),
        ]);
    }

    foreach ($waktuShalat as $shalat) {
        $query = Kehadiran::where('waktu_shalat', $shalat);

        match ($filter) {
            'today' => $query->whereDate('waktu', Carbon::today()),
            'yesterday' => $query->whereDate('waktu', Carbon::yesterday()),
            'week' => $query->whereBetween('waktu', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek(),
            ]),
            'month' => $query->whereMonth('waktu', Carbon::now()->month),
            default => null,
        };

        $totalHadir = $query->whereNotNull('jam_masuk')->count();
        $persentase = round(($totalHadir / $totalSantri) * 100, 2);
        $data[] = $persentase;
    }

    // Hitung absent dari 100 - hadir
    return response()->json([
        'present' => $data,
        'absent' => array_map(fn($p) => round(100 - $p, 2), $data),
    ]);
}

}
