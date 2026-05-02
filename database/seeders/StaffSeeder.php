<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama jika ada
        User::whereIn('email', [
            'superadmin@miftahululum.com',
            'admin@miftahululum.com',
        ])->delete();

        User::create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@miftahululum.com',
            'password' => bcrypt('superadmin123'),
            'role'     => 'superadmin',
        ]);

        User::create([
            'name'     => 'Admin',
            'email'    => 'admin@miftahululum.com',
            'password' => bcrypt('admin123'),
            'role'     => 'admin',
        ]);
    }
}
