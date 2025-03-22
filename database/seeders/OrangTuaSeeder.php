<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrangTuaSeeder extends Seeder
{
    public function run()
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('orang_tua')->insert([
                'alamat'    => 'Jl. Contoh No.' . $i,
                'no_telp'   => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'id_akun'   => $i, // Pastikan id_akun sudah ada di tabel akun
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

