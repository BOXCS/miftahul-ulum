<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $this->call([
            AkunSeeder::class,
            OrangTuaSeeder::class,
            StaffSeeder::class,
            SantriSeeder::class,
            ChatSeeder::class,
            KehadiranSeeder::class,
            PerizinanSeeder::class,
            PengumumanSeeder::class,
            ChatSessionSeeder::class
        ]);
    }
}
