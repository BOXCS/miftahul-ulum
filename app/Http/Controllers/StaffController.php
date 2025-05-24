<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Staff;
use Illuminate\Http\Request;

class StaffController extends Controller
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
        return view('management.staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'alamat' => 'required',
            'no_telp' => 'required|numeric',
            'jabatan' => 'required|string',
            'tgl_bergabung' => 'required',
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
        $akun->staff()->create([
            'nama' => $request->nama,
            'alamat' => $request->alamat,
            'no_telp' => $request->no_telp,
            'jabatan' => $request->jabatan,
            'tgl_bergabung' => $request->tgl_bergabung,
        ]);
        return redirect()->route('management.index')->with('success', 'Staf berhasil ditambahkan.');
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
        $staff = Staff::with('akun')->find($id);
        $edit = true;
        return view('management.staff.create', compact('staff', 'edit'));
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
        $staff = Staff::find($id);
        $id_akun = $staff->id_akun;
        $akun = Akun::find($id_akun);
        $akun->delete();
        $staff->delete();

        return redirect()->route('management.index')->with('success', 'Staf berhasil dihapus');
    }
}
