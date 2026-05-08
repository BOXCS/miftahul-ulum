<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kehadiran;
use App\Helpers\WaktuShalatHelper;

class KehadiranController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'santri_id' => 'required|string|max:10',
            'nama_santri' => 'required|string|max:100',
            'jam_masuk' => 'required|date_format:H:i:s',
            'jam_keluar' => 'nullable|date_format:H:i:s',
            'status' => 'required|in:Hadir,Tidak Hadir,Izin,Sakit',
            'tanggal_waktu' => 'required|date',
        ]);

        // Tentukan waktu shalat berdasarkan jam masuk
        $waktuShalat = WaktuShalatHelper::getWaktuShalat($request->jam_masuk);

        // Simpan data kehadiran
        Kehadiran::create([
            'santri_id' => $request->santri_id,
            'nama_santri' => $request->nama_santri,
            'jam_masuk' => $request->jam_masuk,
            'jam_keluar' => $request->jam_keluar,
            'status' => $request->status,
            'waktu_shalat' => $waktuShalat, // Waktu shalat diisi otomatis
            'tanggal_waktu' => $request->tanggal_waktu,
        ]);

        return redirect()->route('kehadiran.index')->with('success', 'Data kehadiran berhasil disimpan.');
    }
}