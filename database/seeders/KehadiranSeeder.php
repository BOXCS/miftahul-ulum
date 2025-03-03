<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KehadiranSeeder extends Seeder
{
    public function run()
    {
        $santri = DB::table('santri')->pluck('id', 'nama_lengkap');
        $waktuShalat = ['Subuh' => '05:00:00', 'Dzuhur' => '12:00:00', 'Ashar' => '15:30:00', 'Maghrib' => '18:00:00', 'Isya' => '19:30:00'];
        $startDate = Carbon::now()->subMonth(); // 1 bulan ke belakang

        foreach ($santri as $nama => $santri_id) {
            for ($i = 0; $i < 30; $i++) { // 30 hari
                $date = $startDate->copy()->addDays($i);

                foreach ($waktuShalat as $shalat => $waktu) {
                    // Tentukan waktu shalat berdasarkan jam masuk
                    $waktuShalatValue = $this->getWaktuShalat($waktu);

                    DB::table('kehadiran')->insert([
                        'santri_id' => $santri_id,
                        'nama_santri' => $nama,
                        'jam_masuk' => $waktu,
                        'jam_keluar' => Carbon::parse($waktu)->addMinutes(rand(10, 30))->format('H:i:s'),
                        'status' => ['Hadir', 'Tidak Hadir', 'Izin', 'Sakit'][array_rand(['Hadir', 'Tidak Hadir', 'Izin', 'Sakit'])],
                        'waktu_shalat' => $waktuShalatValue, // Waktu shalat diisi otomatis
                        'tanggal_waktu' => $date->format('Y-m-d') . ' ' . $waktu,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    /**
     * Fungsi untuk menentukan waktu shalat berdasarkan jam masuk.
     *
     * @param string $jamMasuk
     * @return string|null
     */
    private function getWaktuShalat($jamMasuk)
    {
        $jam = strtotime($jamMasuk);

        if ($jam >= strtotime('04:00:00') && $jam < strtotime('06:00:00')) {
            return 'Subuh';
        } elseif ($jam >= strtotime('12:00:00') && $jam < strtotime('14:00:00')) {
            return 'Dzuhur';
        } elseif ($jam >= strtotime('15:00:00') && $jam < strtotime('17:00:00')) {
            return 'Ashar';
        } elseif ($jam >= strtotime('18:00:00') && $jam < strtotime('19:00:00')) {
            return 'Maghrib';
        } elseif ($jam >= strtotime('19:30:00') && $jam < strtotime('21:00:00')) {
            return 'Isya';
        } else {
            return null; // Jika tidak masuk ke rentang waktu shalat
        }
    }
}