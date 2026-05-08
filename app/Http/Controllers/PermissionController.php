<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Student;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $permissions = Permission::with("student")
            ->latest()
            ->get()
            ->map(
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
