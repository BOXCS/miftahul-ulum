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
        return view('chat', [
            'sessions' => ChatSession::all(),
            'messages' => ChatMessage::where('id_session', 1)->get(),
            'activeSessionId' => 1,
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

        // 🔥 Publish ke Ably
        $ably = new AblyRest(env('ABLY_API_KEY'));
        $ably->channels->get('chat-session-' . $request->id_session)->publish('new-message', [
            'id_session' => $request->id_session,
            'pengirim' => $request->pengirim,
            'pesan' => $request->pesan,
            'waktu' => now()->toDateTimeString(),
        ]);

        return response()->json(['success' => true, 'message' => $message]);
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
}
