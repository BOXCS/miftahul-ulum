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
use Carbon\Carbon;

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
                "nis" => $s->nis,
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
                    "nis" => $s->nis,
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
        $timezone = config('app.timezone');

        if (!is_string($timezone) || $timezone === '') {
            $timezone = 'Asia/Jakarta';
        }

        $start = now($timezone)->startOfWeek()->toDateString();
        $end   = now($timezone)->endOfWeek()->toDateString();

        $records = Attendance::where("student_id", $id)
            ->whereDate("tanggal", ">=", $start)
            ->whereDate("tanggal", "<=", $end)
            ->get()
            ->groupBy(function ($record) use ($timezone) {
                return Carbon::parse($record->tanggal)
                    ->timezone($timezone)
                    ->format("Y-m-d");
            });

        $normalisasiShalat = function ($value) {
            $value = strtolower(trim((string) $value));

            return match ($value) {
                'subuh' => 'Subuh',
                'dzuhur', 'zuhur', 'dhuhur' => 'Dzuhur',
                'ashar', 'asar' => 'Ashar',
                'maghrib' => 'Maghrib',
                'isya', 'isya\'' => 'Isya',
                default => $value,
            };
        };

        $isHadir = function ($record) {
            if (!$record) {
                return false;
            }

            $status = strtolower(trim((string) $record->status));

            return in_array($status, ['hadir', 'terlambat'], true);
        };

        $formatJam = function ($value) {
            if (!$value) {
                return null;
            }

            try {
                return Carbon::parse($value)->format("H:i:s");
            } catch (\Exception $e) {
                return null;
            }
        };

        $attendance = collect();

        $currentDate = Carbon::parse($start, $timezone);
        $endDate = Carbon::parse($end, $timezone);

        while ($currentDate->lte($endDate)) {
            $tanggal = $currentDate->format("Y-m-d");

            $dayRecords = $records->get($tanggal, collect())
                ->keyBy(function ($record) use ($normalisasiShalat) {
                    return $normalisasiShalat($record->waktu_shalat);
                });

            $subuh   = $dayRecords->get("Subuh");
            $dzuhur  = $dayRecords->get("Dzuhur");
            $ashar   = $dayRecords->get("Ashar");
            $maghrib = $dayRecords->get("Maghrib");
            $isya    = $dayRecords->get("Isya");

            $totalHadir = collect([$subuh, $dzuhur, $ashar, $maghrib, $isya])
                ->filter(fn($record) => $isHadir($record))
                ->count();

            $attendance->push([
                "tanggal" => $tanggal,
                "jumlah_kehadiran" => $totalHadir,

                "Subuh" => $isHadir($subuh) ? 1 : 0,
                "Dzuhur" => $isHadir($dzuhur) ? 1 : 0,
                "Ashar" => $isHadir($ashar) ? 1 : 0,
                "Maghrib" => $isHadir($maghrib) ? 1 : 0,
                "Isya" => $isHadir($isya) ? 1 : 0,

                "jam_masuk_subuh" => $formatJam($subuh?->jam_masuk),
                "jam_keluar_subuh" => $formatJam($subuh?->jam_keluar),

                "jam_masuk_dzuhur" => $formatJam($dzuhur?->jam_masuk),
                "jam_keluar_dzuhur" => $formatJam($dzuhur?->jam_keluar),

                "jam_masuk_ashar" => $formatJam($ashar?->jam_masuk),
                "jam_keluar_ashar" => $formatJam($ashar?->jam_keluar),

                "jam_masuk_maghrib" => $formatJam($maghrib?->jam_masuk),
                "jam_keluar_maghrib" => $formatJam($maghrib?->jam_keluar),

                "jam_masuk_isya" => $formatJam($isya?->jam_masuk),
                "jam_keluar_isya" => $formatJam($isya?->jam_keluar),
            ]);

            $currentDate->addDay();
        }

        return response()->json([
            "success" => true,
            "message" => "OK",
            "data" => $attendance->values(),
        ]);
    }

    public function kehadiranSummary($id)
    {
        $timezone = config('app.timezone');

        if (!is_string($timezone) || $timezone === '') {
            $timezone = 'Asia/Jakarta';
        }

        $start = now($timezone)->startOfWeek()->toDateString();

        // Pakai hari ini, bukan endOfWeek.
        // Supaya Senin hanya dihitung 5 shalat, bukan langsung 35 shalat.
        $today = now($timezone)->toDateString();

        $shalatList = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];

        $records = Attendance::where('student_id', $id)
            ->whereBetween('tanggal', [$start, $today])
            ->orderBy('tanggal')
            ->orderBy('waktu_shalat')
            ->get()
            ->unique(function ($record) {
                return $record->tanggal . '-' . $record->waktu_shalat;
            })
            ->values();

        $totalHadir = $records
            ->whereIn('status', ['hadir', 'terlambat'])
            ->count();

        $totalIzin = $records
            ->where('status', 'izin')
            ->count();

        $totalSakit = $records
            ->where('status', 'sakit')
            ->count();

        $totalAlphaTercatat = $records
            ->where('status', 'alpha')
            ->count();

        $jumlahHari = \Carbon\Carbon::parse($start)
            ->diffInDays(\Carbon\Carbon::parse($today)) + 1;

        $totalShalat = $jumlahHari * count($shalatList);

        $totalTerisi = $totalHadir + $totalIzin + $totalSakit + $totalAlphaTercatat;

        $alphaKosong = max($totalShalat - $totalTerisi, 0);

        $totalAlpha = $totalAlphaTercatat + $alphaKosong;

        $persentase = $totalShalat > 0
            ? round(($totalHadir / $totalShalat) * 100, 2)
            : 0;

        return response()->json([
            'success' => true,
            'data' => [
                'hadir' => $totalHadir,
                'izin' => $totalIzin,
                'alpha' => $totalAlpha,
                'sakit' => $totalSakit,
                'total_shalat' => $totalShalat,
                'persentase' => $persentase,
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
                    "is_from_admin" => (bool) $m->is_from_admin,
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
            "is_from_admin" => false,
            "is_read"      => false,
        ]);

        broadcast(new \App\Events\MessageSent($msg))->toOthers();

        return response()->json([
            "success" => true,
            "message" => [
                "id"           => (string) $msg->id,
                "pesan"        => $msg->pesan,
                "is_from_admin" => false,
                "created_at"   => $msg->created_at->toIso8601String(),
            ],
        ]);
    }
}
