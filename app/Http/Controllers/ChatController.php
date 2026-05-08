<?php

namespace App\Http\Controllers;

use Ably\AblyRest;
use App\Models\Akun;
use App\Models\ChatSession;
use App\Models\ChatMessage;
use App\Services\AblyService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    protected $ably;

    public function __construct(AblyService $ably)
    {
        $this->ably = $ably;
    }

    // Menampilkan halaman chat utama (default session ID 1)
    public function index()
    {
        $sessions = ChatSession::with(['santri', 'orangTua'])->get();

        foreach ($sessions as $session) {
            $session->setAttribute('wali_dari', $session->santri->pluck('nama')->implode(', '));
            $session->setAttribute('nama_lengkap', $session->orangTua->nama_lengkap ?? '-');
            $session->setAttribute('alamat_ortu', $session->orangTua->alamat ?? '-');
        }

        // Ambil ID session aktif pertama jika ada
        $activeSessionId = $sessions->first()->id_session ?? null;

        return view('chat', [
            'sessions' => $sessions,
            'messages' => $activeSessionId ? $this->getMessages($activeSessionId) : [],
            'chat' => $activeSessionId ? $this->getUserInfo($activeSessionId) : null,
            'activeSessionId' => $activeSessionId,
        ]);
    }



    // Menampilkan chat berdasarkan session ID
    public function show($id)
    {
        return view('chat', [
            'sessions' => ChatSession::all(),
            'messages' => ChatMessage::where('id_session', $id)->get(),
            'activeSessionId' => $id,
        ]);
    }

    // Menyimpan pesan baru
    public function store(Request $request)
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

        // 🔥 Ganti ke channel global
        $ably = new AblyRest(env('ABLY_API_KEY'));
        $ably->channels->get('pesantren-chat')->publish('chat', json_encode([
    'id_session' => $request->id_session,
    'pengirim' => $request->pengirim,
    'pesan' => $request->pesan,
    'waktu' => $message->created_at->toDateTimeString(),
]));

        return response()->json(['success' => true, 'message' => $message]);
    }

public function getOrCreateSession(Request $request)
{
logger()->info('GetOrCreateSession hit', $request->all());

    $request->validate([
        'id_staf' => 'required|integer',
        'id_ortu' => 'required|integer',
    ]);

    $idStaf = $request->input('id_staf');
    $idOrtu = $request->input('id_ortu');

    // Cek apakah sudah ada session antara staf dan ortu
    $session = ChatSession::where('id_staf', $idStaf)
                          ->where('id_ortu', $idOrtu)
                          ->first();

    // Jika belum ada, buat baru
    if (!$session) {
        $session = ChatSession::create([
            'id_staf' => $idStaf,
            'id_ortu' => $idOrtu,
        ]);
    }

    return response()->json([
        'id_session' => $session->id_session,
        'id_staf' => $session->id_staf,
        'id_ortu' => $session->id_ortu,
    ]);
}



    // Blokir user (hapus session)
    public function destroy($id)
    {
        $session = ChatSession::findOrFail($id);
        $session->delete();

        return redirect()->route('chat.index')
            ->with('success', 'User berhasil diblokir!');
    }

    public function getSessionMessages($id)
    {
        $messages = ChatMessage::where('id_session', $id)->orderBy('waktu', 'asc')->get();

        return response()->json($messages);
    }

    public function getUserInfo($sessionId)
    {
        $session = ChatSession::where('id_session', $sessionId)->first();

        if (!$session) {
            return null;
        }

        return [
            'nama_orang_tua' => $session->nama_orang_tua,
            'wali_dari' => $session->wali_dari,
            'asal_daerah' => $session->asal_daerah,
        ];
    }


    protected function getMessages($sessionId)
    {
        return ChatMessage::where('id_session', $sessionId)
            ->orderBy('waktu', 'asc')
            ->get();
    }

    public function sendMessage(Request $request)
{
    $request->validate([
        'id_session' => 'required|exists:chat_sessions,id_session',
        'pengirim' => 'required|in:staf,orang_tua',
        'pesan' => 'required|string',
    ]);

    // Simpan pesan ke database
    $message = ChatMessage::create([
        'id_session' => $request->id_session,
        'pengirim' => $request->pengirim,
        'pesan' => $request->pesan,
        'created_at' => now(),
    ]);

    // ✅ Publish ke Ably (agar web menerima real-time message)
    try {
        $ably = new AblyRest(env('ABLY_API_KEY')); // pastikan .env punya ABLY_API_KEY
        $channel = $ably->channels->get('pesantren-chat');

        $channel->publish('chat', json_encode([
            'pesan' => $message->pesan,
            'pengirim' => $message->pengirim,
            'created_at' => $message->created_at->toIso8601String(),
        ]));
    } catch (\Exception $e) {
        logger()->error('Gagal publish ke Ably: ' . $e->getMessage());
    }

    return response()->json([
        'success' => true,
        'message' => 'Pesan terkirim',
        'data' => $message,
    ]);
}
}