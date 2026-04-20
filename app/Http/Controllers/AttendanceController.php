<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $today = $request->input("tanggal", Carbon::today()->toDateString());
        $waktu_shalat = $request->input("waktu_shalat", "Subuh");

        $students = Student::where("status", "aktif")->get();

        $attendances = Attendance::where("tanggal", $today)
            ->where("waktu_shalat", $waktu_shalat)
            ->get()
            ->keyBy("student_id");

        $stats = [
            "hadir" => $attendances->where("status", "hadir")->count(),
            "terlambat" => $attendances->where("status", "terlambat")->count(),
            "izin" => $attendances->where("status", "izin")->count(),
            "sakit" => $attendances->where("status", "sakit")->count(),
            "alpha" => $students->count() - $attendances->count(),
        ];

        $prayers = ["Subuh", "Dzuhur", "Ashar", "Maghrib", "Isya"];

        return view(
            "attendance.index",
            compact(
                "students",
                "attendances",
                "today",
                "waktu_shalat",
                "stats",
                "prayers",
            ),
        );
    }

    public function report(Request $request)
    {
        $bulan = $request->input("bulan", date("m"));
        $tahun = $request->input("tahun", date("Y"));

        // Monthly statistics for chart
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $monthAttendances = Attendance::whereBetween("tanggal", [
                $start,
                $end,
            ])->get();

            $monthlyStats[] = [
                "bulan" => $date->locale("id")->isoFormat("MMMM Y"),
                "hadir" => $monthAttendances
                    ->whereIn("status", ["hadir", "terlambat"])
                    ->count(),
                "izin" => $monthAttendances->where("status", "izin")->count(),
                "sakit" => $monthAttendances->where("status", "sakit")->count(),
                "alpha" => $monthAttendances->where("status", "alpha")->count(),
            ];
        }

        // Top alpha students (Algorithm improvement: count by specific student)
        $topAlpha = Attendance::where("status", "alpha")
            ->with("student")
            ->selectRaw("student_id, COUNT(*) as alpha_count")
            ->groupBy("student_id")
            ->orderByDesc("alpha_count")
            ->limit(5)
            ->get();

        // Summary calculations
        $totalEntries = Attendance::count();
        $totalHadir = Attendance::whereIn("status", [
            "hadir",
            "terlambat",
        ])->count();

        $summary = [
            "total_hari_efektif" => Attendance::distinct("tanggal")->count(),
            "rata_kehadiran" =>
                $totalEntries > 0
                    ? round(($totalHadir / $totalEntries) * 100, 1)
                    : 0,
            "total_santri" => Student::where("status", "aktif")->count(),
        ];

        return view(
            "attendance.report",
            compact("monthlyStats", "topAlpha", "summary", "bulan", "tahun"),
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            "tanggal" => "required|date",
            "waktu_shalat" => "required|in:Subuh,Dzuhur,Ashar,Maghrib,Isya",
            "attendances" => "required|array",
        ]);

        $tanggal = Carbon::parse($request->tanggal)->toDateString();

        foreach ($request->attendances as $student_id => $data) {
            Attendance::updateOrCreate(
                [
                    "student_id" => $student_id,
                    "tanggal" => $tanggal,
                    "waktu_shalat" => $request->waktu_shalat,
                ],
                [
                    "status" => $data["status"],
                    "keterangan" => $data["keterangan"] ?? null,
                    "jam_masuk" => $data["status"] == "hadir" ? now() : null,
                ],
            );
        }

        return redirect()
            ->back()
            ->with("success", "Data presensi berhasil disimpan.");
    }
}
