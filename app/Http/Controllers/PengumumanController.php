<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengumumanController extends Controller
{
    public function index()
    {
        // Ambil semua data pengumuman
        $pengumumans = Pengumuman::all();

        // Kirim variabel pengumumans ke tampilan
        return view('pengumuman', compact('pengumumans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'isi' => 'required',
            'kategori' => 'required|in:akademik,administrasi,kegiatan',
            'tgl_mulai' => 'required|date',
            'tgl_selesai' => 'required|date',
            'foto' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);


        $pathFoto = null;
        if ($request->hasFile('foto')) {
            $pathFoto = $request->file('foto')->store('gambar_pengumuman', 'public');
        }

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'foto' => $pathFoto,
            'id_akun' => Auth::id(), // pastikan user login
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil disimpan!');
    }
}

