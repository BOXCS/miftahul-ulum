<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Announcement;
use App\Models\ChatMessage;
use App\Models\Attendance;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // ← Cari user manual, jangan pakai Auth::attempt() (itu untuk session/web)
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !\Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah.',
            ], 401);
        }

        $parent = ParentModel::where('user_id', $user->id)->first();

        if (!$parent) {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini bukan orang tua santri.',
            ], 403);
        }

        $user->tokens()->delete();
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'token'   => $token,
            'akun'    => [
                'id_akun'   => $parent->id,
                'email'     => $user->email,
                'username'  => $user->name,
                'hak_akses' => 'orang_tua',
            ],
        ]);
    }

    public function santriByOrtu($id)
    {
        $students = Student::where("parent_id", $id)
            ->with("parent") // jika relasi ada
            ->get()
            ->map(function ($s) {
                return [
                    "id_santri" => (string) $s->id,
                    "nama" => $s->name, // ✅ rename
                    "tahun_angkatan" => $s->class, // mapping sementara
                    "sidik_jari" => null, // belum ada alat
                    "status" => $s->status ?? "aktif", // default
                    "id_ortu" => (int) $s->parent_id,

                    // optional nested object
                    "ortu" => $s->parent
                        ? [
                            "nama_lengkap" => $s->parent->name,
                            "alamat" => $s->parent->address,
                            "no_telp" => $s->parent->phone,
                        ]
                        : null,
                ];
            });

        return response()->json([
            "success" => true,
            "message" => "Data santri berhasil diambil", // ✅ tambahkan
            "data" => $students,
        ]);
    }

    public function kehadiranMingguan($id)
    {
        $attendance = Attendance::where("student_id", $id)
            ->whereBetween("tanggal", [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->get()
            ->map(function ($a) {
                return [
                    "tanggal" => $a->tanggal ?? "",

                    "jumlah_kehadiran" => $a->jumlah_kehadiran ?? 0,

                    "Subuh" => (int) ($a->Subuh ?? 0),
                    "Dzuhur" => (int) ($a->Dzuhur ?? 0),
                    "Ashar" => (int) ($a->Ashar ?? 0),
                    "Maghrib" => (int) ($a->Maghrib ?? 0),
                    "Isya" => (int) ($a->Isya ?? 0),

                    "jam_masuk_subuh" => $a->jam_masuk_subuh ?? null,
                    "jam_keluar_subuh" => $a->jam_keluar_subuh ?? null,
                    "jam_masuk_dzuhur" => $a->jam_masuk_dzuhur ?? null,
                    "jam_keluar_dzuhur" => $a->jam_keluar_dzuhur ?? null,
                    "jam_masuk_ashar" => $a->jam_masuk_ashar ?? null,
                    "jam_keluar_ashar" => $a->jam_keluar_ashar ?? null,
                    "jam_masuk_maghrib" => $a->jam_masuk_maghrib ?? null,
                    "jam_keluar_maghrib" => $a->jam_keluar_maghrib ?? null,
                    "jam_masuk_isya" => $a->jam_masuk_isya ?? null,
                    "jam_keluar_isya" => $a->jam_keluar_isya ?? null,
                ];
            });

        return response()->json([
            "success" => true,
            "message" => "OK",
            "data" => $attendance,
        ]);
    }

    public function kehadiranSummary($id)
    {
        $totalHadir = Attendance::where("student_id", $id)
            ->where("status", "hadir")
            ->count();
        $totalIzin = Attendance::where("student_id", $id)
            ->where("status", "izin")
            ->count();

        return response()->json([
            "success" => true,
            "data" => [
                "hadir" => $totalHadir,
                "izin" => $totalIzin,
            ],
        ]);
    }

    public function perizinan($id)
    {
        $permissions = Permission::where("student_id", $id)->get();
        return response()->json(["success" => true, "data" => $permissions]);
    }

    public function chatHistory(Request $request, $parentId)
    {
        // Pastikan orang tua hanya bisa lihat chat miliknya
        $user   = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        if ((int) $parent->id !== (int) $parentId) {
            return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        // Mark pesan dari admin sebagai sudah dibaca
        ChatMessage::where('parent_id', $parentId)
            ->where('is_from_admin', true)
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);

        $messages = ChatMessage::where('parent_id', $parentId)
            ->oldest()
            ->get()
            ->map(fn($m) => [
                'id'            => $m->id,
                'pesan'         => $m->pesan,
                'is_from_admin' => $m->is_from_admin,
                'is_read'       => $m->is_read,
                'time'          => $m->created_at->format('H:i'),
                'created_at'    => $m->created_at->toIso8601String(),
            ]);

        return response()->json(['success' => true, 'data' => $messages]);
    }

    public function sendMessage(Request $request, $parentId)
    {
        $user   = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        if ((int) $parent->id !== (int) $parentId) {
            return response()->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        $validated = $request->validate([
            'pesan' => 'required|string|max:1000',
        ]);

        $msg = ChatMessage::create([
            'parent_id'     => $parentId,
            'pesan'         => $validated['pesan'],
            'is_from_admin' => false,
            'is_read'       => false,
        ]);

        broadcast(new \App\Events\MessageSent($msg))->toOthers();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'            => $msg->id,
                'pesan'         => $msg->pesan,
                'is_from_admin' => $msg->is_from_admin,
                'is_read'       => $msg->is_read,
                'time'          => $msg->created_at->format('H:i'),
                'created_at'    => $msg->created_at->toIso8601String(),
            ],
        ]);
    }

    public function saveFcmToken(Request $request)
    {
        $validated = $request->validate([
            'fcm_token' => 'required|string',
        ]);

        $user   = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();
        $parent->update(['fcm_token' => $validated['fcm_token']]);

        return response()->json(['success' => true]);
    }
}
