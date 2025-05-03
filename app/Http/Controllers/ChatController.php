<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = [
            [
                'nama' => 'Yanto',
                'wali_dari' => 'Agung Hapsah',
                'pesan' => 'Lorem ipsum dolor sit amet',
                'waktu' => '08:00',
                'jumlah_pesan_baru' => 99,
                'tanggal' => '12/12/25',
            ],
            // Tambahkan lebih banyak dummy chat jika perlu
        ];

        $userProfile = [
            'nama' => 'Yanto',
            'wali_dari' => 'Agung Hapsah',
            'asal' => 'Jl. Mastrip No.164, Lingkungan Panji, Tegalgede, Kec. Sumbersari, Kabupaten Jember, Jawa Timur',
            'foto' => '/images/profile.png', // Path ke foto dummy
        ];

        $messages = [
            ['sender' => 'yanto', 'text' => 'Halo, apa kabar?', 'time' => '08:00'],
            ['sender' => 'me', 'text' => 'Baik, terima kasih!', 'time' => '08:00'],
        ];

        return view('Chat', compact('chats', 'userProfile', 'messages'));
    }
}
