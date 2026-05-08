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
        // Validasi input
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        // Cari Parent berdasarkan email
        $parent = ParentModel::where("email", $credentials["email"])->first();

        if (!$parent || !Hash::check($credentials["password"], $parent->password)) {
            return response()->json([
                "success" => false,
                "message" => "Email atau password salah.",
            ], 401);
        }

        return response()->json([
            "success" => true,
            "token"   => "dummy-token-" . $parent->id,
            "akun" => [
                "id_akun"      => $parent->id,
                "email"        => $parent->email,
                "username"     => $parent->name,
                "relationship" => $parent->relationship,
                "phone"        => $parent->phone,
                "address"      => $parent->address ?? "",
                "hak_akses"    => $parent->role ?? "ortu",
            ],
        ]);
    }



    public function pengumuman()
    {
        return response()->json(Announcement::latest()->get());
    }

    public function santriById($id)
    {
        $s = Student::find($id);

        if (!$s) {
            return response()->json([
                "success" => false,
                "message" => "Not found",
                "data" => null,
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "OK",
            "data" => [
                "id_santri" => (string) $s->id,
                "nama" => $s->name,
                "tahun_angkatan" => $s->class,
                "sidik_jari" => null,
                "status" => $s->status ?? "aktif",
                "id_ortu" => (int) ($s->parent_id ?? 0),
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

    /**
     * Endpoint auth channel Reverb khusus untuk mobile (tidak pakai session Laravel).
     * Dipanggil otomatis oleh pusher_channels_flutter saat subscribe ke private channel.
     * Authorization: Bearer dummy-token-{parentId}
     */
    public function broadcastAuth(Request $request): \Illuminate\Http\JsonResponse
    {
        $authHeader = $request->header('Authorization', '');
        if (!str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $token = substr($authHeader, 7);

        // Format token: "dummy-token-{parentId}"
        if (!preg_match('/^dummy-token-(\d+)$/', $token, $matches)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $parentId    = (int) $matches[1];
        $channelName = $request->input('channel_name', '');
        $socketId    = $request->input('socket_id', '');

        // Wali santri hanya boleh subscribe ke channel miliknya
        if ($channelName !== "private-chat.{$parentId}") {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $appKey    = config('broadcasting.connections.reverb.key');
        $appSecret = config('broadcasting.connections.reverb.secret');

        $signature = hash_hmac('sha256', "{$socketId}:{$channelName}", $appSecret);

        return response()->json(['auth' => "{$appKey}:{$signature}"]);
    }

    public function chatHistory($parentId)
    {
        $messages = ChatMessage::where("parent_id", $parentId)
            ->oldest()
            ->get()
            ->map(
                fn($m) => [
                    "pesan" => $m->pesan,
                    "is_from_admin" => $m->is_from_admin,
                    "created_at" => $m->created_at->toIso8601String(),
                ],
            );
        return response()->json($messages);
    }

    public function sendMessage(Request $request, $parentId)
    {
        $msg = ChatMessage::create([
            "parent_id" => $parentId,
            "pesan" => $request->pesan,
            "is_from_admin" => false,
            "is_read" => false,
        ]);

        broadcast(new \App\Events\MessageSent($msg))->toOthers();

        return response()->json(["success" => true, "message" => $msg]);
    }
}
