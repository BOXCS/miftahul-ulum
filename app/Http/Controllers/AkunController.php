<?php

namespace App\Http\Controllers;

use App\Models\Akun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    // GET: Ambil semua akun
    public function index()
    {
        return response()->json(Akun::all(), 200);
    }

    // POST: Tambah akun baru
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:akun',
            'password' => 'required|min:6',
            'hak_akses' => 'required|in:ortu',
        ]);

        $akun = Akun::create([
            'username' => $request->username,
            'password' => Hash::make($request->password), // Simpan password yang sudah di-hash
            'hak_akses' => $request->hak_akses,
        ]);

        return response()->json($akun, 201);
    }

    // GET: Ambil akun berdasarkan ID
    public function show($id)
    {
        $akun = Akun::find($id);
        return $akun ? response()->json($akun, 200) : response()->json(['message' => 'Akun tidak ditemukan'], 404);
    }

    // PUT: Update akun
    public function update(Request $request, $id)
    {
        $akun = Akun::find($id);
        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        $akun->update($request->only(['username', 'hak_akses']));
        return response()->json($akun, 200);
    }

    // DELETE: Hapus akun
    public function destroy($id)
    {
        $akun = Akun::find($id);
        if (!$akun) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        $akun->delete();
        return response()->json(['message' => 'Akun berhasil dihapus'], 200);
    }
}
