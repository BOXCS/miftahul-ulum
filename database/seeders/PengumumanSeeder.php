<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PengumumanSeeder extends Seeder
{
    public function run()
    {
        // Pastikan AkunSeeder sudah dijalankan
        $staffAccounts = DB::table('akun')
                        ->whereIn('hak_akses', ['admin', 'superadmin'])
                        ->pluck('id_akun');
        
        if ($staffAccounts->isEmpty()) {
            $this->command->info('Data staff/admin kosong! Jalankan AkunSeeder terlebih dahulu.');
            return;
        }

        $dataPengumuman = [];
        $faker = \Faker\Factory::create('id_ID');
        $kategori = ['akademik', 'administrasi', 'kegiatan'];
        $now = Carbon::now();

        // Buat 10-15 pengumuman
        $jumlahPengumuman = rand(10, 15);
        
        for ($i = 0; $i < $jumlahPengumuman; $i++) {
            $tglMulai = $now->copy()->subDays(rand(0, 30)); // Pengumuman dari 30 hari terakhir
            
            $dataPengumuman[] = [
                'judul' => $this->generateJudul($faker),
                'isi' => $this->generateIsiPengumuman($faker),
                'tgl_mulai' => $tglMulai,
                'kategori' => $kategori[array_rand($kategori)],
                'foto' => rand(0, 1) ? 'pengumuman/example'.rand(1,3).'.jpg' : null, // 50% punya foto
                'id_akun' => $staffAccounts->random(),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('pengumuman')->insert($dataPengumuman);
    }

    private function generateJudul($faker): string
    {
        $judul = [
            "Jadwal Ujian Akhir Semester",
            "Pembayaran SPP Bulan Ini",
            "Kegiatan Pesantren Kilat",
            "Pendaftaran Lomba Hafalan",
            "Perubahan Jadwal Sholat",
            "Libur Hari Besar",
            "Penerimaan Santri Baru",
            "Pelaksanaan Kegiatan Tahunan",
            "Peringatan Hari Besar Islam",
            "Pembagian Raport Semester",
            "Jadwal Kegiatan Ekstrakurikuler",
            "Informasi Penting Untuk Orang Tua"
        ];

        return $judul[array_rand($judul)];
    }

    private function generateIsiPengumuman($faker): string
    {
        $paragraf = [];
        
        // Buat 3-5 paragraf
        for ($i = 0; $i < rand(3, 5); $i++) {
            $paragraf[] = $faker->paragraph(rand(2, 5));
        }

        return implode("\n\n", $paragraf);
    }
}