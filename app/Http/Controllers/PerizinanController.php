<?php

namespace App\Http\Controllers;

use App\Models\Perizinan;
use App\Models\Santri;
use Illuminate\Http\Request;

class PerizinanController extends Controller
{
    public function index()
    {
        $perizinan = Perizinan::with('santri')->get();
        return view('perizinan', compact('perizinan'));
    }
    public function create()
    {
        $santri = Santri::get(['id_santri', 'nama']);
        return view('perizinan.create', compact( 'santri'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'waktu' => "required|date",
            'jenis_izin' => 'required|string',
            'keterangan' => 'required|string',
            'id_santri' => 'required|string',
        ]);
        Perizinan::create($request->all());
        return redirect()->route(route: 'perizinan.index')->with('success', 'Izin santri berhasil ditambahkan.');
    }
    public function edit(string $id)
    {
        $perizinan = Perizinan::find($id);
        $santri = Santri::get(['id_santri', 'nama']);
        return view('perizinan.create', compact('perizinan', 'santri'));
    }
    public function destroy(string $id)
    {
        $perizinan = Perizinan::find($id);
        $perizinan->delete();
        return redirect()->route('perizinan.index')->with('success', 'Izin santri berhasil dihapus');
    }
    public function update(Request $request, string $id)
    {
        $request->validate([
            'waktu' => "required|date",
            'jenis_izin' => 'required|string',
            'keterangan' => 'required|string',
            'id_santri' => 'required|string',
        ]);
        $perizinan = Perizinan::find($id);
        $perizinan->update($request->all());
        return redirect()->route('perizinan.index')->with('success', 'Izin santri berhasil diupdate');
    }
}
