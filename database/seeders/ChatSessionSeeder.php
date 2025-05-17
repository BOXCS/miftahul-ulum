<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChatSessionSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID akun dengan hak akses 'admin' (ID akun 11)
        $akun = DB::table('akun')->where('id_akun', 11)->first();

        if ($akun) {
            // Dapatkan ID staf berdasarkan ID akun
            $staff = DB::table('staff')->where('id_akun', $akun->id_akun)->first();

            if ($staff) {
                // Menambahkan data chat session untuk id_staf 1 dan id_ortu yang valid
                DB::table('chat_sessions')->insert([
                    'id_ortu' => 1, // ID orang tua yang valid
                    'id_staf' => $staff->id_staf, // Gunakan ID staf yang sesuai
                    'last_message_time' => now(),
                    'last_message' => 'Selamat siang admin!',
                    'unread_count' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Error jika staf dengan id_akun 11 tidak ditemukan
                echo "Staf dengan ID akun 11 tidak ditemukan.";
            }
        } else {
            // Error jika akun dengan id_akun 11 tidak ditemukan
            echo "Akun dengan ID 11 tidak ditemukan.";
        }
    }
}

