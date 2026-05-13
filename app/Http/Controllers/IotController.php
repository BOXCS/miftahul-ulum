<?php

namespace App\Http\Controllers;

use App\Events\IotScanResult;
use App\Models\Attendance;
use App\Models\IotCommand;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Controller untuk semua interaksi antara web/admin dan perangkat ESP32.
 *
 * ALUR ENROLLMENT (web → ESP32):
 *   1. Admin klik tombol "Scan" di /students → POST /api/iot/enroll-request
 *   2. Server buat IotCommand status=pending (alokasi fingerprint_id baru)
 *   3. ESP32 polling GET /api/iot/poll → terima command 'enroll'
 *   4. ESP32 masuk mode enrollment, simpan template di sensor pakai fingerprint_id tsb
 *   5. ESP32 POST /api/iot/enroll-complete → server update student.fingerprint_id
 *   6. Server broadcast IotScanResult kind=enroll → modal admin tampil status sukses
 *
 * ALUR ABSENSI (ESP32 → web/mobile):
 *   1. ESP32 scan sidik jari → POST /api/absensi/fingerprint
 *   2. Server cocokkan fingerprint_id → student, tentukan waktu shalat sekarang
 *   3. Server insert Attendance (status hadir/terlambat) + cooldown check
 *   4. Server broadcast IotScanResult kind=attendance → modal admin update real-time
 *   5. Server return JSON ke ESP32 (untuk LCD/buzzer feedback)
 */
class IotController extends Controller
{
    /**
     * GET /api/iot/ping
     * Endpoint identifier — ESP32 pakai ini untuk auto-discover server
     * di subnet WiFi yang sama (scan IP, cari yang return signature ini).
     */
    public function ping(): JsonResponse
    {
        return response()->json([
            'server'  => 'miftahul_ulum',
            'service' => 'fingerprint',
            'version' => 'v2',
        ]);
    }

    /** Anti-fraud cooldown antar tap untuk santri yg sama (menit) */
    private const ANTIFRAUD_COOLDOWN_MIN = 5;

    /** Command expired setelah berapa detik tanpa diproses ESP32 */
    private const COMMAND_TIMEOUT_SEC = 60;

    /** Timezone pesantren — bisa di-override via .env IOT_TIMEZONE */
    private function tz(): string
    {
        return env('IOT_TIMEZONE', 'Asia/Jakarta');
    }

    /** Lokasi & metode jadwal sholat — bisa di-override via .env */
    private function prayerCity(): string
    {
        return env('IOT_CITY', 'Jember');
    }
    private function prayerCountry(): string
    {
        return env('IOT_COUNTRY', 'Indonesia');
    }
    private function prayerMethod(): int
    {
        return (int) env('IOT_PRAYER_METHOD', 20);
    }
    private function TIMEZONE_(): string
    {
        return $this->tz();
    }

    /** Backward-compat constant references */
    private const TIMEZONE = 'Asia/Jakarta'; // fallback constant — code now uses $this->tz()

    /**
     * Mapping nama lokal → key Aladhan API.
     * Window setiap sholat extend sampai adzan sholat berikutnya
     * (sesuai praktik Islami: Dzuhur valid sampai adzan Ashar, dst).
     * Buffer 1 menit sebelum adzan berikutnya.
     */
    private const PRAYER_ORDER = [
        'Subuh'   => 'Fajr',
        'Dzuhur'  => 'Dhuhr',
        'Ashar'   => 'Asr',
        'Maghrib' => 'Maghrib',
        'Isya'    => 'Isha',
    ];

    /** Buffer (menit) sebelum adzan berikutnya */
    private const NEXT_PRAYER_BUFFER_MIN = 1;

