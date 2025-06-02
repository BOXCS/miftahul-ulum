<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KehadiranSeeder extends Seeder
{
    public function run()
    {
        // Pastikan SantriSeeder sudah dijalankan
        $santriList = DB::table('santri')->where('status', 'aktif')->get();
        
        if ($santriList->isEmpty()) {
            $this->command->info('Data santri aktif kosong! Jalankan SantriSeeder terlebih dahulu.');
            return;
        }

        $dataKehadiran = [];
        $waktuShalat = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];
        $now = Carbon::now();
        $startDate = $now->copy()->subDays(30); // Data untuk 30 hari terakhir

        foreach ($santriList as $santri) {
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $now) {
                foreach ($waktuShalat as $shalat) {
                    // 20% kemungkinan tidak hadir
                    $hadir = rand(1, 100) > 20;
                    
                    $waktuShalatDate = $this->getWaktuShalat($currentDate, $shalat);
                    
                    $dataKehadiran[] = [
                        'waktu' => $waktuShalatDate,
                        'jam_masuk' => $hadir ? $this->getJamMasuk($waktuShalatDate) : null,
                        'jam_keluar' => $hadir ? $this->getJamKeluar($waktuShalatDate, $shalat) : null,
                        'waktu_shalat' => $hadir ? $shalat : null, // ubah ini
                        'id_santri' => $santri->id_santri,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    
                }
                $currentDate->addDay();
            }
        }

        // Chunk insert untuk menghindari memory limit
        foreach (array_chunk($dataKehadiran, 500) as $chunk) {
            DB::table('kehadiran')->insert($chunk);
        }
    }

    /**
     * Mendapatkan waktu shalat berdasarkan jenis shalat dan tanggal
     */
    private function getWaktuShalat(Carbon $date, string $shalat): Carbon
    {
        $time = match ($shalat) {
            'Subuh' => '05:00:00',
            'Dzuhur' => '12:00:00',
            'Ashar' => '15:00:00',
            'Maghrib' => '18:00:00',
            'Isya' => '19:30:00',
            default => '12:00:00',
        };

        return $date->copy()->setTimeFromTimeString($time);
    }

    /**
     * Mendapatkan jam masuk dengan variasi +/- 15 menit dari waktu shalat
     */
    private function getJamMasuk(Carbon $waktuShalat): Carbon
    {
        return $waktuShalat->copy()->addMinutes(rand(-15, 15));
    }

    /**
     * Mendapatkan jam keluar berdasarkan jenis shalat
     */
    private function getJamKeluar(Carbon $waktuShalat, string $shalat): Carbon
    {
        $duration = match ($shalat) {
            'Subuh' => rand(20, 40), // 20-40 menit
            'Dzuhur' => rand(15, 30),
            'Ashar' => rand(15, 30),
            'Maghrib' => rand(10, 20),
            'Isya' => rand(20, 40),
            default => 30,
        };

        return $waktuShalat->copy()->addMinutes($duration);
    }
}