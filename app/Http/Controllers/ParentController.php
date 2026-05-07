<?php

namespace App\Http\Controllers;

use App\Models\ParentModel;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ParentController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $parents = ParentModel::with("students")->get();

        $stats = [
            "total" => ParentModel::count(),
            "terhubung" => Student::whereNotNull("parent_id")
                ->distinct("parent_id")
                ->count(),
        ];

        return view("parents.index", compact("parents", "stats"));
    }

    public function create(): \Illuminate\View\View
    {
        $hubunganOptions = ["ayah", "ibu", "wali"];
        return view("parents.create", compact("hubunganOptions"));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "relationship" => "required|in:ayah,ibu,wali",
            "phone" => "required|string|max:20",
            "email" => "nullable|email|max:255",
            "address" => "required|string",
            "password" => "required|string|min:8",
        ]);

        $validated["password"] = Hash::make($validated["password"]);
        $validated["role"] = "ortu";

        ParentModel::create($validated);

        return redirect()
            ->route("parents.index")
            ->with("success", "Data wali santri berhasil ditambahkan.");
    }

    public function edit(int $id): \Illuminate\View\View
    {
        $parent = ParentModel::findOrFail($id);
        $hubunganOptions = ["ayah", "ibu", "wali"];

        return view("parents.edit", compact("parent", "hubunganOptions"));
    }

    public function update(
        Request $request,
        int $id,
    ): \Illuminate\Http\RedirectResponse {
        $parent = ParentModel::findOrFail($id);

        $validated = $request->validate([
            "name" => "required|string|max:255",
            "relationship" => "required|in:ayah,ibu,wali",
            "phone" => "required|string|max:20",
            "email" => "nullable|email|max:255",
            "address" => "required|string",
            "password" => "nullable|string|min:8",
        ]);

        if (!empty($validated["password"])) {
            $validated["password"] = Hash::make($validated["password"]);
        } else {
            unset($validated["password"]);
        }

        $validated["role"] = "ortu";

        $parent->update($validated);

        return redirect()
            ->route("parents.index")
            ->with("success", "Data wali santri berhasil diperbarui.");
    }

    public function destroy(int $id): \Illuminate\Http\RedirectResponse
    {
        $parent = ParentModel::findOrFail($id);
        $parent->delete();

        return redirect()
            ->route("parents.index")
            ->with("success", "Data wali santri berhasil dihapus.");
    }
}
