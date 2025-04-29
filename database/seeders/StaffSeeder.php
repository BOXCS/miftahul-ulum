<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StaffSeeder extends Seeder
{
    public function run()
    {
        // Pastikan AkunSeeder sudah dijalankan sebelumnya
        // Ambil ID akun dengan hak akses 'admin' dan 'superadmin'
        $staffAccounts = DB::table('akun')
                        ->whereIn('hak_akses', ['admin', 'superadmin'])
                        ->orderBy('id_akun')
                        ->get();

        $dataStaff = [];
        $faker = \Faker\Factory::create('id_ID'); // Faker dengan lokal Indonesia
        
        // Daftar jabatan yang mungkin
        $jabatan = [
            'Administrator',
            'Kepala Sekolah',
            'Wakil Kepala Sekolah',
            'Guru',
            'Bendahara',
            'Staf TU',
            'Kepala Asrama',
            'Konselor'
        ];

        foreach ($staffAccounts as $index => $account) {
            $isSuperadmin = ($account->hak_akses === 'superadmin');
            
            $dataStaff[] = [
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'no_telp' => $faker->phoneNumber,
                'jabatan' => $isSuperadmin ? 'Kepala Sekolah' : $jabatan[array_rand($jabatan)],
                'tgl_bergabung' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                'id_akun' => $account->id_akun,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('staff')->insert($dataStaff);
    }
}