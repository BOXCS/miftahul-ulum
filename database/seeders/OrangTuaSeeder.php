<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrangTuaSeeder extends Seeder
{
    public function run()
    {
        // Pastikan AkunSeeder sudah dijalankan sebelumnya
        // Ambil ID akun dengan hak akses 'ortu' (10 akun pertama yang dibuat)
        $ortuAccounts = DB::table('akun')
                        ->where('hak_akses', 'ortu')
                        ->orderBy('id_akun')
                        ->take(10)
                        ->get();

        $dataOrtu = [];
        $faker = \Faker\Factory::create('id_ID'); // Faker dengan lokal Indonesia

        foreach ($ortuAccounts as $index => $account) {
            $dataOrtu[] = [
                'alamat' => $faker->address,
                'nama_lengkap' => $faker->name,
                'no_telp' => $faker->phoneNumber,
                'id_akun' => $account->id_akun,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('orang_tua')->insert($dataOrtu);
    }
}