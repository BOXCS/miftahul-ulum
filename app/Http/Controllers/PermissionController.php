<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Student;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $permissionsQuery = Permission::with("student")->latest()->get();

        if ($request->export === 'excel') {
            return $this->exportExcel($permissionsQuery);
        }

        if ($request->export === 'pdf') {
            return $this->exportPdf($permissionsQuery);
        }

        $permissions = $permissionsQuery->map(
                fn($p) => [
                    "id" => $p->id,
                    "santri" => $p->student?->name ?? "N/A",
                    "avatar" => strtoupper(
                        substr($p->student?->name ?? "N", 0, 2),
                    ),
                    "kelas" => $p->student?->class ?? "-",
                    "jenis" => ucfirst($p->jenis),
                    "tanggal" =>
                    $p->tanggal_mulai->format("d/m/Y") .
                        " - " .
                        $p->tanggal_selesai->format("d/m/Y"),
                    "keterangan" => $p->keterangan,
                    "status" => ucfirst($p->status),
                    "diajukan" => "Wali", // Default for now
                    "tglAjuan" => $p->created_at->diffForHumans(),
                    "approved_by" => $p->approved_by,
                    "catatan" => $p->catatan, // Alasan penolakan / catatan admin
                ],
            )
            ->toArray();

        return view("permissions.index", compact("permissions"));
    }

    private function exportExcel($permissions)
    {
        $filename = "Export_Perizinan_" . date('Y-m-d_H-i-s') . ".csv";
        $handle = fopen('php://output', 'w');
        
        ob_start();
        fputcsv($handle, ['ID', 'Nama Santri', 'Kelas', 'Jenis Izin', 'Tanggal Mulai', 'Tanggal Selesai', 'Status', 'Alasan', 'Catatan Admin']);
        
        foreach ($permissions as $p) {
            fputcsv($handle, [
                $p->id,
                $p->student?->name ?? "N/A",
                $p->student?->class ?? "-",
                ucfirst($p->jenis),
                $p->tanggal_mulai->format('d/m/Y'),
                $p->tanggal_selesai->format('d/m/Y'),
                ucfirst($p->status),
                $p->keterangan,
                $p->catatan
            ]);
        }
        fclose($handle);
        
        $csv = ob_get_clean();
        
        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    private function exportPdf($permissions)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('permissions.pdf', compact('permissions'));
        return $pdf->download("Export_Perizinan_" . date('Y-m-d_H-i-s') . ".pdf");
    }

    public function create(): \Illuminate\View\View
    {
        $students = Student::where("status", "aktif")->get();
        return view("permissions.create", compact("students"));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            "student_id" => "required|exists:students,id",
            "jenis" => "required|in:keluar,pulang,kegiatan,sakit",
            "tanggal_mulai" => "required|date",
            "tanggal_selesai" => "required|date|after_or_equal:tanggal_mulai",
            "keterangan" => "nullable|string",
        ]);

        Permission::create($validated + ["status" => "pending"]);

        return redirect()
            ->route("permissions.index")
            ->with("success", "Permintaan izin berhasil dibuat.");
    }

    public function approve(
        Request $request,
        int $id,
    ): \Illuminate\Http\RedirectResponse {
        $permission = Permission::findOrFail($id);
        $permission->update([
            "status" => "disetujui",
            "approved_by" => $request->input("approved_by", "Admin"),
            "approved_at" => now(),
            "catatan" => $request->input("catatan"), // opsional
        ]);

        // Realtime ke wali santri di mobile
        broadcast(new \App\Events\PermissionStatusUpdated($permission->fresh()));

        return redirect()
            ->route("permissions.index")
            ->with("success", "Izin disetujui.");
    }

    public function reject(
        Request $request,
        int $id,
    ): \Illuminate\Http\RedirectResponse {
        $validated = $request->validate([
            "catatan" => "required|string|max:500", // alasan penolakan wajib
            "approved_by" => "nullable|string|max:255",
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            "status" => "ditolak",
            "approved_by" => $validated["approved_by"] ?? "Admin",
            "approved_at" => now(),
            "catatan" => $validated["catatan"],
        ]);

        // Realtime ke wali santri di mobile
        broadcast(new \App\Events\PermissionStatusUpdated($permission->fresh()));

        return redirect()
            ->route("permissions.index")
            ->with("success", "Izin ditolak.");
    }
    public function edit(int $id): \Illuminate\View\View
    {
        $permission = Permission::with('student')->findOrFail($id);
        $students = Student::where('status', 'aktif')->get();
        return view('permissions.edit', compact('permission', 'students'));
    }

    public function update(Request $request, int $id): \Illuminate\Http\RedirectResponse
    {
        $permission = Permission::findOrFail($id);
        $validated = $request->validate([
            'student_id'      => 'required|exists:students,id',
            'jenis'           => 'required|in:keluar,pulang,kegiatan,sakit',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'nullable|string',
        ]);
        $permission->update($validated);
        return redirect()->route('permissions.index')->with('success', 'Permintaan izin berhasil diperbarui.');
    }
}
