<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ParentModel;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    private function getKelasOptions(): array
    {
        return ["7A", "7B", "8A", "8B", "9A", "9B"];
    }

    public function index(Request $request): \Illuminate\View\View
    {
        $query = Student::with("parent");

        if ($request->filled("search")) {
            $search = $request->input("search");
            $query->where(function ($q) use ($search) {
                $q->where("name", "like", "%$search%")->orWhere(
                    "nis",
                    "like",
                    "%$search%",
                );
            });
        }

        if ($request->filled("class")) {
            $query->where("class", $request->input("class"));
        }

        if ($request->filled("status")) {
            $query->where("status", $request->input("status"));
        }

        $students = $query->get();

        $stats = [
            "total" => Student::count(),
            "laki_laki" => Student::where("gender", "Laki-laki")->count(),
            "perempuan" => Student::where("gender", "Perempuan")->count(),
            "aktif" => Student::where("status", "aktif")->count(),
        ];

        return view("students.index", [
            "students" => $students,
            "kelas_options" => $this->getKelasOptions(),
            "stats" => $stats,
        ]);
    }

    public function create(): \Illuminate\View\View
    {
        return view("students.create", [
            "kelas_options" => $this->getKelasOptions(),
            "status_options" => ["aktif", "alumni", "keluar"],
            "parents" => ParentModel::all(),
        ]);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            "name" => "required|string|max:100",
            "parent_id" => "required|exists:parents,id",
            "nis" => "required|string|unique:students,nis",
            "gender" => "required|in:Laki-laki,Perempuan",
            "tanggal_lahir" => "required|date",
            "email" => "nullable|email|unique:students,email",
            "phone" => "nullable|string|max:20",
            "class" => "required|in:7A,7B,8A,8B,9A,9B",
            "tahun_angkatan" => "required|string|max:4",
            "address" => "required|string",
            "status" => "required|in:aktif,alumni,keluar",
        ]);

        Student::create($validated);

        return redirect()
            ->route("students.index")
            ->with("success", "Data santri berhasil ditambahkan.");
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $student = Student::findOrFail($id);

        return view("students.edit", [
            "student" => $student,
            "kelas_options" => $this->getKelasOptions(),
            "status_options" => ["aktif", "alumni", "keluar"],
            "parents" => ParentModel::all(),
        ]);
    }

    public function update(
        Request $request,
        int $id,
    ): \Illuminate\Http\RedirectResponse {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            "name" => "required|string|max:100",
            "parent_id" => "required|exists:parents,id",
            "nis" => "required|string|unique:students,nis," . $id,
            "gender" => "required|in:Laki-laki,Perempuan",
            "tanggal_lahir" => "required|date",
            "email" => "nullable|email|unique:students,email," . $id,
            "phone" => "nullable|string|max:20",
            "class" => "required|in:7A,7B,8A,8B,9A,9B",
            "tahun_angkatan" => "required|string|max:4",
            "address" => "required|string",
            "status" => "required|in:aktif,alumni,keluar",
        ]);

        $student->update($validated);

        return redirect()
            ->route("students.index")
            ->with("success", "Data santri berhasil diperbarui.");
    }

    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()
            ->route("students.index")
            ->with("success", "Data santri berhasil dihapus.");
    }

    public function updateFingerprint(
        Request $request,
        int $id,
    ): \Illuminate\Http\JsonResponse {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            "fingerprint_template" => "required|string",
            "fingerprint_quality" => "required|integer|min:0|max:100",
        ]);

        $student->update([
            "fingerprint_template" => encrypt(
                $validated["fingerprint_template"],
            ),
            "fingerprint_quality" => $validated["fingerprint_quality"],
            "scanned_at" => now(),
        ]);

        return response()->json([
            "success" => true,
            "message" => "Data sidik jari berhasil disimpan.",
        ]);
    }
}
