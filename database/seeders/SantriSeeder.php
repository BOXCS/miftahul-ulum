<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SantriSeeder extends Seeder
{
    public function run()
    {
        // Pastikan OrangTuaSeeder sudah dijalankan sebelumnya
        $orangTua = DB::table('orang_tua')->get();
        
        if ($orangTua->isEmpty()) {
            $this->command->info('Data orang tua kosong! Jalankan OrangTuaSeeder terlebih dahulu.');
            return;
        }

        $dataSantri = [];
        $faker = \Faker\Factory::create('id_ID');
        
        // Generate 3-5 santri per orang tua
        foreach ($orangTua as $ortu) {
            $jumlahSantri = rand(1, 3); // Setiap ortu punya 1-3 santri
            
            for ($i = 1; $i <= $jumlahSantri; $i++) {
                $tahunAngkatan = (string) $faker->numberBetween(2018, 2023);
                $idSantri = 'ST' . $tahunAngkatan . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
                
                $dataSantri[] = [
                    'id_santri' => $idSantri,
                    'nama' => $faker->firstName . ' ' . $faker->lastName,
                    'tahun_angkatan' => $tahunAngkatan,
                    'sidik_jari' => null, // Bisa diisi binary data jika diperlukan
                    'status' => $faker->randomElement(['aktif', 'tidak aktif']),
                    'id_ortu' => $ortu->id_ortu,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('santri')->insert($dataSantri);
    }
}