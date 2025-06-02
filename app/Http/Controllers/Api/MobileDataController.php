<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use App\Models\Kehadiran;
use App\Models\OrangTua;
use App\Models\Pengumuman;
use App\Models\Perizinan;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class MobileDataController extends Controller
{

    // untuk mengambil data Kehadiran seorang santri seminggu, sebulan, dan setahun terakhir
    public function kehadiranByIdByTime(string $id)
    {
        $santri = Santri::find($id)->nama;
        // tanggal hari ini
        $endDate = Carbon::today()->toDateString();
        // total kehadiran Mingguan
        $seminggu = Carbon::today()->subDays(7)->toDateString();     // 7 hari terakhir (termasuk hari ini)
        $jumlahKehadiranMingguan = Kehadiran::with("santri")->select('id_santri', DB::raw('COUNT(*) as jumlah_kehadiran'))->whereBetween('waktu', [$seminggu, $endDate])->where('id_santri', $id)->groupBy('id_santri')->first();
        // total kehadiran Bulanan
        $sebulan = Carbon::today()->subMonth()->toDateString();     // 1 bulan terakhir
        $jumlahKehadiranBulanan = Kehadiran::select('id_santri', DB::raw('COUNT(*) as jumlah_kehadiran'))->whereBetween('waktu', [$sebulan, $endDate])->where('id_santri', $id)->groupBy('id_santri')->first();
        // total kehadiran Tahunan
        $setahun = Carbon::today()->subYear()->toDateString();      // 1 tahun terakhir
        $jumlahKehadiranTahunan = Kehadiran::select('id_santri', DB::raw('COUNT(*) as jumlah_kehadiran'))->whereBetween('waktu', [$setahun, $endDate])->where('id_santri', $id)->groupBy('id_santri')->first();
        $data = [
            "jumlah kehadiran seminggu" => $jumlahKehadiranMingguan["jumlah_kehadiran"],
            "Jumlah kehadiran mingguan seharusnya" => date_diff(date_create($seminggu), date_create($endDate))->days * 5,
            "jumlah kehadiran sebulan" => $jumlahKehadiranBulanan["jumlah_kehadiran"],
            "Jumlah kehadiran bulan seharusnya" => date_diff(date_create($sebulan), date_create($endDate))->days * 5,
            "jumlah kehadiran setahun" => $jumlahKehadiranTahunan["jumlah_kehadiran"],
            "Jumlah kehadiran tahunan seharusnya" => date_diff(date_create($setahun), date_create($endDate))->days * 5
        ];
        return new AllResource(true, "data kehadiran $santri", $data);
    }

    // untuk mengambil data santri menggunakan id
    public function dataOrtuSantriById(string $id)
    {
        try {
            $ortu = OrangTua::with('santri')->find($id);

            if (!$ortu) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data orang tua tidak ditemukan'
                ], 404);
            }

            // Hanya mengembalikan data santri saja, tanpa data orang tua
            $santriData = $ortu->santri->map(function ($santri) {
                return [
                    'id_santri' => $santri->id_santri,
                    'nama' => $santri->nama,
                    'tahun_angkatan' => $santri->tahun_angkatan,
                    'sidik_jari' => $santri->sidik_jari,
                    'status' => $santri->status,
                    'id_ortu' => $santri->id_ortu,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => "Data santri berhasil diambil",
                'data' => $santriData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data santri',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getSantriByOrtu(string $id)
    {
        $santri = Santri::where('id_ortu', $id)->get();

        if ($santri->isEmpty()) {
            return new AllResource(false, "Tidak ada santri yang terkait dengan orang tua ini", null);
        }

        return new AllResource(true, "Data santri berdasarkan orang tua", $santri);
    }
    // kehadiran harian seminggu terakhir
    public function kehadiranSeminggu(string $id)
    {
        $startDate = Carbon::today()->subDays(6); // 7 hari terakhir termasuk hari ini
        $endDate = Carbon::today();

        // Ambil nama santri
        $santri = Santri::find($id);
        if (!$santri) {
            return new AllResource(false, "Santri tidak ditemukan", null);
        }

        // Query data kehadiran dengan jam masuk dan keluar per sholat
        $kehadiran = Kehadiran::select(
            'waktu as tanggal',
            DB::raw('COUNT(*) as jumlah_kehadiran'),
            // Status kehadiran per sholat (1 jika hadir, 0 jika tidak)
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Subuh' THEN 1 ELSE 0 END) as Subuh"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Dzuhur' THEN 1 ELSE 0 END) as Dzuhur"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Ashar' THEN 1 ELSE 0 END) as Ashar"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Maghrib' THEN 1 ELSE 0 END) as Maghrib"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Isya' THEN 1 ELSE 0 END) as Isya"),
            // Jam masuk per sholat
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Subuh' THEN jam_masuk END) as jam_masuk_subuh"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Dzuhur' THEN jam_masuk END) as jam_masuk_dzuhur"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Ashar' THEN jam_masuk END) as jam_masuk_ashar"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Maghrib' THEN jam_masuk END) as jam_masuk_maghrib"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Isya' THEN jam_masuk END) as jam_masuk_isya"),
            // Jam keluar per sholat
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Subuh' THEN jam_keluar END) as jam_keluar_subuh"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Dzuhur' THEN jam_keluar END) as jam_keluar_dzuhur"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Ashar' THEN jam_keluar END) as jam_keluar_ashar"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Maghrib' THEN jam_keluar END) as jam_keluar_maghrib"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Isya' THEN jam_keluar END) as jam_keluar_isya")
        )
            ->whereBetween('waktu', [$startDate, $endDate])
            ->where('id_santri', $id)
            ->groupBy('waktu')
            ->orderBy('waktu', 'asc')
            ->get();

        // Buat array untuk semua tanggal dalam 7 hari terakhir
        $allDates = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->toDateString();
            $allDates[$date] = [
                'tanggal' => $date,
                'jumlah_kehadiran' => 0,
                'Subuh' => 0,
                'Dzuhur' => 0,
                'Ashar' => 0,
                'Maghrib' => 0,
                'Isya' => 0,
                'jam_masuk_subuh' => null,
                'jam_masuk_dzuhur' => null,
                'jam_masuk_ashar' => null,
                'jam_masuk_maghrib' => null,
                'jam_masuk_isya' => null,
                'jam_keluar_subuh' => null,
                'jam_keluar_dzuhur' => null,
                'jam_keluar_ashar' => null,
                'jam_keluar_maghrib' => null,
                'jam_keluar_isya' => null,
            ];
        }

        // Merge data kehadiran dengan semua tanggal
        foreach ($kehadiran as $item) {
            if (isset($allDates[$item->tanggal])) {
                $allDates[$item->tanggal] = [
                    'tanggal' => $item->tanggal,
                    'jumlah_kehadiran' => $item->jumlah_kehadiran,
                    'Subuh' => $item->Subuh,
                    'Dzuhur' => $item->Dzuhur,
                    'Ashar' => $item->Ashar,
                    'Maghrib' => $item->Maghrib,
                    'Isya' => $item->Isya,
                    'jam_masuk_subuh' => $item->jam_masuk_subuh,
                    'jam_masuk_dzuhur' => $item->jam_masuk_dzuhur,
                    'jam_masuk_ashar' => $item->jam_masuk_ashar,
                    'jam_masuk_maghrib' => $item->jam_masuk_maghrib,
                    'jam_masuk_isya' => $item->jam_masuk_isya,
                    'jam_keluar_subuh' => $item->jam_keluar_subuh,
                    'jam_keluar_dzuhur' => $item->jam_keluar_dzuhur,
                    'jam_keluar_ashar' => $item->jam_keluar_ashar,
                    'jam_keluar_maghrib' => $item->jam_keluar_maghrib,
                    'jam_keluar_isya' => $item->jam_keluar_isya,
                ];
            }
        }

        // Convert ke array dan urutkan berdasarkan tanggal
        $result = array_values($allDates);

        return new AllResource(true, "Data kehadiran mingguan {$santri->nama}", $result);
    }

    public function kehadiranPeriode(string $id, string $periode = 'seminggu')
    {
        switch ($periode) {
            case 'sebulan':
                $startDate = Carbon::today()->subDays(29)->startOfDay(); // 30 hari terakhir
                $days = 30;
                break;
            case 'setahun':
                return $this->kehadiranTahunanPerBulan($id);
            case 'seminggu':
            default:
                $startDate = Carbon::today()->subDays(6)->startOfDay(); // 7 hari terakhir
                $days = 7;
                break;
        }

        $endDate = Carbon::today()->endOfDay();

        $santri = Santri::find($id);
        if (!$santri) {
            return new AllResource(false, "Santri tidak ditemukan", null);
        }

        $kehadiran = Kehadiran::select(
            DB::raw('DATE(waktu) as tanggal'), // group by tanggal saja tanpa waktu
            DB::raw('COUNT(DISTINCT waktu_shalat) as jumlah_kehadiran'), // jumlah waktu_shalat unik
            // Status kehadiran per sholat (ada data atau tidak)
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Subuh' THEN 1 ELSE 0 END) as Subuh"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Dzuhur' THEN 1 ELSE 0 END) as Dzuhur"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Ashar' THEN 1 ELSE 0 END) as Ashar"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Maghrib' THEN 1 ELSE 0 END) as Maghrib"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Isya' THEN 1 ELSE 0 END) as Isya"),
            // Jam masuk (ambil yang paling awal)
            DB::raw("MIN(CASE WHEN waktu_shalat = 'Subuh' THEN jam_masuk END) as jam_masuk_subuh"),
            DB::raw("MIN(CASE WHEN waktu_shalat = 'Dzuhur' THEN jam_masuk END) as jam_masuk_dzuhur"),
            DB::raw("MIN(CASE WHEN waktu_shalat = 'Ashar' THEN jam_masuk END) as jam_masuk_ashar"),
            DB::raw("MIN(CASE WHEN waktu_shalat = 'Maghrib' THEN jam_masuk END) as jam_masuk_maghrib"),
            DB::raw("MIN(CASE WHEN waktu_shalat = 'Isya' THEN jam_masuk END) as jam_masuk_isya"),
            // Jam keluar (ambil yang paling akhir)
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Subuh' THEN jam_keluar END) as jam_keluar_subuh"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Dzuhur' THEN jam_keluar END) as jam_keluar_dzuhur"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Ashar' THEN jam_keluar END) as jam_keluar_ashar"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Maghrib' THEN jam_keluar END) as jam_keluar_maghrib"),
            DB::raw("MAX(CASE WHEN waktu_shalat = 'Isya' THEN jam_keluar END) as jam_keluar_isya")
        )
            ->whereBetween('waktu', [$startDate, $endDate])
            ->where('id_santri', $id)
            ->groupBy(DB::raw('DATE(waktu)')) // group by tanggal saja
            ->orderBy('tanggal', 'asc')
            ->get();

        // Siapkan array tanggal kosong untuk seluruh periode
        $allDates = [];
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i)->toDateString();
            $allDates[$date] = [
                'tanggal' => $date,
                'jumlah_kehadiran' => 0,
                'Subuh' => 0,
                'Dzuhur' => 0,
                'Ashar' => 0,
                'Maghrib' => 0,
                'Isya' => 0,
                'jam_masuk_subuh' => null,
                'jam_masuk_dzuhur' => null,
                'jam_masuk_ashar' => null,
                'jam_masuk_maghrib' => null,
                'jam_masuk_isya' => null,
                'jam_keluar_subuh' => null,
                'jam_keluar_dzuhur' => null,
                'jam_keluar_ashar' => null,
                'jam_keluar_maghrib' => null,
                'jam_keluar_isya' => null,
            ];
        }

        // Isi data hasil query ke array tanggal
        foreach ($kehadiran as $item) {
            $date = $item->tanggal;
            if (isset($allDates[$date])) {
                $allDates[$date] = [
                    'tanggal' => $date,
                    'jumlah_kehadiran' => (int)$item->jumlah_kehadiran,
                    'Subuh' => (int)$item->Subuh,
                    'Dzuhur' => (int)$item->Dzuhur,
                    'Ashar' => (int)$item->Ashar,
                    'Maghrib' => (int)$item->Maghrib,
                    'Isya' => (int)$item->Isya,
                    'jam_masuk_subuh' => $item->jam_masuk_subuh,
                    'jam_masuk_dzuhur' => $item->jam_masuk_dzuhur,
                    'jam_masuk_ashar' => $item->jam_masuk_ashar,
                    'jam_masuk_maghrib' => $item->jam_masuk_maghrib,
                    'jam_masuk_isya' => $item->jam_masuk_isya,
                    'jam_keluar_subuh' => $item->jam_keluar_subuh,
                    'jam_keluar_dzuhur' => $item->jam_keluar_dzuhur,
                    'jam_keluar_ashar' => $item->jam_keluar_ashar,
                    'jam_keluar_maghrib' => $item->jam_keluar_maghrib,
                    'jam_keluar_isya' => $item->jam_keluar_isya,
                ];
            }
        }

        return new AllResource(
            true,
            "Data kehadiran {$periode} {$santri->nama}",
            array_values($allDates)
        );
    }

    private function kehadiranTahunanPerBulan(string $id)
    {
        $startDate = Carbon::today()->subYear()->startOfMonth();
        $endDate = Carbon::today()->endOfDay();

        $santri = Santri::find($id);
        if (!$santri) {
            return new AllResource(false, "Santri tidak ditemukan", null);
        }

        $kehadiran = Kehadiran::select(
            DB::raw("DATE_FORMAT(waktu, '%Y-%m') as bulan"),
            DB::raw('COUNT(DISTINCT DATE(waktu)) as hari_kehadiran'), // hari dengan kehadiran
            DB::raw("SUM(CASE WHEN waktu_shalat = 'Subuh' THEN 1 ELSE 0 END) as subuh"),
            DB::raw("SUM(CASE WHEN waktu_shalat = 'Dzuhur' THEN 1 ELSE 0 END) as dzuhur"),
            DB::raw("SUM(CASE WHEN waktu_shalat = 'Ashar' THEN 1 ELSE 0 END) as ashar"),
            DB::raw("SUM(CASE WHEN waktu_shalat = 'Maghrib' THEN 1 ELSE 0 END) as maghrib"),
            DB::raw("SUM(CASE WHEN waktu_shalat = 'Isya' THEN 1 ELSE 0 END) as isya")
        )
            ->whereBetween('waktu', [$startDate, $endDate])
            ->where('id_santri', $id)
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();

        // Buat array semua bulan 12 bulan terakhir
        $allMonths = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::today()->subMonths($i)->format('Y-m');
            $allMonths[$month] = [
                'bulan' => $month,
                'hari_kehadiran' => 0,
                'subuh' => 0,
                'dzuhur' => 0,
                'ashar' => 0,
                'maghrib' => 0,
                'isya' => 0
            ];
        }

        // Merge hasil query ke semua bulan
        foreach ($kehadiran as $item) {
            if (isset($allMonths[$item->bulan])) {
                $allMonths[$item->bulan] = [
                    'bulan' => $item->bulan,
                    'hari_kehadiran' => (int)$item->hari_kehadiran,
                    'subuh' => (int)$item->subuh,
                    'dzuhur' => (int)$item->dzuhur,
                    'ashar' => (int)$item->ashar,
                    'maghrib' => (int)$item->maghrib,
                    'isya' => (int)$item->isya
                ];
            }
        }

        return new AllResource(
            true,
            "Data kehadiran setahun {$santri->nama} (per bulan)",
            array_values($allMonths)
        );
    }

    // data perizinan
    public function perizinanSetahun(string $id)
    {
        $setahun = Carbon::today()->subYear()->toDateString();      // 1 tahun terakhir
        $perizinan = Perizinan::where('id_santri', $id)->where('waktu', '>=', $setahun)->get();
        $santri = Santri::find($id)->nama;

        return new AllResource(true, "data kehadiran mingguan $santri", $perizinan);
    }
    // mengambil data pengumuman beserta guru yang mengumumkan
    public function Pengumuman()
    {
        $pengumuman = Pengumuman::with('staff')->get();
        return new AllResource(true, 'Semua pengumuman beserta siapa yang mengumumkan', $pengumuman);
    }
}
