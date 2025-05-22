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
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $idSantri = $request->input('santri');
        $waktuShalat = $request->input('shalat');

        $query = DB::table('santri')
            ->leftJoin('kehadiran', 'santri.id_santri', '=', 'kehadiran.id_santri')
            ->select(
                'santri.id_santri',
                'santri.nama',
                'kehadiran.waktu',
                'kehadiran.waktu_shalat',
                'kehadiran.jam_masuk',
                'kehadiran.jam_keluar'
            );

        if ($bulan) {
            $query->whereMonth('kehadiran.waktu', $bulan);
        }

        if ($tahun) {
            $query->whereYear('kehadiran.waktu', $tahun);
        }

        if ($idSantri) {
            $query->where('santri.id_santri', $idSantri);
        }

        if ($waktuShalat) {
            $query->where('kehadiran.waktu_shalat', $waktuShalat);
        }

        $data = $query->get();
        $semuaKehadiran = $data;

        // Group data by santri and date
        $dataKehadiran = $data->groupBy([
            'id_santri',
            function ($item) {
                return Carbon::parse($item->waktu)->format('Y-m-d');
            }
        ]);

        $listSantri = DB::table('santri')->select('id_santri', 'nama')->get();

        // Hitung statistik:
        // Inisialisasi array statistik kehadiran per santri
        $statKehadiran = [];

        foreach ($dataKehadiran as $idSantri => $byTanggal) {
            $totalHari = count($byTanggal); // hari ada data
            $totalHadir = 0;
            $totalShalat = 0;

            foreach ($byTanggal as $tanggal => $records) {
                // Asumsikan max 5 shalat per hari
                $hadirPerHari = 0;
                foreach ($records as $r) {
                    if ($r->jam_masuk) {
                        $hadirPerHari++;
                    }
                    $totalShalat++;
                }
                $totalHadir += $hadirPerHari;
            }

            // Hindari pembagian 0
            $persentase = $totalShalat > 0 ? ($totalHadir / $totalShalat) * 100 : 0;

            $statKehadiran[$idSantri] = [
                'nama' => $byTanggal->first()->first()->nama ?? 'Unknown',
                'persen' => round($persentase, 2),
            ];
        }

        // Hitung rata-rata, tertinggi dan terendah
        if (count($statKehadiran) > 0) {
            $rataRata = round(collect($statKehadiran)->avg('persen'), 2);

            $tertinggi = collect($statKehadiran)->sortByDesc('persen')->first();
            $terendah = collect($statKehadiran)->sortBy('persen')->first();
        } else {
            $rataRata = 0;
            $tertinggi = ['nama' => '-', 'persen' => 0];
            $terendah = ['nama' => '-', 'persen' => 0];
        }

        return view('attendance', [
            'dataKehadiran' => $dataKehadiran,
            'listSantri' => $listSantri,
            'rataRataKehadiran' => $rataRata,
            'kehadiranTertinggi' => $tertinggi,
            'kehadiranTerendah' => $terendah,
            'semuaKehadiran' => $semuaKehadiran,
        ]);
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

    public function exportToPdf(Request $request)
    {
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');
        $idSantri = $request->input('santri');
        $waktuShalat = $request->input('shalat');

        $query = DB::table('santri')
            ->leftJoin('kehadiran', 'santri.id_santri', '=', 'kehadiran.id_santri')
            ->select(
                'santri.id_santri',
                'santri.nama',
                'kehadiran.waktu',
                'kehadiran.waktu_shalat',
                'kehadiran.jam_masuk',
                'kehadiran.jam_keluar'
            );

        if ($bulan) {
            $query->whereMonth('kehadiran.waktu', $bulan);
        }

        if ($tahun) {
            $query->whereYear('kehadiran.waktu', $tahun);
        }

        if ($idSantri) {
            $query->where('santri.id_santri', $idSantri);
        }

        if ($waktuShalat) {
            $query->where('kehadiran.waktu_shalat', $waktuShalat);
        }

        $data = $query->get();

        $dataKehadiran = $data->groupBy([
            'id_santri',
            function ($item) {
                return Carbon::parse($item->waktu)->format('Y-m-d');
            }
        ]);

        // Statistik kehadiran
        $statKehadiran = [];
        foreach ($dataKehadiran as $idSantri => $byTanggal) {
            $totalHari = count($byTanggal);
            $totalHadir = 0;
            $totalShalat = 0;

            foreach ($byTanggal as $tanggal => $records) {
                $hadirPerHari = 0;
                foreach ($records as $r) {
                    if ($r->jam_masuk) {
                        $hadirPerHari++;
                    }
                    $totalShalat++;
                }
                $totalHadir += $hadirPerHari;
            }

            $persentase = $totalShalat > 0 ? ($totalHadir / $totalShalat) * 100 : 0;

            $statKehadiran[$idSantri] = [
                'nama' => $byTanggal->first()->first()->nama ?? 'Unknown',
                'persen' => round($persentase, 2),
            ];
        }

        $rataRata = $statKehadiran ? round(collect($statKehadiran)->avg('persen'), 2) : 0;
        $tertinggi = $statKehadiran ? collect($statKehadiran)->sortByDesc('persen')->first() : ['nama' => '-', 'persen' => 0];
        $terendah = $statKehadiran ? collect($statKehadiran)->sortBy('persen')->first() : ['nama' => '-', 'persen' => 0];

        $html = view('attendance_export', [
            'dataKehadiran' => $dataKehadiran,
            'rataRataKehadiran' => $rataRata,
            'kehadiranTertinggi' => $tertinggi,
            'kehadiranTerendah' => $terendah,
        ])->render();

        $mpdf = new Mpdf([
            'format' => 'A4-L', // landscape
        ]);

        $mpdf->WriteHTML($html);
        $filename = 'Laporan-Kehadiran-' . now()->format('Ymd_His') . '.pdf';
        return $mpdf->Output($filename, \Mpdf\Output\Destination::DOWNLOAD);
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
