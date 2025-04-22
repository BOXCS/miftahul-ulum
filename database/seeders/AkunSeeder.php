<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AkunSeeder extends Seeder
{
    public function run()
    {
        // Data akun orang tua (10 akun)
        $ortuAccounts = [];
        for ($i = 1; $i <= 10; $i++) {
            $ortuAccounts[] = [
                'email' => 'ortu'.$i.'@example.com',
                'username' => 'orangtua'.$i,
                'password' => Hash::make('password123'), // Password default
                'hak_akses' => 'ortu',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Data akun admin (2 akun)
        $adminAccounts = [
            [
                'email' => 'admin1@example.com',
                'username' => 'admin1',
                'password' => Hash::make('admin123'),
                'hak_akses' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'admin2@example.com',
                'username' => 'admin2',
                'password' => Hash::make('admin123'),
                'hak_akses' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Data akun superadmin (2 akun)
        $superadminAccounts = [
            [
                'email' => 'superadmin1@example.com',
                'username' => 'superadmin1',
                'password' => Hash::make('superadmin123'),
                'hak_akses' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'email' => 'superadmin2@example.com',
                'username' => 'superadmin2',
                'password' => Hash::make('superadmin123'),
                'hak_akses' => 'superadmin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        // Gabungkan semua data
        $allAccounts = array_merge($ortuAccounts, $adminAccounts, $superadminAccounts);

        // Masukkan data ke database
        DB::table('akun')->insert($allAccounts);
    }
}