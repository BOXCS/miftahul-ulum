<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SantriSeeder extends Seeder
{
    public function run()
    {
        $tahunAngkatan = '24'; // Misalnya tahun angkatan 2024
        
        for ($i = 1; $i <= 10; $i++) {
            $id = 'MU' . $tahunAngkatan . str_pad($i, 4, '0', STR_PAD_LEFT);
            
            $status = $i <= 7 ? 'Aktif' : ['Tidak Aktif', 'Izin', 'Sakit'][array_rand(['Tidak Aktif', 'Izin', 'Sakit'])];
            
            DB::table('santri')->insert([
                'id' => $id,
                'nama_lengkap' => 'Santri ' . $i,
                'tahun_angkatan' => $tahunAngkatan,
                'nama_orang_tua' => 'Orang Tua ' . $i,
                'status' => $status,
                'sidik_jari' => null, // Karena binary, kita biarkan NULL dulu
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}