    // ──────────────────────────────────────────────────────────
    // 1. POLL — ESP32 cek pending command (GET /api/iot/poll)
    // ──────────────────────────────────────────────────────────
    public function poll(Request $request): JsonResponse
    {
        // Expire command pending yang sudah lewat timeout (auto cleanup)
        IotCommand::where('status', 'pending')
            ->where('created_at', '<', now()->subSeconds(self::COMMAND_TIMEOUT_SEC))
            ->update(['status' => 'expired']);

        // Ambil 1 command pending paling lama
        $cmd = IotCommand::where('status', 'pending')
            ->orderBy('created_at')
            ->first();

        if (!$cmd) {
            return response()->json(['command' => null]);
        }

        // Tandai sedang diproses agar tidak diambil device lain
        $cmd->update([
            'status'       => 'in_progress',
            'processed_at' => now(),
        ]);

        return response()->json([
            'command' => [
                'id'             => $cmd->id,
                'type'           => $cmd->type,
                'fingerprint_id' => $cmd->fingerprint_id,
                'student_id'     => $cmd->student_id,
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // 2. ENROLL REQUEST — web admin trigger (POST /api/iot/enroll-request)
    // ──────────────────────────────────────────────────────────
    public function enrollRequest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
        ]);

        $student = Student::find($validated['student_id']);

        // Re-enrollment? Pakai fingerprint_id lama. Kalau belum pernah, alokasi baru.
        $fingerprintId = $student->fingerprint_id ?? $this->allocateFingerprintId();

        // Cancel pending command lama untuk santri ini (kalau ada)
        IotCommand::where('student_id', $student->id)
            ->where('status', 'pending')
            ->update(['status' => 'expired']);

        $cmd = IotCommand::create([
            'type'           => 'enroll',
            'student_id'     => $student->id,
            'fingerprint_id' => $fingerprintId,
            'status'         => 'pending',
        ]);

        return response()->json([
            'success'        => true,
            'command_id'     => $cmd->id,
            'fingerprint_id' => $fingerprintId,
            'student'        => [
                'id'   => $student->id,
                'name' => $student->name,
            ],
            'message' => 'Letakkan jari santri di sensor ESP32 untuk memulai pendaftaran.',
        ], 201);
    }

    // ──────────────────────────────────────────────────────────
    // 3. ENROLL COMPLETE — ESP32 report hasil (POST /api/iot/enroll-complete)
    // ──────────────────────────────────────────────────────────
    public function enrollComplete(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'command_id'     => 'required|exists:iot_commands,id',
            'fingerprint_id' => 'required|integer|min:1',
            'success'        => 'required|boolean',
            'message'        => 'nullable|string|max:255',
            'quality'        => 'nullable|integer|min:0|max:100',
        ]);

        $cmd = IotCommand::with('student')->find($validated['command_id']);

        if (!$cmd || $cmd->type !== 'enroll') {
            return response()->json(['success' => false, 'message' => 'Command tidak valid'], 422);
        }

        $student = $cmd->student;

        if ($validated['success'] && $student) {
            $student->update([
                'fingerprint_id'      => $validated['fingerprint_id'],
                'fingerprint_quality' => $validated['quality'] ?? null,
                'scanned_at'          => now(),
            ]);
        }

        $cmd->update([
            'status' => $validated['success'] ? 'done' : 'failed',
            'result' => [
                'success'        => (bool) $validated['success'],
                'message'        => $validated['message'] ?? null,
                'quality'        => $validated['quality'] ?? null,
                'fingerprint_id' => $validated['fingerprint_id'],
            ],
        ]);

        // Broadcast ke admin web agar modal langsung update
        broadcast(new IotScanResult('enroll', [
            'command_id'     => $cmd->id,
            'student_id'     => $student?->id,
            'student_name'   => $student?->name,
            'fingerprint_id' => $validated['fingerprint_id'],
            'quality'        => $validated['quality'] ?? null,
            'success'        => (bool) $validated['success'],
            'message'        => $validated['message'] ?? ($validated['success'] ? 'Berhasil terdaftar' : 'Gagal mendaftar'),
        ]));

