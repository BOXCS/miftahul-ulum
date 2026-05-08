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
                "id_santri"      => (string) $s->id,
                "nis"            => $s->nis ?? '',
                "nama"           => $s->name,
                "kelas"          => $s->class ?? null,
                "kamar"          => $s->kamar ?? null,
                "tahun_angkatan" => $s->class,
                "sidik_jari"     => null,
                "status"         => $s->status ?? "aktif",
                "id_ortu"        => (int) ($s->parent_id ?? 0),
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
                    "id_santri"      => (string) $s->id,
                    "nis"            => $s->nis ?? '',        // ← tambah
                    "nama"           => $s->name,
                    "kelas"          => $s->class ?? null,    // ← tambah
                    "kamar"          => $s->kamar ?? null,    // ← tambah
                    "tahun_angkatan" => $s->tahun_angkatan ?? $s->class,
                    "sidik_jari"     => null,
                    "status"         => $s->status ?? "aktif",
                    "id_ortu"        => (int) $s->parent_id,
                    "ortu"           => $s->parent ? [
                        "nama_lengkap" => $s->parent->name,
                        "alamat"       => $s->parent->address,
                        "no_telp"      => $s->parent->phone,
                    ] : null,
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

    // Ganti seluruh function kehadiranSummary:
    public function kehadiranSummary($id)
    {
        $now = now();

        // === SEMINGGU ===
        $semingguStart = $now->copy()->startOfWeek();
        $semingguEnd   = $now->copy()->endOfWeek();
        $semingguRows  = Attendance::where('student_id', $id)
            ->whereBetween('tanggal', [$semingguStart, $semingguEnd])
            ->get();
        $semingguTotal  = $semingguRows->count() * 5; // 5 shalat per hari
        $semingguHadir  = $semingguRows->sum(function ($a) {
            return ($a->Subuh ?? 0) + ($a->Dzuhur ?? 0) + ($a->Ashar ?? 0)
                + ($a->Maghrib ?? 0) + ($a->Isya ?? 0);
        });
        $semingguPersen = $semingguTotal > 0
            ? round(($semingguHadir / $semingguTotal) * 100, 1)
            : 0;

        // === SEBULAN ===
        $sebulanStart = $now->copy()->startOfMonth();
        $sebulanEnd   = $now->copy()->endOfMonth();
        $sebulanRows  = Attendance::where('student_id', $id)
            ->whereBetween('tanggal', [$sebulanStart, $sebulanEnd])
            ->get();
        $sebulanTotal  = $sebulanRows->count() * 5;
        $sebulanHadir  = $sebulanRows->sum(function ($a) {
            return ($a->Subuh ?? 0) + ($a->Dzuhur ?? 0) + ($a->Ashar ?? 0)
                + ($a->Maghrib ?? 0) + ($a->Isya ?? 0);
        });
        $sebulanPersen = $sebulanTotal > 0
            ? round(($sebulanHadir / $sebulanTotal) * 100, 1)
            : 0;

        // === SETAHUN ===
        $setahunStart = $now->copy()->startOfYear();
        $setahunEnd   = $now->copy()->endOfYear();
        $setahunRows  = Attendance::where('student_id', $id)
            ->whereBetween('tanggal', [$setahunStart, $setahunEnd])
            ->get();
        $setahunTotal  = $setahunRows->count() * 5;
        $setahunHadir  = $setahunRows->sum(function ($a) {
            return ($a->Subuh ?? 0) + ($a->Dzuhur ?? 0) + ($a->Ashar ?? 0)
                + ($a->Maghrib ?? 0) + ($a->Isya ?? 0);
        });
        $setahunPersen = $setahunTotal > 0
            ? round(($setahunHadir / $setahunTotal) * 100, 1)
            : 0;

        return response()->json([
            'success' => true,
            'message' => 'OK',
            'data' => [
                'seminggu' => [
                    'total_hadir'  => $semingguHadir,
                    'total_shalat' => $semingguTotal,
                    'persentase'   => $semingguPersen,
                ],
                'sebulan' => [
                    'total_hadir'  => $sebulanHadir,
                    'total_shalat' => $sebulanTotal,
                    'persentase'   => $sebulanPersen,
                ],
                'setahun' => [
                    'total_hadir'  => $setahunHadir,
                    'total_shalat' => $setahunTotal,
                    'persentase'   => $setahunPersen,
                ],
            ],
        ]);
    }

    public function perizinan($id)
    {
        $permissions = Permission::where("student_id", $id)->get();
        return response()->json(["success" => true, "data" => $permissions]);
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
