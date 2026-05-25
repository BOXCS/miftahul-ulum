<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ParentModel;
use App\Models\Student;
use App\Models\Announcement;
use App\Models\ChatMessage;
use App\Models\Attendance;
use App\Models\Permission;
use App\Models\Faq;
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
        // Hanya pengumuman yg sudah dipublish, terbaru di atas
        $announcements = Announcement::where('is_published', true)
            ->orderByDesc('published_at')
            ->get()
            ->map(fn($a) => [
                'id'           => (int) $a->id,
                'judul'        => $a->judul,
                'isi'          => $a->konten,         // mobile baca 'isi'
                'konten'       => $a->konten,         // backward-compat
                'kategori'     => $a->kategori,
                'foto'         => null,
                'tgl_mulai'    => optional($a->published_at)->toIso8601String(),
                'tgl_selesai'  => null,                // null = no expiry
                'is_published' => (bool) $a->is_published,
                'published_at' => optional($a->published_at)->toIso8601String(),
                'created_at'   => optional($a->created_at)->toIso8601String(),
                'updated_at'   => optional($a->updated_at)->toIso8601String(),
            ]);
        return response()->json($announcements);
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
        $records = Attendance::where("student_id", $id)
            ->whereBetween("tanggal", [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ])
            ->get()
            ->groupBy("tanggal");

        $attendance = $records->map(function ($dayRecords) {
            $dayRecords = $dayRecords->keyBy("waktu_shalat");
            $totalHadir = $dayRecords->filter(fn($r) => in_array($r->status, ["hadir", "terlambat"]))->count();

            $getRecord = fn($shalat) => $dayRecords->get($shalat);

            return [
                "tanggal" => $dayRecords->first()->tanggal->format("Y-m-d"),
                "jumlah_kehadiran" => $totalHadir,
                "Subuh" => $getRecord("Subuh") && in_array($getRecord("Subuh")->status, ["hadir", "terlambat"]) ? 1 : 0,
                "Dzuhur" => $getRecord("Dzuhur") && in_array($getRecord("Dzuhur")->status, ["hadir", "terlambat"]) ? 1 : 0,
                "Ashar" => $getRecord("Ashar") && in_array($getRecord("Ashar")->status, ["hadir", "terlambat"]) ? 1 : 0,
                "Maghrib" => $getRecord("Maghrib") && in_array($getRecord("Maghrib")->status, ["hadir", "terlambat"]) ? 1 : 0,
                "Isya" => $getRecord("Isya") && in_array($getRecord("Isya")->status, ["hadir", "terlambat"]) ? 1 : 0,
                "jam_masuk_subuh" => $getRecord("Subuh")?->jam_masuk?->toTimeString(),
                "jam_keluar_subuh" => $getRecord("Subuh")?->jam_keluar?->toTimeString(),
                "jam_masuk_dzuhur" => $getRecord("Dzuhur")?->jam_masuk?->toTimeString(),
                "jam_keluar_dzuhur" => $getRecord("Dzuhur")?->jam_keluar?->toTimeString(),
                "jam_masuk_ashar" => $getRecord("Ashar")?->jam_masuk?->toTimeString(),
                "jam_keluar_ashar" => $getRecord("Ashar")?->jam_keluar?->toTimeString(),
                "jam_masuk_maghrib" => $getRecord("Maghrib")?->jam_masuk?->toTimeString(),
                "jam_keluar_maghrib" => $getRecord("Maghrib")?->jam_keluar?->toTimeString(),
                "jam_masuk_isya" => $getRecord("Isya")?->jam_masuk?->toTimeString(),
                "jam_keluar_isya" => $getRecord("Isya")?->jam_keluar?->toTimeString(),
            ];
        })->values();

        return response()->json([
            "success" => true,
            "message" => "OK",
            "data" => $attendance,
        ]);
    }

    public function kehadiranSummary($id)
    {
        $totalHadir = Attendance::where("student_id", $id)
            ->whereIn("status", ["hadir", "terlambat"])
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

    /**
     * GET /api/perizinan/{santriId}
     * Riwayat perizinan untuk satu santri (digunakan mobile).
     * Format response disesuaikan dengan Perizinan.fromJson() di Flutter.
     */
    public function perizinan($id)
    {
        $permissions = Permission::where("student_id", $id)
            ->latest("created_at")
            ->get()
            ->map(fn($p) => [
                "id"              => (int) $p->id,
                "student_id"      => (int) $p->student_id,
                "jenis"           => $p->jenis,
                "tanggal_mulai"   => optional($p->tanggal_mulai)->format("Y-m-d"),
                "tanggal_selesai" => optional($p->tanggal_selesai)->format("Y-m-d"),
                "keterangan"      => $p->keterangan,
                "status"          => $p->status,
                "approved_by"     => $p->approved_by,
                "approved_at"     => optional($p->approved_at)->toIso8601String(),
                "catatan"         => $p->catatan,
                "created_at"      => optional($p->created_at)->toIso8601String(),
                "updated_at"      => optional($p->updated_at)->toIso8601String(),
            ]);

        return response()->json([
            "success" => true,
            "message" => "OK",
            "data"    => $permissions,
        ]);
    }

    /**
     * POST /api/perizinan
     * Submit perizinan baru dari mobile (wali santri).
     * Body: { student_id, jenis, tanggal_mulai, tanggal_selesai, keterangan }
     */
    public function submitPerizinan(Request $request): \Illuminate\Http\JsonResponse
    {
        $validated = $request->validate([
            "student_id"      => "required|exists:students,id",
            "jenis"           => "required|in:keluar,pulang,kegiatan,sakit",
            "tanggal_mulai"   => "required|date",
            "tanggal_selesai" => "required|date|after_or_equal:tanggal_mulai",
            "keterangan"      => "nullable|string|max:1000",
        ]);

        $permission = Permission::create($validated + ["status" => "pending"]);

        // Broadcast realtime ke channel admin agar dashboard otomatis update
        broadcast(new \App\Events\PermissionSubmitted($permission));

        return response()->json([
            "success" => true,
            "message" => "Permintaan izin berhasil diajukan, menunggu persetujuan admin.",
            "data" => [
                "id"              => (int) $permission->id,
                "student_id"      => (int) $permission->student_id,
                "jenis"           => $permission->jenis,
                "tanggal_mulai"   => optional($permission->tanggal_mulai)->format("Y-m-d"),
                "tanggal_selesai" => optional($permission->tanggal_selesai)->format("Y-m-d"),
                "keterangan"      => $permission->keterangan,
                "status"          => $permission->status,
                "created_at"      => optional($permission->created_at)->toIso8601String(),
            ],
        ], 201);
    }

    /**
     * GET /api/perizinan-by-ortu/{parentId}
     * Riwayat perizinan untuk semua anak dari satu wali santri.
     */
    public function perizinanByOrtu($parentId): \Illuminate\Http\JsonResponse
    {
        $studentIds = Student::where("parent_id", $parentId)->pluck("id");

        $permissions = Permission::with("student")
            ->whereIn("student_id", $studentIds)
            ->latest("created_at")
            ->get()
            ->map(fn($p) => [
                "id"              => (int) $p->id,
                "student_id"      => (int) $p->student_id,
                "santri_nama"     => $p->student?->name,
                "jenis"           => $p->jenis,
                "tanggal_mulai"   => optional($p->tanggal_mulai)->format("Y-m-d"),
                "tanggal_selesai" => optional($p->tanggal_selesai)->format("Y-m-d"),
                "keterangan"      => $p->keterangan,
                "status"          => $p->status,
                "approved_by"     => $p->approved_by,
                "approved_at"     => optional($p->approved_at)->toIso8601String(),
                "catatan"         => $p->catatan,
                "created_at"      => optional($p->created_at)->toIso8601String(),
                "updated_at"      => optional($p->updated_at)->toIso8601String(),
            ]);

        return response()->json([
            "success" => true,
            "data"    => $permissions,
        ]);
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

        // Validasi channel — wali santri hanya boleh akses channel miliknya
        $allowed = false;

        if ($channelName === "private-chat.{$parentId}") {
            $allowed = true;
        } elseif (preg_match('/^private-santri\.(\d+)$/', $channelName, $sm)) {
            // Cek apakah santri tersebut anak dari wali ini
            $studentId = (int) $sm[1];
            $allowed = Student::where('id', $studentId)
                ->where('parent_id', $parentId)
                ->exists();
        }

        if (!$allowed) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $appKey    = config('broadcasting.connections.reverb.key');
        $appSecret = config('broadcasting.connections.reverb.secret');

        $signature = hash_hmac('sha256', "{$socketId}:{$channelName}", $appSecret);

        return response()->json(['auth' => "{$appKey}:{$signature}"]);
    }

    /**
     * GET /api/faq?search=...&kategori=...
     * Mengembalikan semua FAQ aktif untuk ditampilkan di mobile.
     * Format response disesuaikan dengan FaqResponse.fromJson() di Flutter.
     */
    public function getFaqs(Request $request): \Illuminate\Http\JsonResponse
    {
        $search   = trim((string) $request->query('search', ''));
        $kategori = trim((string) $request->query('kategori', ''));

        $query = Faq::query()->where('is_active', true);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('pertanyaan', 'like', "%{$search}%")
                  ->orWhere('jawaban', 'like', "%{$search}%");
            });
        }

        if ($kategori !== '') {
            $query->where('kategori', $kategori);
        }

        $faqs = $query
            ->orderBy('kategori')
            ->orderBy('urutan')
            ->orderBy('id')
            ->get()
            ->map(fn($f) => [
                'id'         => (int) $f->id,
                'pertanyaan' => $f->pertanyaan,
                'jawaban'    => $f->jawaban,
                'kategori'   => $f->kategori,
                'urutan'     => (int) ($f->urutan ?? 0),
                'is_active'  => (bool) $f->is_active,
            ])
            ->values();

        // Daftar semua kategori unik (dari FAQ aktif) untuk dropdown filter mobile
        $kategoris = Faq::where('is_active', true)
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->filter()
            ->values();

        return response()->json([
            'success'   => true,
            'data'      => $faqs,
            'kategoris' => $kategoris,
            'total'     => $faqs->count(),
        ]);
    }

    /**
     * GET /api/faq/{id} — detail satu FAQ.
     */
    public function getFaqById($id): \Illuminate\Http\JsonResponse
    {
        $faq = Faq::where('is_active', true)->find($id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'id'         => (int) $faq->id,
                'pertanyaan' => $faq->pertanyaan,
                'jawaban'    => $faq->jawaban,
                'kategori'   => $faq->kategori,
                'urutan'     => (int) ($faq->urutan ?? 0),
                'is_active'  => (bool) $faq->is_active,
            ],
        ]);
    }

    public function chatHistory($parentId)
    {
        // Tandai pesan dari admin sebagai sudah dibaca oleh mobile
        ChatMessage::where("parent_id", $parentId)
            ->where("is_from_admin", true)
            ->where("is_read", false)
            ->update(["is_read" => true, "read_at" => now()]);

        $messages = ChatMessage::where("parent_id", $parentId)
            ->oldest()
            ->get()
            ->map(
                fn($m) => [
                    "id"           => (string) $m->id,
                    "pesan"        => $m->pesan,
                    "is_from_admin"=> (bool) $m->is_from_admin,
                    "created_at"   => $m->created_at->toIso8601String(),
                ],
            );
        return response()->json($messages);
    }

    public function sendMessage(Request $request, $parentId)
    {
        $request->validate([
            "pesan" => "required|string|max:1000",
        ]);

        $msg = ChatMessage::create([
            "parent_id"    => $parentId,
            "pesan"        => $request->pesan,
            "is_from_admin"=> false,
            "is_read"      => false,
        ]);

        broadcast(new \App\Events\MessageSent($msg))->toOthers();

        return response()->json([
            "success" => true,
            "message" => [
                "id"           => (string) $msg->id,
                "pesan"        => $msg->pesan,
                "is_from_admin"=> false,
                "created_at"   => $msg->created_at->toIso8601String(),
            ],
        ]);
    }
}