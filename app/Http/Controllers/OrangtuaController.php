<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\OrangTua;
use Illuminate\Http\Request;

class OrangtuaController extends Controller
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
    public function create(Request $request)
    {
        return view('management.orangtua.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string',
            'alamat' => 'required',
            'no_telp' => 'required|numeric',
            'email' => 'required|email',
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $akun = Akun::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'hak_akses' => $request->hak_akses,
        ]);
        $akun->ortu()->create([
            'nama_lengkap' => $request->nama_lengkap,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
        ]);
        return redirect()->route('management.index')->with('success', 'Orang tua santri berhasil ditambahkan.');
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
        $orangtua = OrangTua::with('akun')->find($id);
        $edit = true;
        return view('management.orangtua.create', compact('orangtua', 'edit'));
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
        $ortu = OrangTua::find($id);
        $id_akun = $ortu->id_akun;
        $akun = Akun::find($id_akun);
        $akun->delete();
        $ortu->delete();

        return redirect()->route('management.index')->with('success', 'Orang tua berhasil dihapus');
    }
}
