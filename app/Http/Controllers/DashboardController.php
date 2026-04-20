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
        $today = Carbon::today()->toDateString();
        $totalSantri = Student::where("status", "aktif")->count();
        $totalOpportunities = $totalSantri * 5;

        // Statistics
        $totalHadirHariIni = Attendance::where("tanggal", $today)
            ->whereIn("status", ["hadir", "terlambat"])
            ->count();

        $stats = [
            "total_santri" => $totalSantri,
            "hadir_hari_ini" => $totalHadirHariIni,
            "izin_hari_ini" => Attendance::where("tanggal", $today)
                ->where("status", "izin")
                ->count(),
            "alpha_hari_ini" =>
                $totalOpportunities -
                Attendance::where("tanggal", $today)->count(),
            "total_ortu" => ParentModel::count(),
            "pengumuman_aktif" => Announcement::where(
                "is_published",
                true,
            )->count(),
        ];

        // Jadwal Shalat API (Using MyQuran / Aladhan as fallback)
        $jadwalSholat = Cache::remember(
            "jadwal_sholat_" . $today,
            86400,
            function () use ($today) {
                try {
                    // Default to Jakarta (ID 1301 for MyQuran or search by city)
                    $response = Http::get(
                        "https://api.myquran.com/v2/sholat/jadwal/1301/" .
                            date("Y/m/d"),
                    );
                    if ($response->successful()) {
                        return $response->json()["data"]["jadwal"];
                    }
                } catch (\Exception $e) {
                    return null;
                }
                return null;
            },
        );

        // Recent activities
        $recent_activities = [];

        // Latest attendance records
        $attendances = Attendance::with("student")
            ->where("tanggal", $today)
            ->latest("created_at")
            ->limit(3)
            ->get();

        foreach ($attendances as $att) {
            $recent_activities[] = [
                "icon" => "attendance",
                "text" =>
                    $att->student->name .
                    " — " .
                    ucfirst($att->status) .
                    " (" .
                    $att->waktu_shalat .
                    ")",
                "time" => $att->created_at->diffForHumans(),
                "color" => in_array($att->status, ["hadir", "terlambat"])
                    ? "green"
                    : ($att->status === "alpha"
                        ? "red"
                        : "amber"),
            ];
        }

        // Latest permissions
        $permissions = Permission::with("student")
            ->where("status", "pending")
            ->latest("created_at")
            ->limit(2)
            ->get();

        foreach ($permissions as $perm) {
            $recent_activities[] = [
                "icon" => "permission",
                "text" =>
                    "Izin: " .
                    $perm->student->name .
                    " — " .
                    ucfirst($perm->jenis),
                "time" => $perm->created_at->diffForHumans(),
                "color" => "amber",
            ];
        }

        // Latest messages
        $messages = ChatMessage::with("parent")
            ->where("is_from_admin", false)
            ->latest("created_at")
            ->limit(1)
            ->get();

        foreach ($messages as $msg) {
            $recent_activities[] = [
                "icon" => "message",
                "text" => "Pesan dari " . $msg->parent->name,
                "time" => $msg->created_at->diffForHumans(),
                "color" => "blue",
            ];
        }

        // Latest announcements
        $announcements = Announcement::where("is_published", true)
            ->latest("published_at")
            ->limit(1)
            ->get();

        foreach ($announcements as $ann) {
            $recent_activities[] = [
                "icon" => "announcement",
                "text" => "Pengumuman: " . $ann->judul,
                "time" => $ann->published_at
                    ? $ann->published_at->diffForHumans()
                    : "Baru saja",
                "color" => "purple",
            ];
        }

        // Sort by time (most recent first) and limit to 5
        usort($recent_activities, function ($a, $b) {
            return strcmp($b["time"], $a["time"]);
        });
        $recent_activities = array_slice($recent_activities, 0, 5);

        return view(
            "dashboard",
            compact("stats", "recent_activities", "jadwalSholat"),
        );
    }
}
