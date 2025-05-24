<?php

namespace App\Http\Controllers\Api;

use Ably\AblyRest;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use App\Models\ChatMessage;
use App\Models\Kehadiran;
use App\Models\Pengumuman;
use App\Models\Santri;
use Illuminate\Http\Request;
use Str;

class MobileDataController extends Controller
{
    // untuk mengambil data Kehadiran seorang santri
    public function kehadiranById(string $id)
    {
        $kehadiran = Kehadiran::with('santri')->where('id_santri', '=', $id)->get();
        return new AllResource(true, "data kehadiran santri = " . $kehadiran->first()->santri->nama, $kehadiran);
    }
    // untuk mengambil data santri menggunakan id
    public function dataSantriById(string $id)
    {
        $santri = Santri::find($id);
        return new AllResource(true, "data santri atas nama = " . $santri->nama, $santri);
    }
    // mengambil data pengumuman beserta guru yang mengumumkan
    public function Pengumuman()
    {
        $pengumuman = Pengumuman::with('staff')->get();
        return new AllResource(true, 'Semua pengumuman beserta siapa yang mengumumkan', $pengumuman);
    }

    public function chatMessagesBySession($session)
    {
        $messages = \App\Models\ChatMessage::where('id_session', $session)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'id_session' => 'required|exists:chat_sessions,id_session',
            'pengirim' => 'required|string',
            'pesan' => 'required|string',
        ]);

        $message = ChatMessage::create([
            'id_session' => $request->id_session,
            'pengirim' => $request->pengirim,
            'pesan' => $request->pesan,
            'waktu' => now(),
        ]);

        // Publikasikan ke channel ably
        $ably = new AblyRest(env('ABLY_API_KEY'));
        $ably->channels->get('pesantren-chat')->publish('new-message', [
            'id_session' => $request->id_session,
            'pengirim' => $request->pengirim,
            'pesan' => $request->pesan,
            'waktu' => now()->toDateTimeString(),
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }


    public function startChatSession(Request $request)
    {
        $request->validate([
            'id_ortu' => 'required|exists:orang_tua,id',
        ]);

        $existing = \App\Models\ChatSession::where('id_ortu', $request->id_ortu)->first();
        if ($existing) return response()->json($existing);

        $new = \App\Models\ChatSession::create([
            'id_session' => \Illuminate\Support\Str::uuid(),
            'id_ortu' => $request->id_ortu,
        ]);

        return response()->json($new);
    }
}
