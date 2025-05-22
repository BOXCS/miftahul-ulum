<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use App\Models\Kehadiran;
use App\Models\Pengumuman;
use App\Models\Santri;
use Illuminate\Http\Request;

class MobileDataController extends Controller
{
    // untuk mengambil data Kehadiran seorang santri
    public function kehadiranById(string $id)
    {
        $kehadiran = Kehadiran::with('santri')->where('id_santri','=',$id)->get();
        return new AllResource(true, "data kehadiran santri = ".$kehadiran->first()->santri->nama, $kehadiran);
    }
    // untuk mengambil data santri menggunakan id
    public function dataSantriById(string $id)
    {
        $santri = Santri::find( $id);
        return new AllResource(true, "data santri atas nama = ".$santri->nama, $santri);
    }
    // mengambil data pengumuman beserta guru yang mengumumkan
    public function Pengumuman()
    {
        $pengumuman = Pengumuman::with('staff')->get();
        return new AllResource(true, 'Semua pengumuman beserta siapa yang mengumumkan', $pengumuman);
    }
}
