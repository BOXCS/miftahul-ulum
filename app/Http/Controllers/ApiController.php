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
    /**
     * Login menggunakan email/password dan generate Sanctum token.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                "success" => false,
                "message" => "Email atau password salah.",
            ], 401);
        }

        $parent = ParentModel::where('user_id', $user->id)->first();

        if (!$parent) {
            return response()->json([
                "success" => false,
                "message" => "Akun ini bukan orang tua santri.",
            ], 403);
        }

        // Hapus token lama & buat token baru
        $user->tokens()->delete();
        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            "success" => true,
            "token" => $token,
            "akun" => [
                "id_akun" => $parent->id,
                "email" => $user->email,
                "username" => $user->name,
                "relationship" => $parent->relationship ?? null,
                "phone" => $parent->phone,
                "address" => $parent->address ?? "",
                "hak_akses" => $parent->role ?? "ortu",
            ],
        ]);
    }

    public function pengumuman()
    {
        return response()->json(Announcement::latest()->get());
    }

    public function faq(Request $request)
    {
        $query = Faq::where('is_active', true)
            ->orderBy('urutan')
            ->orderBy('id');

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qb) use ($q) {
                $qb->where('pertanyaan', 'like', "%{$q}%")
                    ->orWhere('jawaban', 'like', "%{$q}%")
                    ->orWhere('kategori', 'like', "%{$q}%");
            });
        }

        $faqs = $query->get()->map(fn($f) => [
            'id' => $f->id,
            'pertanyaan' => $f->pertanyaan,
            'jawaban' => $f->jawaban,
            'kategori' => $f->kategori,
            'urutan' => $f->urutan,
            'is_active' => $f->is_active,
        ]);

        $kategoris = Faq::where('is_active', true)
            ->select('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $faqs,
            'kategoris' => $kategoris,
            'total' => $faqs->count(),
        ]);
    }

    public function faqDetail($id)
    {
        $faq = Faq::where('is_active', true)->find($id);

        if (!$faq) {
            return response()->json([
                'success' => false,
                'message' => 'FAQ tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $faq->id,
                'pertanyaan' => $faq->pertanyaan,
                'jawaban' => $faq->jawaban,
                'kategori' => $faq->kategori,
                'urutan' => $faq->urutan,
                'is_active' => $faq->is_active,
            ],
        ]);
    }

    public function santriById(Request $request, $id)
    {
        $user = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

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
                "nis" => $s->nis ?? '',
                "nama" => $s->name,
                "kelas" => $s->class ?? null,
                "kamar" => $s->kamar ?? null,
                "tahun_angkatan" => $s->class,
                "sidik_jari" => null,
                "status" => $s->status ?? "aktif",
                "id_ortu" => (int) ($s->parent_id ?? 0),
                "ortu" => $s->parent ? [
                    "nama_lengkap" => $s->parent->name,
                    "alamat" => $s->parent->address,
                    "no_telp" => $s->parent->phone,
                ] : null,
            ],
        ]);
    }

    public function santriByOrtu(Request $request, $id)
    {
        $user = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        $students = Student::where("parent_id", $id)
            ->with("parent")
            ->get()
            ->map(fn($s) => [
                "id_santri" => (string) $s->id,
                "nis" => $s->nis ?? '',
                "nama" => $s->name,
                "kelas" => $s->class ?? null,
                "kamar" => $s->kamar ?? null,
                "tahun_angkatan" => $s->tahun_angkatan ?? $s->class,
                "sidik_jari" => null,
                "status" => $s->status ?? "aktif",
                "id_ortu" => (int) $s->parent_id,
                "ortu" => $s->parent ? [
                    "nama_lengkap" => $s->parent->name,
                    "alamat" => $s->parent->address,
                    "no_telp" => $s->parent->phone,
                ] : null,
            ]);

        return response()->json([
            "success" => true,
            "message" => "Data santri berhasil diambil",
            "data" => $students,
        ]);
    }

    public function kehadiranMingguan(Request $request, $id)
    {
        $user = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        $attendance = Attendance::where("student_id", $id)
            ->whereBetween("tanggal", [now()->startOfWeek(), now()->endOfWeek()])
            ->get()
            ->map(fn($a) => [
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
            ]);

        return response()->json([
            "success" => true,
            "message" => "OK",
            "data" => $attendance,
        ]);
    }

    public function kehadiranSummary(Request $request, $id)
    {
        $user = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        // Implementasi sama seperti versi sebelumnya
        // ... (tidak ada perubahan struktur response)
    }

    public function perizinan(Request $request, $id)
    {
        $user = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        $permissions = Permission::where("student_id", $id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($p) => [
                "id" => $p->id,
                "student_id" => $p->student_id,
                "jenis" => $p->jenis,
                "tanggal_mulai" => $p->tanggal_mulai->format('Y-m-d'),
                "tanggal_selesai" => $p->tanggal_selesai->format('Y-m-d'),
                "keterangan" => $p->keterangan,
                "catatan" => $p->catatan,
                "status" => $p->status,
                "approved_by" => $p->approved_by,
                "approved_at" => $p->approved_at?->toIso8601String(),
                "created_at" => $p->created_at->toIso8601String(),
            ]);

        return response()->json(['success' => true, 'data' => $permissions]);
    }

    public function storePerizinan(Request $request)
    {
        // Validasi — mengikuti skema DB web sebagai source of truth
        $validated = $request->validate([
            'student_id'      => 'required|integer|exists:students,id',
            'jenis'           => 'required|in:keluar,pulang,kegiatan,sakit',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'nullable|string|max:1000',
        ]);

        // Simpan — status selalu 'pending' dari mobile, admin yang approve/reject
        $permission = Permission::create([
            'student_id'      => $validated['student_id'],
            'jenis'           => $validated['jenis'],
            'tanggal_mulai'   => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'keterangan'      => $validated['keterangan'] ?? null,
            'status'          => 'pending',
        ]);

        $permission->load('student');

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan izin berhasil dikirim. Menunggu persetujuan admin.',
            'data'    => [
                'id'              => $permission->id,
                'student_id'      => $permission->student_id,
                'nama_santri'     => $permission->student?->name ?? 'N/A',
                'jenis'           => $permission->jenis,
                'tanggal_mulai'   => $permission->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $permission->tanggal_selesai->format('Y-m-d'),
                'keterangan'      => $permission->keterangan,
                'status'          => $permission->status,
                'created_at'      => $permission->created_at->toIso8601String(),
            ],
        ], 201);
    }

    /**
     * GET /api/perizinan/riwayat/{student_id}
     *
     * Riwayat perizinan lengkap satu santri — dipakai mobile untuk menampilkan
     * daftar pengajuan beserta status terkini (pending/disetujui/ditolak).
     * Diurutkan dari terbaru.
     *
     * Response 200:
     * {
     *   "success": true,
     *   "data"   : [ { id, jenis, tanggal_mulai, tanggal_selesai, keterangan,
     *                  status, approved_by, approved_at, created_at }, ... ]
     * }
     */

    public function riwayatPerizinan($studentId)
    {
        $student = Student::find($studentId);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Santri tidak ditemukan.',
                'data'    => [],
            ], 404);
        }

        $permissions = Permission::where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($p) => [
                'id'              => $p->id,
                'jenis'           => $p->jenis,
                'tanggal_mulai'   => $p->tanggal_mulai->format('Y-m-d'),
                'tanggal_selesai' => $p->tanggal_selesai->format('Y-m-d'),
                'keterangan'      => $p->keterangan,
                'catatan'         => $p->catatan,
                'status'          => $p->status,
                'approved_by'     => $p->approved_by,
                'approved_at'     => $p->approved_at?->toIso8601String(),
                'created_at'      => $p->created_at->toIso8601String(),
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat perizinan berhasil diambil.',
            'data'    => $permissions,
        ]);
    }

    /**
     * Endpoint auth channel Reverb khusus untuk mobile (tidak pakai session Laravel).
     * Dipanggil otomatis oleh pusher_channels_flutter saat subscribe ke private channel.
     * Authorization: Bearer dummy-token-{parentId}
     */

    public function broadcastAuth(Request $request): \Illuminate\Http\JsonResponse
    {
        // Ambil user yang sudah terautentikasi via Sanctum
        $user = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        $channelName = $request->input('channel_name', '');
        $socketId = $request->input('socket_id', '');

        // Wali santri hanya boleh subscribe ke channel miliknya
        if ($channelName !== "private-chat.{$parent->id}") {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        $appKey = config('broadcasting.connections.reverb.key');
        $appSecret = config('broadcasting.connections.reverb.secret');

        $signature = hash_hmac('sha256', "{$socketId}:{$channelName}", $appSecret);

        return response()->json(['auth' => "{$appKey}:{$signature}"]);
    }

    public function chatHistory(Request $request)
    {
        $user = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        $messages = ChatMessage::where("parent_id", $parent->id)
            ->oldest()
            ->get()
            ->map(fn($m) => [
                "pesan" => $m->pesan,
                "is_from_admin" => $m->is_from_admin,
                "created_at" => $m->created_at->toIso8601String(),
            ]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $user = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        $msg = ChatMessage::create([
            "parent_id" => $parent->id,
            "pesan" => $request->pesan,
            "is_from_admin" => false,
            "is_read" => false,
        ]);

        broadcast(new \App\Events\MessageSent($msg))->toOthers();

        return response()->json(["success" => true, "message" => $msg]);
    }

    public function saveFcmToken(Request $request)
    {
        $user = $request->user();
        $parent = ParentModel::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate(['fcm_token' => 'required|string']);

        $parent->update(['fcm_token' => $validated['fcm_token']]);

        return response()->json(['success' => true]);
    }
}
