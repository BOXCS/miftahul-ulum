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
        $today        = Carbon::parse($request->input('tanggal', Carbon::today()))->toDateString();
        $waktu_shalat = $request->input('waktu_shalat', 'Subuh');

        $students = Student::where('status', 'aktif')->get();

        // Semua absensi hari ini (semua waktu shalat)
        $rawAttendances = Attendance::whereDate('tanggal', $today)->get();

        // Untuk stat cards — tetap filter per waktu yang aktif
        $attendances = $rawAttendances->where('waktu_shalat', $waktu_shalat)->keyBy('student_id');

        // Untuk tabel 5 kolom — format: ['studentId_Subuh' => att, ...]
        $allAttendances = [];
        foreach ($rawAttendances as $att) {
            $key = $att->student_id . '_' . $att->waktu_shalat;
            $allAttendances[$key] = [
                'status'     => $att->status,
                'keterangan' => $att->keterangan,
                'jam_masuk'  => $att->jam_masuk,
            ];
        }

        $stats = [
            'hadir'    => $attendances->where('status', 'hadir')->count(),
            'terlambat' => $attendances->where('status', 'terlambat')->count(),
            'izin'     => $attendances->where('status', 'izin')->count(),
            'sakit'    => $attendances->where('status', 'sakit')->count(),
            'alpha'    => $attendances->where('status', 'alpha')->count()
                + ($students->count() - $attendances->count()),
        ];

        $prayers = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];

        return view('attendance.index', compact(
            'students',
            'attendances',
            'allAttendances',
            'today',
            'waktu_shalat',
            'stats',
            'prayers'
        ));
    }
    public function create(Request $request)
    {
        $today        = Carbon::parse($request->input('tanggal', Carbon::today()))->toDateString();
        $waktu_shalat = $request->input('waktu_shalat', 'Subuh');
        $students     = Student::where('status', 'aktif')->orderBy('name')->get();
        $prayers      = ['Subuh', 'Dzuhur', 'Ashar', 'Maghrib', 'Isya'];

        return view('attendance.create', compact('students', 'today', 'waktu_shalat', 'prayers'));
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

        $tanggalView = Carbon::parse($request->tanggal)->toDateString();
        $tanggalDb = Carbon::parse($request->tanggal)->startOfDay();

        foreach ($request->attendances as $student_id => $data) {
            $status = $data["status"] ?? "alpha";

            $attendance = Attendance::where("student_id", $student_id)
                ->whereDate("tanggal", $tanggalView)
                ->where("waktu_shalat", $request->waktu_shalat)
                ->first();

            if (!$attendance) {
                $attendance = new Attendance();
                $attendance->student_id = $student_id;
                $attendance->tanggal = $tanggalDb;
                $attendance->waktu_shalat = $request->waktu_shalat;
            }

            $attendance->status = $status;
            $attendance->keterangan = $data["keterangan"] ?? null;

            if (in_array($status, ["hadir", "terlambat"])) {
                $attendance->jam_masuk = $attendance->jam_masuk ?? now();
            } else {
                $attendance->jam_masuk = null;
            }

            $attendance->save();
        }

        return redirect()
            ->route("attendance.index", [
                "tanggal" => $tanggalView,
                "waktu_shalat" => $request->waktu_shalat,
            ])
            ->with("success", "Data presensi berhasil disimpan.");
    }

    public function verifyFingerprint(Request $request)
    {
        $request->validate([
            "fingerprint_template" => "required|string",
        ]);

        $inputTemplate = $request->input("fingerprint_template");

        // Dalam implementasi nyata, matching 1:N sebaiknya dilakukan
        // menggunakan SDK dari perangkat fingerprint atau algoritma biometrik.
        // Simulasi ini membandingkan hasil dekripsi template.
        $students = Student::whereNotNull("fingerprint_template")
            ->where("status", "aktif")
            ->get();

        foreach ($students as $student) {
            try {
                $savedTemplate = decrypt($student->fingerprint_template);
                // Matching sederhana untuk keperluan simulasi
                if ($savedTemplate === $inputTemplate) {
                    return response()->json([
                        "success" => true,
                        "student" => [
                            "id" => $student->id,
                            "name" => $student->name,
                            "nis" => $student->nis,
                            "class" => $student->class,
                        ],
                        "message" => "Verifikasi berhasil.",
                    ]);
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        return response()->json(
            [
                "success" => false,
                "message" => "Sidik jari tidak dikenali.",
            ],
            404,
        );
    }
}
