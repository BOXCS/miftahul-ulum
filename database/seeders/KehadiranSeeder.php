<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KehadiranSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua santri yang aktif
        $santri = DB::table('santri')->where('status', 'aktif')->pluck('id_santri');

        // Waktu shalat yang ada dalam sistem
        $waktuShalat = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];

        // Tanggal bulan ini dan bulan lalu
        $startThisMonth = Carbon::now()->startOfMonth();
        $startLastMonth = Carbon::now()->subMonth()->startOfMonth();
        $endLastMonth = Carbon::now()->subMonth()->endOfMonth();

        // Fungsi untuk generate kehadiran
        function generateKehadiran($santri, $startDate, $endDate, $waktuShalat)
        {
            $kehadiranData = [];

            while ($startDate <= $endDate) {
                foreach ($santri as $idSantri) {
                    foreach ($waktuShalat as $waktu) {
                        // 80% kemungkinan hadir, 20% absen
                        if (rand(1, 100) <= 80) {
                            $kehadiranData[] = [
                                'id_santri'   => $idSantri,
                                'waktu'       => $startDate->toDateString(),
                                'waktu_shalat' => $waktu,
                                'jam_masuk'   => $startDate->copy()->setHour(rand(4, 20))->setMinute(rand(0, 59))->format('H:i:s'),
                                'jam_keluar'  => $startDate->copy()->setHour(rand(4, 21))->setMinute(rand(0, 59))->format('H:i:s'),
                                'created_at'  => now(),
                                'updated_at'  => now(),
                            ];
                        }
                    }
                }
                $startDate->addDay();
            }

            return $kehadiranData;
        }

        // Buat data kehadiran untuk bulan lalu dan bulan ini
        $dataKehadiran = array_merge(
            generateKehadiran($santri, $startLastMonth, $endLastMonth, $waktuShalat),
            generateKehadiran($santri, $startThisMonth, now(), $waktuShalat)
        );

        // Masukkan data ke tabel kehadiran
        DB::table('kehadiran')->insert($dataKehadiran);
    }
}
