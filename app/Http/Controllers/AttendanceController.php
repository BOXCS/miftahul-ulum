<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Barryvdh\DomPDF\Facade\Pdf;

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
                'jam_keluar' => $att->jam_keluar,
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

    /* ═══════════════════════════════════════════════════════
     * EXPORT EXCEL
     * ═══════════════════════════════════════════════════════ */
    public function exportExcel()
    {
        $monthlyStats = $this->buildMonthlyStats();
        $topAlpha     = $this->buildTopAlpha();
        $summary      = $this->buildSummary();

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Presensi');

        // ── Warna tema ─────────────────────────────────────
        $teal   = '0D9488';
        $white  = 'FFFFFF';
        $gray   = 'F8FAFC';
        $border = 'E2E8F0';

        // ── Judul ──────────────────────────────────────────
        $sheet->mergeCells('A1:F1');
        $sheet->setCellValue('A1', 'LAPORAN PRESENSI SANTRI');
        $sheet->getStyle('A1')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 16, 'color' => ['rgb' => $white]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $teal]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(36);

        $sheet->mergeCells('A2:F2');
        $sheet->setCellValue('A2', 'Pondok Pesantren Miftahul Ulum · Dicetak: ' . now()->locale('id')->isoFormat('D MMMM Y'));
        $sheet->getStyle('A2')->applyFromArray([
            'font'      => ['size' => 10, 'color' => ['rgb' => '64748B']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'F0FDFA']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // ── Summary ────────────────────────────────────────
        $sheet->setCellValue('A4', 'RINGKASAN');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $teal]],
        ]);

        $summaryData = [
            ['Total Santri Aktif',    $summary['total_santri']],
            ['Hari Efektif Tercatat', $summary['total_hari_efektif']],
            ['Rata-rata Kehadiran',   $summary['rata_kehadiran'] . '%'],
        ];
        foreach ($summaryData as $i => $row) {
            $r = 5 + $i;
            $sheet->setCellValue('A' . $r, $row[0]);
            $sheet->setCellValue('B' . $r, $row[1]);
            $sheet->getStyle('A' . $r)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => '475569']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $gray]],
            ]);
            $sheet->getStyle('B' . $r)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => $teal]],
            ]);
        }

        // ── Tren Bulanan ───────────────────────────────────
        $startRow = 10;
        $sheet->setCellValue('A' . $startRow, 'TREN KEHADIRAN 6 BULAN TERAKHIR');
        $sheet->getStyle('A' . $startRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => $teal]],
        ]);

        $headers = ['Bulan', 'Hadir', 'Izin', 'Sakit', 'Alpha', 'Total'];
        $headerRow = $startRow + 1;
        foreach ($headers as $col => $h) {
            $cell = chr(65 + $col) . $headerRow;
            $sheet->setCellValue($cell, $h);
        }
        $sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => $white]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $teal]],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        foreach ($monthlyStats as $idx => $row) {
            $r     = $headerRow + 1 + $idx;
            $total = $row['hadir'] + $row['izin'] + $row['sakit'] + $row['alpha'];
            $sheet->setCellValue('A' . $r, $row['bulan']);
            $sheet->setCellValue('B' . $r, $row['hadir']);
            $sheet->setCellValue('C' . $r, $row['izin']);
            $sheet->setCellValue('D' . $r, $row['sakit']);
            $sheet->setCellValue('E' . $r, $row['alpha']);
            $sheet->setCellValue('F' . $r, $total);
            $bg = $idx % 2 === 0 ? $gray : $white;
            $sheet->getStyle('A' . $r . ':F' . $r)->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $bg]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getStyle('A' . $r)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                'font'      => ['bold' => true],
            ]);
        }

        // Border tabel bulanan
        $lastMonthRow = $headerRow + count($monthlyStats);
        $sheet->getStyle('A' . $headerRow . ':F' . $lastMonthRow)->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => $border]],
            ],
        ]);

        // ── Top Alpha ──────────────────────────────────────
        $alphaStart = $lastMonthRow + 2;
        $sheet->setCellValue('A' . $alphaStart, 'SANTRI ALPHA TERBANYAK');
        $sheet->getStyle('A' . $alphaStart)->applyFromArray([
            'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => 'DC2626']],
        ]);

        $alphaHeaders = ['#', 'Nama Santri', 'Kelas', 'Jumlah Alpha'];
        $alphaHeaderRow = $alphaStart + 1;
        foreach ($alphaHeaders as $col => $h) {
            $sheet->setCellValue(chr(65 + $col) . $alphaHeaderRow, $h);
        }
        $sheet->getStyle('A' . $alphaHeaderRow . ':D' . $alphaHeaderRow)->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => $white]],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => 'DC2626']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        foreach ($topAlpha as $idx => $item) {
            $r = $alphaHeaderRow + 1 + $idx;
            $sheet->setCellValue('A' . $r, $idx + 1);
            $sheet->setCellValue('B' . $r, $item->student?->name ?? '-');
            $sheet->setCellValue('C' . $r, $item->student?->class ?? '-');
            $sheet->setCellValue('D' . $r, $item->alpha_count . 'x');
            $bg = $idx % 2 === 0 ? $gray : $white;
            $sheet->getStyle('A' . $r . ':D' . $r)->applyFromArray([
                'fill'      => ['fillType' => Fill::FILL_SOLID, 'color' => ['rgb' => $bg]],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ]);
            $sheet->getStyle('B' . $r)->applyFromArray([
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
                'font'      => ['bold' => true],
            ]);
        }

        // ── Column widths ──────────────────────────────────
        $sheet->getColumnDimension('A')->setWidth(28);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(14);
        $sheet->getColumnDimension('D')->setWidth(12);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(12);

        // ── Download ───────────────────────────────────────
        $filename = 'laporan-presensi-' . now()->format('Y-m-d') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control'       => 'max-age=0',
        ]);
    }

    /* ═══════════════════════════════════════════════════════
     * EXPORT PDF
     * ═══════════════════════════════════════════════════════ */
    public function exportPdf()
    {
        $monthlyStats = $this->buildMonthlyStats();
        $topAlpha     = $this->buildTopAlpha();
        $summary      = $this->buildSummary();

        $pdf = Pdf::loadView('attendance.report-pdf', compact('monthlyStats', 'topAlpha', 'summary'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download('laporan-presensi-' . now()->format('Y-m-d') . '.pdf');
    }

    /* ═══════════════════════════════════════════════════════
     * HELPERS (shared antara report, excel, pdf)
     * ═══════════════════════════════════════════════════════ */
    private function buildMonthlyStats(): array
    {
        $stats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth();
            $end   = $date->copy()->endOfMonth();

            $rows = Attendance::whereBetween('tanggal', [$start, $end])->get();
            $stats[] = [
                'bulan' => $date->locale('id')->isoFormat('MMMM Y'),
                'hadir' => $rows->whereIn('status', ['hadir', 'terlambat'])->count(),
                'izin'  => $rows->where('status', 'izin')->count(),
                'sakit' => $rows->where('status', 'sakit')->count(),
                'alpha' => $rows->where('status', 'alpha')->count(),
            ];
        }
        return $stats;
    }

    private function buildTopAlpha()
    {
        return Attendance::where('status', 'alpha')
            ->with('student')
            ->selectRaw('student_id, COUNT(*) as alpha_count')
            ->groupBy('student_id')
            ->orderByDesc('alpha_count')
            ->limit(5)
            ->get();
    }

    private function buildSummary(): array
    {
        $total  = Attendance::count();
        $hadir  = Attendance::whereIn('status', ['hadir', 'terlambat'])->count();
        return [
            'total_hari_efektif' => Attendance::distinct('tanggal')->count(),
            'rata_kehadiran'     => $total > 0 ? round(($hadir / $total) * 100, 1) : 0,
            'total_santri'       => Student::where('status', 'aktif')->count(),
        ];
    }
}