        return response()->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────────
    // 4. ABSENSI VIA FINGERPRINT — POST /api/absensi/fingerprint
    // ──────────────────────────────────────────────────────────
    public function attendanceScan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fingerprint_id' => 'required|integer|min:0',
            'matched'        => 'sometimes|boolean',
            'confidence'     => 'sometimes|integer|min:0',
        ]);

        $matched = $request->boolean('matched', $validated['fingerprint_id'] > 0);

        // ── KASUS 1: Sensor tidak match (no template found di sensor) ──
        if (!$matched || $validated['fingerprint_id'] === 0) {
            // Log buat audit — server tahu ada scan attempt
            Log::info('Scan attempt tanpa match di sensor', [
                'ip' => $request->ip(),
                'confidence' => $validated['confidence'] ?? 0,
            ]);
            return response()->json([
                'success' => false,
                'status'  => 'not_recognized',
                'message' => 'Sidik jari belum terdaftar',
            ], 404);
        }

        // ── KASUS 2: Sensor match → cek DB ──
        $student = Student::where('fingerprint_id', $validated['fingerprint_id'])->first();

        // KASUS 3: Sensor punya template tapi DB tidak punya mapping
        // (sensor-DB inconsistency — orphan template)
        if (!$student) {
            Log::warning('Orphan template di sensor', [
                'fingerprint_id' => $validated['fingerprint_id'],
                'confidence' => $validated['confidence'] ?? 0,
            ]);
            return response()->json([
                'success' => false,
                'status'  => 'orphan_template',
                'message' => 'Template ada tapi belum terdaftar. Hubungi admin.',
                'fingerprint_id' => $validated['fingerprint_id'],
            ], 404);
        }

        $now = now($this->tz());

        // ══════ SEMUA RULE ABSENSI DICEK DI SERVER (sumber kebenaran tunggal) ══════

        // ── Validasi 1: Window sholat aktif ──
        $prayer = $this->detectPrayerWindow();
        if (!$prayer) {
            return response()->json([
                'success' => false,
                'status'  => 'out_of_prayer',
                'message' => 'Di luar waktu sholat',
                'student_name' => $student->name,
            ], 422);
        }

        // ── Validasi 2: Anti-fraud via CACHE session ──
        // Key per fingerprint_id → independent per santri, tidak konflik saat
        // banyak santri lintas sholat bersamaan.
        $sessionKey = "iot_session_fp_{$validated['fingerprint_id']}";
        $session    = Cache::get($sessionKey);

        if ($session && isset($session['last_tap_at'])) {
            $lastTap = Carbon::parse($session['last_tap_at'], $this->tz());
            $sinceLastTap = abs($now->diffInMinutes($lastTap, false));
            if ($sinceLastTap < self::ANTIFRAUD_COOLDOWN_MIN) {
                return response()->json([
                    'success'      => false,
                    'status'       => 'cooldown',
                    'message'      => 'Tunggu ' . self::ANTIFRAUD_COOLDOWN_MIN . ' menit antar scan',
                    'student_name' => $student->name,
                ], 429);
            }
        }

        $action      = '';
        $message     = '';
        $statusHadir = 'hadir';
        $crossPrayers = [];

        if ($session && !empty($session['is_open'])) {
            // ════════ TAP KEDUA — tutup sesi ════════
            $startPrayer = $session['start_prayer'];
            $attendance  = Attendance::find($session['attendance_id']);

            if (!$attendance) {
                // Record hilang (mis. di-delete admin), reset cache & treat as new tap
                Cache::forget($sessionKey);
                $session = null;
            } else {
                // Tutup session: set jam_keluar
                $attendance->update(['jam_keluar' => $now]);
                $statusHadir = $attendance->status;

                if ($startPrayer === $prayer['name']) {
                    // Tap keluar di window yang SAMA
                    $action  = 'keluar';
                    $message = 'Keluar dari ' . $prayer['name'];
                } else {
                    // ── LINTAS WAKTU SHOLAT ──
                    // Tap masuk Dzuhur + tap keluar Ashar = hadir Dzuhur + Ashar
                    $orderKeys = array_keys(self::PRAYER_ORDER);
                    $oldIdx    = array_search($startPrayer,     $orderKeys);
                    $newIdx    = array_search($prayer['name'],  $orderKeys);

                    if ($oldIdx !== false && $newIdx !== false && $newIdx > $oldIdx) {
                        // Auto-hadir untuk window di antara + window terakhir
                        for ($i = $oldIdx + 1; $i <= $newIdx; $i++) {
                            $isLastWindow = ($i === $newIdx);
                            Attendance::updateOrCreate(
                                [
                                    'student_id'   => $student->id,
                                    'tanggal'      => $now->copy()->startOfDay(),
                                    'waktu_shalat' => $orderKeys[$i],
                                ],
                                [
                                    'status'     => 'hadir',
                                    'keterangan' => 'Auto lintas waktu sholat (' . $startPrayer . ' → ' . $prayer['name'] . ')',
                                    'jam_keluar' => $isLastWindow ? $now : null,
                                ]
                            );
                            $crossPrayers[] = $orderKeys[$i];
                        }
                    }

                    $action  = 'keluar_lintas';
                    $message = 'Keluar + auto-hadir: ' . implode(', ', array_merge([$startPrayer], $crossPrayers));
                }

                // Bersihkan cache session (sesi selesai)
                Cache::forget($sessionKey);
            }
        }

        if (!$session || empty($session['is_open']) || $action === '') {
            // ════════ TAP PERTAMA — buka sesi baru ════════
            // Cek apakah window saat ini sudah lengkap (sudah masuk+keluar)
            $existing = Attendance::where('student_id', $student->id)
                ->whereDate('tanggal', $now->toDateString())
                ->where('waktu_shalat', $prayer['name'])
                ->first();

            if ($existing && $existing->jam_keluar) {
                return response()->json([
                    'success'      => false,
                    'status'       => 'already_done',
                    'message'      => 'Sudah selesai absen ' . $prayer['name'] . ' hari ini',
                    'student_name' => $student->name,
                ], 422);
            }

            // Tentukan hadir/terlambat (>15 menit dari adzan)
            $start = Carbon::parse($prayer['start_at'], $this->tz());

            $statusHadir = $now->greaterThan($start->copy()->addMinutes(15))
                ? 'terlambat'
                : 'hadir';

            $attendance = Attendance::updateOrCreate(
                [
                    'student_id'   => $student->id,
                    'tanggal'      => $now->copy()->startOfDay(),
                    'waktu_shalat' => $prayer['name'],
                ],
                [
                    'jam_masuk'  => $now,
                    'status'     => $statusHadir,
                    'keterangan' => 'Tap masuk via fingerprint',
                ]
            );

            // ── Simpan sesi ke CACHE per fingerprint_id ──
            // TTL sampai akhir hari — jam tahan untuk lintas waktu sholat sehari penuh.
            Cache::put($sessionKey, [
                'attendance_id' => $attendance->id,
                'student_id'    => $student->id,
                'started_at'    => $now->toDateTimeString(),
                'start_prayer'  => $prayer['name'],
                'last_tap_at'   => $now->toDateTimeString(),
                'is_open'       => true,
            ], now($this->tz())->endOfDay());

            $action  = 'masuk';
            $message = 'Masuk ' . $prayer['name'];
        }

        // Broadcast ke admin web
        broadcast(new IotScanResult('attendance', [
            'attendance_id' => $attendance->id,
            'student_id'    => $student->id,
            'student_name'  => $student->name,
            'kelas'         => $student->class,
            'waktu_shalat'  => $prayer['name'],
            'status'        => $statusHadir,
            'action'        => $action,        // 'masuk' | 'keluar' | 'keluar_lintas'
            'cross_prayers' => $crossPrayers,  // list window yg auto-hadir
            'jam_scan'      => $now->format('H:i:s'),
            'success'       => true,
        ]));

        return response()->json([
            'success'      => true,
            'student_name' => $student->name,
            'kelas'        => $student->class,
            'waktu_shalat' => $prayer['name'],
            'status'       => $statusHadir,
            'action'       => $action,
            'message'      => $message,
        ]);
    }

    // ──────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────

    /** Alokasi fingerprint_id baru (slot kosong terkecil 1..N) */
    private function allocateFingerprintId(): int
    {
        $used = Student::whereNotNull('fingerprint_id')
            ->pluck('fingerprint_id')
            ->toArray();
        for ($i = 1; $i <= 1000; $i++) {
            if (!in_array($i, $used, true)) return $i;
        }
        throw new \RuntimeException('Slot fingerprint sensor sudah penuh');
    }

    /**
     * Deteksi window sholat saat ini berdasarkan jadwal Aladhan API
     * (Kemenag method 20) untuk lokasi pesantren. Hasil dicache 24 jam.
     * Return null kalau di luar semua window.
     */
    private function detectPrayerWindow(): ?array
    {
        $now = now($this->tz());

        // Cek window hari ini dan kemarin.
        // Kemarin perlu dicek karena Isya kemarin bisa berakhir di Subuh hari ini.
        $windows = array_merge(
            $this->buildWindows($now->copy()->subDay()),
            $this->buildWindows($now)
        );

        if (empty($windows)) {
            Log::warning('Prayer windows kosong, fallback hardcoded');
            return $this->detectPrayerWindowFallback($now);
        }

        foreach ($windows as $w) {
            $start = Carbon::parse($w['start_at'], $this->tz());
            $end   = Carbon::parse($w['end_at'], $this->tz());

            if ($now->betweenIncluded($start, $end)) {
                return $w;
            }
        }

        return null;
    }

    /**
     * Bangun array 5 window sholat untuk $date.
     * Tiap window extend SAMPAI adzan sholat BERIKUTNYA (minus buffer).
     * Praktik Islami: Dzuhur valid sampai adzan Ashar, dst.
     * Isya extend sampai sebelum Subuh besok.
     */
    private function buildWindows(Carbon $date): array
    {
        $times = $this->getPrayerTimes($date->format('Y-m-d'));
        if (empty($times)) return [];

        $tz        = $this->tz();
        $localKeys = array_keys(self::PRAYER_ORDER);
        $apiKeys   = array_values(self::PRAYER_ORDER);

        // Untuk batas akhir Isya, butuh Subuh besok
        $tomorrowTimes = $this->getPrayerTimes($date->copy()->addDay()->format('Y-m-d'));

        $windows = [];
        $count = count($localKeys);
        for ($i = 0; $i < $count; $i++) {
            $startStr = substr(trim($times[$apiKeys[$i]] ?? ''), 0, 5);
            if (!$startStr) continue;

            $start = Carbon::createFromFormat('H:i', $startStr, $tz)
                ->setDate($date->year, $date->month, $date->day);

            // KHUSUS Subuh: end = Sunrise (syuruq), bukan adzan Dzuhur.
            // Setelah Sunrise sampai Dzuhur tidak ada absensi sholat.
            if ($localKeys[$i] === 'Subuh') {
                $sunriseStr = substr(trim($times['Sunrise'] ?? ''), 0, 5);
                if (!$sunriseStr) continue;
                $end = Carbon::createFromFormat('H:i', $sunriseStr, $tz)
                    ->setDate($date->year, $date->month, $date->day)
                    ->subMinutes(self::NEXT_PRAYER_BUFFER_MIN);
            } elseif ($i < $count - 1) {
                // Sholat lain: end = adzan sholat berikutnya - buffer
                $nextStr = substr(trim($times[$apiKeys[$i + 1]] ?? ''), 0, 5);
                if (!$nextStr) continue;
                $end = Carbon::createFromFormat('H:i', $nextStr, $tz)
                    ->setDate($date->year, $date->month, $date->day)
                    ->subMinutes(self::NEXT_PRAYER_BUFFER_MIN);
            } else {
                // Isya: sampai sebelum Subuh besok (atau 23:59 fallback)
                $nextDayFajr = substr(trim($tomorrowTimes['Fajr'] ?? ''), 0, 5);
                if ($nextDayFajr) {
                    $end = Carbon::createFromFormat('H:i', $nextDayFajr, $tz)
                        ->setDate($date->year, $date->month, $date->day)
                        ->addDay()
                        ->subMinutes(self::NEXT_PRAYER_BUFFER_MIN);
                } else {
                    $end = Carbon::createFromTimeString('23:59:00', $tz)
                        ->setDate($date->year, $date->month, $date->day);
                }
            }

            $windows[] = [
                'name'      => $localKeys[$i],
                'start'     => $start->format('H:i:s'),
                'end'       => $end->format('H:i:s'),
                'start_at'  => $start->toDateTimeString(),
                'end_at'    => $end->toDateTimeString(),
                'duration'  => max(0, (int) $start->diffInMinutes($end)),
            ];
        }
        return $windows;
    }

    /**
     * Fetch jadwal sholat dari Aladhan API. Cache 24 jam per tanggal.
     */
    private function getPrayerTimes(string $date): array
    {
        $city = $this->prayerCity();
        return Cache::remember("prayer_times_{$city}_{$date}", 86400, function () use ($date, $city) {
            try {
                $dmy  = Carbon::parse($date)->format('d-m-Y');
                $resp = Http::timeout(8)->get("http://api.aladhan.com/v1/timingsByCity/{$dmy}", [
                    'city'    => $city,
                    'country' => $this->prayerCountry(),
                    'method'  => $this->prayerMethod(),
                ]);
                if ($resp->successful()) {
                    return $resp->json('data.timings', []);
                }
                Log::warning('Prayer API non-200', ['status' => $resp->status()]);
            } catch (\Throwable $e) {
                Log::warning('Prayer API exception', ['msg' => $e->getMessage()]);
            }
            return [];
        });
    }

    /** Fallback hardcoded kalau API down */
    private function detectPrayerWindowFallback(Carbon $now): ?array
    {
        $time = $now->format('H:i:s');
        $windows = [
            ['name' => 'Subuh',   'start' => '04:00:00', 'end' => '06:00:00'],
            ['name' => 'Dzuhur',  'start' => '11:45:00', 'end' => '13:00:00'],
            ['name' => 'Ashar',   'start' => '15:00:00', 'end' => '16:30:00'],
            ['name' => 'Maghrib', 'start' => '17:45:00', 'end' => '19:00:00'],
            ['name' => 'Isya',    'start' => '19:00:00', 'end' => '20:30:00'],
        ];
        foreach ($windows as $w) {
            if ($time >= $w['start'] && $time <= $w['end']) {
                return $w;
            }
        }
        return null;
    }

    /**
     * GET /api/iot/prayer-times
     * Debug endpoint — ESP32/admin bisa cek jadwal yang dipakai server.
     */
    public function prayerTimes(): JsonResponse
    {
        $now     = now($this->tz());
        $today   = $now->format('Y-m-d');
        $times   = $this->getPrayerTimes($today);
        $current = $this->detectPrayerWindow();

        // Build array windows (extend sampai adzan berikutnya, sesuai praktik Islami)
        $windows = $this->buildWindows($now);

        return response()->json([
            'date'           => $today,
            'timezone'       => $this->tz(),
            'gmt_offset_sec' => $now->getOffset(), // misal 25200 utk WIB
            'now'            => $now->format('H:i:s'),
            'now_epoch'      => $now->timestamp,    // untuk verifikasi NTP ESP32
            'location'       => $this->prayerCity() . ', ' . $this->prayerCountry(),
            'source'         => empty($times) ? 'fallback' : 'aladhan-api',
            'windows'        => $windows,
            'current_window' => $current,
        ]);
    }
}
