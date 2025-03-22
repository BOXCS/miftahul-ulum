<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SantriSeeder extends Seeder
{
    public function run()
    {
        $tahun_angkatan = '2024';
        $tahun_2digit = substr($tahun_angkatan, 2, 2); // Ambil 2 digit terakhir (24)

        // Ambil ID Orang Tua yang tersedia di database
        $id_ortu_list = DB::table('orang_tua')->pluck('id_ortu')->toArray();

        if (count($id_ortu_list) < 10) {
            throw new \Exception("Jumlah orang tua kurang dari 10, harap cek data di database.");
        }

        for ($i = 0; $i < 10; $i++) {
            $id_santri = 'MU01' . $tahun_2digit . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

            DB::table('santri')->insert([
                'id_santri'     => $id_santri,
                'nama'          => 'Santri ' . ($i + 1),
                'tahun_angkatan'=> $tahun_angkatan,
                'sidik_jari'    => null,
                'status'        => 'aktif',
                'id_ortu'       => $id_ortu_list[$i], // Ambil id_ortu yang valid
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }
    }
}


