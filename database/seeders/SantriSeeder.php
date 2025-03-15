<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SantriSeeder extends Seeder
{
    public function run()
    {
        $tahunAngkatan = '24'; // Tahun angkatan 2024
        
        for ($i = 1; $i <= 10; $i++) {
            // Tentukan status santri
            $status = $i <= 7 ? 'aktif' : 'tidak aktif';

            DB::table('santri')->insert([
                'nama' => 'Santri ' . $i,
                'tahun_angkatan' => $tahunAngkatan,
                'sidik_jari' => null, // Sidik jari tidak diisi (nullable)
                'status' => $status,
                'id_ortu' => rand(1, 5), // Id orang tua diacak antara 1-5 (sesuaikan dengan data orang tua)
                'id_izin' => null, // Tidak semua santri memiliki izin, bisa diatur nanti
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
