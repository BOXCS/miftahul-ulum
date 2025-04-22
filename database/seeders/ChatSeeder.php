<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatSeeder extends Seeder
{
    public function run()
    {
        // Pastikan StaffSeeder dan OrangTuaSeeder sudah dijalankan
        $staffList = DB::table('staff')->get();
        $orangTuaList = DB::table('orang_tua')->get();
        
        if ($staffList->isEmpty() || $orangTuaList->isEmpty()) {
            $this->command->info('Data staff atau orang tua kosong! Jalankan seeder terkait terlebih dahulu.');
            return;
        }

        $dataChat = [];
        $faker = \Faker\Factory::create('id_ID');
        
        // Buat 5-10 percakapan per pasangan staff-orang tua
        foreach ($staffList as $staff) {
            // Ambil 1-3 orang tua secara acak untuk chatting dengan staff ini
            $jumlahOrtu = rand(1, min(3, $orangTuaList->count()));
            $selectedOrtu = $orangTuaList->random($jumlahOrtu);
            
            foreach ($selectedOrtu as $ortu) {
                // Buat 5-10 pesan per percakapan
                $jumlahPesan = rand(5, 10);
                $waktu = now()->subDays(rand(1, 30));
                
                for ($i = 0; $i < $jumlahPesan; $i++) {
                    // Bergantian antara staff dan orang tua sebagai pengirim
                    $pengirim = ($i % 2 == 0) ? 'staf' : 'orang_tua';
                    $waktu = $waktu->addMinutes(rand(1, 1440)); // Jarak antar pesan 1 menit - 1 hari
                    
                    $dataChat[] = [
                        'id_staf' => $staff->id_staf,
                        'id_ortu' => $ortu->id_ortu,
                        'pesan' => $faker->sentence(rand(5, 15)),
                        'waktu' => $waktu,
                        'pengirim' => $pengirim,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        DB::table('chat')->insert($dataChat);
    }
}