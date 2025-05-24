<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('management.santri.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tahun = date('Y');
        $request->validate([
            'id_santri' => 'required|string',
            'nama' => 'required|string',
            'tahun_angkatan' => "required|numeric|max:$tahun",
            'id_ortu' => 'required|string',
            'status' => 'required|string',
        ]);
        Santri::create($request->all());
        return redirect()->route('management.index')->with('success', 'Santri berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $santri = Santri::with('ortu')->find($id);
        $edit = true;
        return view('management.santri.create', compact('santri', 'edit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $santri = Santri::find($id);
        $santri->delete();

        return redirect()->route('management.index')->with('success', 'Santri berhasil dihapus');
    }
}
