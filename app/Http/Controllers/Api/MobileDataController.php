<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use App\Models\Kehadiran;
use App\Models\OrangTua;
use App\Models\Pengumuman;
use App\Models\Perizinan;
use App\Models\Santri;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

        if($santri->isEmpty()) {
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
