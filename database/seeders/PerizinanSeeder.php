<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PerizinanSeeder extends Seeder
{
    public function run()
    {
        // Pastikan SantriSeeder sudah dijalankan
        $santriList = DB::table('santri')->where('status', 'aktif')->get();
        
        if ($santriList->isEmpty()) {
            $this->command->info('Data santri aktif kosong! Jalankan SantriSeeder terlebih dahulu.');
            return;
        }

        $dataPerizinan = [];
        $now = Carbon::now();
        $startDate = $now->copy()->subDays(60); // Data untuk 60 hari terakhir

        foreach ($santriList as $santri) {
            // Setiap santri aktif memiliki 2-5 data perizinan
            $jumlahIzin = rand(2, 5);
            
            for ($i = 0; $i < $jumlahIzin; $i++) {
                $jenisIzin = rand(0, 1) ? 'izin' : 'sakit';
                $tanggalIzin = $startDate->copy()->addDays(rand(0, 59));
                
                $dataPerizinan[] = [
                    'waktu' => $tanggalIzin,
                    'jenis_izin' => $jenisIzin,
                    'keterangan' => $this->generateKeterangan($jenisIzin),
                    'id_santri' => $santri->id_santri,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('perizinan')->insert($dataPerizinan);
    }

    /**
     * Generate keterangan berdasarkan jenis izin
     */
    private function generateKeterangan(string $jenisIzin): string
    {
        $keteranganIzin = [
            "Pulang mengikuti acara keluarga",
            "Menghadiri undangan pernikahan",
            "Menjenguk anggota keluarga yang sakit",
            "Keperluan keluarga mendesak",
            "Mengikuti kegiatan di luar pesantren",
            "Perlu istirahat di rumah",
            "Ada keperluan orang tua"
        ];

        $keteranganSakit = [
            "Demam tinggi",
            "Flu dan batuk",
            "Sakit perut",
            "Pusing berkepanjangan",
            "Diare",
            "Cedera olahraga",
            "Periksa ke dokter"
        ];

        if ($jenisIzin === 'izin') {
            return $keteranganIzin[array_rand($keteranganIzin)];
        }

        return $keteranganSakit[array_rand($keteranganSakit)];
    }
}