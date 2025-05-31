<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $id = auth()->user()->id_akun;

        $akun = Akun::findOrFail($id);
        $staff = Staff::where('id_akun', $id)->firstOrFail();

        return view('profile', compact('akun', 'staff'));
    }

    public function update(Request $request, $id)
    {
        if ($id != Auth::user()->id_akun) {
            abort(403, 'Akses ditolak.');
        }

        $akun = Akun::findOrFail($id);
        $staff = Staff::where('id_akun', $id)->firstOrFail();

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_telp' => 'required|string|max:20',
            'email' => 'required|email|unique:akun,email,' . $akun->id_akun . ',id_akun',
            'username' => 'required|string|max:255',
            'password' => 'nullable|min:6',
        ]);

        // Update akun
        $akun->email = $request->email;
        $akun->username = $request->username;
        if ($request->filled('password')) {
            $akun->password = bcrypt($request->password);
        }
        $akun->save();

        // Update staff
        $staff->nama = $request->nama;
        $staff->alamat = $request->alamat;
        $staff->no_telp = $request->no_telp;
        $staff->save();

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    // Hapus method create, store, show, edit, destroy jika tidak dipakai
}
