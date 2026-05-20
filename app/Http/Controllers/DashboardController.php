<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ParentModel;
use App\Models\Announcement;
use App\Models\Permission;
use App\Models\Attendance;
use App\Models\ChatMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $tz    = env('IOT_TIMEZONE', 'Asia/Jakarta');
        $today = Carbon::today($tz);

        $totalSantri        = Student::where("status", "aktif")->count();
        $totalOpportunities = $totalSantri * 5; // 5 waktu sholat

        $stats = [
            "total_santri"     => $totalSantri,
            "hadir_hari_ini"   => Attendance::whereDate("tanggal", $today)
                ->whereIn("status", ["hadir", "terlambat"])
                ->count(),
            "izin_hari_ini"    => Attendance::whereDate("tanggal", $today)
                ->whereIn("status", ["izin", "sakit"])
                ->count(),
            "alpha_hari_ini"   => max(0, $totalOpportunities - Attendance::whereDate("tanggal", $today)->count()),
            "total_ortu"       => ParentModel::count(),
            "pengumuman_aktif" => Announcement::where("is_published", true)->count(),
        ];

        // ─── Jadwal Sholat untuk lokasi pesantren (Aladhan API, Jember default) ───
        $jadwalSholat = $this->fetchJadwalSholat($today);

        // ─── Data chart dari database (bukan dummy) ───
        $chartData = $this->buildChartData($tz);

        // ─── Recent activities ───
        $recent_activities = $this->buildRecentActivities($today);

        return view("dashboard", compact("stats", "recent_activities", "jadwalSholat", "chartData"));
    }

    /**
     * Fetch jadwal sholat dari Aladhan API (sama API yang dipakai IoT).
     * Kota & negara dari .env (IOT_CITY, IOT_COUNTRY).
     * Cache 24 jam per tanggal.
     */
    private function fetchJadwalSholat(Carbon $date): ?array
    {
        $city    = env('IOT_CITY', 'Jember');
        $country = env('IOT_COUNTRY', 'Indonesia');
        $method  = (int) env('IOT_PRAYER_METHOD', 20);

        $cacheKey = "dashboard_jadwal_{$city}_" . $date->toDateString();
        return Cache::remember($cacheKey, 86400, function () use ($date, $city, $country, $method) {
            try {
                $dmy  = $date->format('d-m-Y');
                $resp = Http::withoutVerifying()
                    ->timeout(8)
                    ->get("http://api.aladhan.com/v1/timingsByCity/{$dmy}", [
                        'city'    => $city,
                        'country' => $country,
                        'method'  => $method,
                    ]);

                if (!$resp->successful()) return null;

                $t = $resp->json('data.timings', []);
                $g = $resp->json('data.date.gregorian', []);
                $h = $resp->json('data.date.hijri', []);

                return [
                    'tanggal' => $g['date'] ?? $date->format('d-m-Y'),
                    'hijri'   => trim(($h['day'] ?? '') . ' ' . ($h['month']['en'] ?? '') . ' ' . ($h['year'] ?? '')),
                    'subuh'   => substr(trim($t['Fajr']    ?? '00:00'), 0, 5),
                    'terbit'  => substr(trim($t['Sunrise'] ?? '00:00'), 0, 5),
                    'dzuhur'  => substr(trim($t['Dhuhr']   ?? '00:00'), 0, 5),
                    'ashar'   => substr(trim($t['Asr']     ?? '00:00'), 0, 5),
                    'maghrib' => substr(trim($t['Maghrib'] ?? '00:00'), 0, 5),
                    'isya'    => substr(trim($t['Isha']    ?? '00:00'), 0, 5),
                    'city'    => $city,
                    'country' => $country,
                ];
            } catch (\Throwable $e) {
                return null;
            }
        });
    }

    /**
     * Build chart data nyata dari tabel attendance.
     *   7hari   → 6 hari kebelakang + hari ini
     *   bulanan → 12 bulan tahun berjalan
     *   tahunan → 5 tahun terakhir
     */
    private function buildChartData(string $tz): array
    {
        $totalSantri  = Student::where('status', 'aktif')->count();
        $prayersCount = 5;
        $year         = Carbon::now($tz)->year;

        $dayNames   = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
        $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

        // ── 7 hari terakhir ──
        $sevenDays = ['labels' => [], 'hadir' => [], 'izin' => [], 'alpha' => []];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::today($tz)->subDays($i);
            $sevenDays['labels'][] = $i === 0 ? 'Hari Ini' : $dayNames[$d->dayOfWeekIso - 1];

            $hadir = Attendance::whereDate('tanggal', $d)->whereIn('status', ['hadir', 'terlambat'])->count();
            $izin  = Attendance::whereDate('tanggal', $d)->whereIn('status', ['izin', 'sakit'])->count();
            $total = Attendance::whereDate('tanggal', $d)->count();
            $expected = $totalSantri * $prayersCount;
            $alpha = max(0, $expected - $total);

            $sevenDays['hadir'][] = $hadir;
            $sevenDays['izin'][]  = $izin;
            $sevenDays['alpha'][] = $alpha;
        }

        // ── Bulanan (12 bulan tahun berjalan) ──
        $bulanan = ['labels' => [], 'hadir' => [], 'izin' => [], 'alpha' => []];
        for ($m = 1; $m <= 12; $m++) {
            $bulanan['labels'][] = $monthNames[$m - 1];
            $hadir = Attendance::whereYear('tanggal', $year)->whereMonth('tanggal', $m)
                ->whereIn('status', ['hadir', 'terlambat'])->count();
            $izin  = Attendance::whereYear('tanggal', $year)->whereMonth('tanggal', $m)
                ->whereIn('status', ['izin', 'sakit'])->count();
            $total = Attendance::whereYear('tanggal', $year)->whereMonth('tanggal', $m)->count();
            $daysInMonth = Carbon::create($year, $m, 1)->daysInMonth;
            $expected = $totalSantri * $prayersCount * $daysInMonth;
            $alpha = max(0, $expected - $total);

            $bulanan['hadir'][] = $hadir;
            $bulanan['izin'][]  = $izin;
            $bulanan['alpha'][] = $alpha;
        }

        // ── Tahunan (5 tahun terakhir, % dari data yang tercatat) ──
        $tahunan = ['labels' => [], 'hadir' => [], 'izin' => [], 'alpha' => []];
        for ($i = 4; $i >= 0; $i--) {
            $y = $year - $i;
            $tahunan['labels'][] = (string) $y;

            $hadir = Attendance::whereYear('tanggal', $y)->whereIn('status', ['hadir', 'terlambat'])->count();
            $izin  = Attendance::whereYear('tanggal', $y)->whereIn('status', ['izin', 'sakit'])->count();
            $alpha = Attendance::whereYear('tanggal', $y)->where('status', 'alpha')->count();
            $total = $hadir + $izin + $alpha;

            // Tampilkan sebagai persentase dari data yang benar-benar tercatat
            $tahunan['hadir'][] = $total > 0 ? round(($hadir / $total) * 100, 1) : 0;
            $tahunan['izin'][]  = $total > 0 ? round(($izin  / $total) * 100, 1) : 0;
            $tahunan['alpha'][] = $total > 0 ? round(($alpha / $total) * 100, 1) : 0;
        }

        return [
            '7hari'   => $sevenDays,
            'bulanan' => $bulanan,
            'tahunan' => array_merge($tahunan, ['isPercent' => true]),
        ];
    }

    /**
     * Aktivitas terbaru: 5 paling baru dari attendance, permission, chat, announcement.
     */
    private function buildRecentActivities(Carbon $today): array
    {
        $activities = [];

        Attendance::with("student")
            ->whereDate("tanggal", $today)
            ->latest("created_at")
            ->limit(3)
            ->get()
            ->each(function ($att) use (&$activities) {
                $activities[] = [
                    "icon"  => "attendance",
                    "text"  => ($att->student?->name ?? 'N/A') . " — " . ucfirst($att->status) . " (" . $att->waktu_shalat . ")",
                    "time"  => $att->created_at->diffForHumans(),
                    "color" => in_array($att->status, ["hadir", "terlambat"]) ? "green" : ($att->status === "alpha" ? "red" : "amber"),
                    "ts"    => $att->created_at->timestamp,
                ];
            });

        Permission::with("student")
            ->where("status", "pending")
            ->latest("created_at")
            ->limit(2)
            ->get()
            ->each(function ($perm) use (&$activities) {
                $activities[] = [
                    "icon"  => "permission",
                    "text"  => "Izin: " . ($perm->student?->name ?? 'N/A') . " — " . ucfirst($perm->jenis),
                    "time"  => $perm->created_at->diffForHumans(),
                    "color" => "amber",
                    "ts"    => $perm->created_at->timestamp,
                ];
            });

        ChatMessage::with("parent")
            ->where("is_from_admin", false)
            ->latest("created_at")
            ->limit(1)
            ->get()
            ->each(function ($msg) use (&$activities) {
                $activities[] = [
                    "icon"  => "message",
                    "text"  => "Pesan dari " . ($msg->parent?->name ?? 'wali'),
                    "time"  => $msg->created_at->diffForHumans(),
                    "color" => "blue",
                    "ts"    => $msg->created_at->timestamp,
                ];
            });

        Announcement::where("is_published", true)
            ->latest("published_at")
            ->limit(1)
            ->get()
            ->each(function ($ann) use (&$activities) {
                $activities[] = [
                    "icon"  => "announcement",
                    "text"  => "Pengumuman: " . $ann->judul,
                    "time"  => $ann->published_at ? $ann->published_at->diffForHumans() : "Baru saja",
                    "color" => "purple",
                    "ts"    => $ann->published_at ? $ann->published_at->timestamp : 0,
                ];
            });

        // Sort by timestamp (latest first), ambil 5
        usort($activities, fn($a, $b) => $b['ts'] - $a['ts']);
        return array_slice($activities, 0, 5);
    }
}
