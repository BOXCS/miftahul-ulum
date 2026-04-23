<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        $filename = null;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');

            // Buat nama unik
            $filename = Str::random(40) . '.' . $file->getClientOriginalExtension();

            // Simpan ke storage/app/public/gambar_pengumuman (optional)
            $file->storeAs('gambar_pengumuman', $filename, 'public');

            // Copy ke public/gambar_pengumuman agar bisa diakses Flutter
            $file->move(public_path('gambar_pengumuman'), $filename);
        }

        Pengumuman::create([
            'judul' => $request->judul,
            'isi' => $request->isi,
            'kategori' => $request->kategori,
            'tgl_mulai' => $request->tgl_mulai,
            'tgl_selesai' => $request->tgl_selesai,
            'foto' => $filename,
            'id_akun' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Pengumuman berhasil disimpan!');
    }
}
