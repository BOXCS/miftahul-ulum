<?php

namespace App\Http\Controllers\Api;

use Ably\AblyRest;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllResource;
use App\Models\ChatMessage;
use App\Models\Kehadiran;
use App\Models\OrangTua;
use App\Models\Pengumuman;
use App\Models\Perizinan;
use App\Models\Santri;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Str;

class MobileDataController extends Controller
{

    // untuk mengambil data Kehadiran seorang santri seminggu, sebulan, dan setahun terakhir
    public function kehadiranByIdByTime(string $id)
    {
        $santri = Santri::find($id)->nama;
        // tanggal hari ini
        $endDate = Carbon::today()->toDateString();
        // total kehadiran Mingguan
        $seminggu = Carbon::today()->subDays(7)->toDateString();     // 7 hari terakhir (termasuk hari ini)
        $jumlahKehadiranMingguan = Kehadiran::with("santri")->select('id_santri', DB::raw('COUNT(*) as jumlah_kehadiran'))->whereBetween('waktu', [$seminggu, $endDate])->where('id_santri', $id)->groupBy('id_santri')->first();
        // total kehadiran Bulanan
        $sebulan = Carbon::today()->subMonth()->toDateString();     // 1 bulan terakhir
        $jumlahKehadiranBulanan = Kehadiran::select('id_santri', DB::raw('COUNT(*) as jumlah_kehadiran'))->whereBetween('waktu', [$sebulan, $endDate])->where('id_santri', $id)->groupBy('id_santri')->first();
        // total kehadiran Tahunan
        $setahun = Carbon::today()->subYear()->toDateString();      // 1 tahun terakhir
        $jumlahKehadiranTahunan = Kehadiran::select('id_santri', DB::raw('COUNT(*) as jumlah_kehadiran'))->whereBetween('waktu', [$setahun, $endDate])->where('id_santri', $id)->groupBy('id_santri')->first();
        $data = [
            "jumlah kehadiran seminggu" => $jumlahKehadiranMingguan["jumlah_kehadiran"],
            "Jumlah kehadiran mingguan seharusnya" => date_diff(date_create($seminggu), date_create($endDate))->days * 5,
            "jumlah kehadiran sebulan" => $jumlahKehadiranBulanan["jumlah_kehadiran"],
            "Jumlah kehadiran bulan seharusnya" => date_diff(date_create($sebulan), date_create($endDate))->days * 5,
            "jumlah kehadiran setahun" => $jumlahKehadiranTahunan["jumlah_kehadiran"],
            "Jumlah kehadiran tahunan seharusnya" => date_diff(date_create($setahun), date_create($endDate))->days * 5
        ];
        return new AllResource(true, "data kehadiran $santri", $data);
        // $kehadiran = Kehadiran::with('santri')->where('id_santri', '=', $id)->get();
        // return new AllResource(true, "data kehadiran santri = " . $kehadiran->first()->santri->nama, $kehadiran);
    }

    // untuk mengambil data santri menggunakan id
    public function dataOrtuSantriById(string $id)
    {
        $ortu = OrangTua::with('santri')->find($id);
        return new AllResource(true, "data santri atas nama = " . $ortu->nama_lengkap, $ortu);
    }

    // kehadiran harian seminggu terakhir
    public function kehadiranSeminggu(string $id)
    {
        $startDate = Carbon::today()->subDays(7);
        $endDate = Carbon::today();
        $santri = Santri::find($id)->nama;
        $kehadiran = Kehadiran::select(
                'waktu as tanggal',
                DB::raw('COUNT(*) as jumlah_kehadiran'),
                DB::raw("MAX(CASE WHEN waktu_shalat = 'Subuh' THEN 1 ELSE 0 END) as Subuh"),
                DB::raw("MAX(CASE WHEN waktu_shalat = 'Dzuhur' THEN 1 ELSE 0 END) as Dzuhur"),
                DB::raw("MAX(CASE WHEN waktu_shalat = 'Ashar' THEN 1 ELSE 0 END) as Ashar"),
                DB::raw("MAX(CASE WHEN waktu_shalat = 'Maghrib' THEN 1 ELSE 0 END) as Maghrib"),
                DB::raw("MAX(CASE WHEN waktu_shalat = 'Isya' THEN 1 ELSE 0 END) as Isya")
            )
            ->whereBetween('waktu', [$startDate, $endDate])
            ->where('id_santri', $id)
            ->groupBy('waktu')
            ->orderBy('waktu', 'asc')
            ->get();

        return new AllResource(true, "data kehadiran mingguan $santri", $kehadiran);
    }
    
    // data perizinan
    public function perizinanSetahun(string $id)
    {
        $setahun = Carbon::today()->subYear()->toDateString();      // 1 tahun terakhir
        $perizinan = Perizinan::where('id_santri', $id)->where('waktu', '>=', $setahun)->get();
        $santri = Santri::find($id)->nama;

        return new AllResource(true, "data kehadiran mingguan $santri", $perizinan);
        // $santri = Santri::find($id);
        // return new AllResource(true, "data santri atas nama = " . $santri->nama, $santri);
    }
    // mengambil data pengumuman beserta guru yang mengumumkan
    public function Pengumuman()
    {
        $pengumuman = Pengumuman::with('staff')->get();
        return new AllResource(true, 'Semua pengumuman beserta siapa yang mengumumkan', $pengumuman);
    }

    public function chatMessagesBySession($session)
    {
        $messages = \App\Models\ChatMessage::where('id_session', $session)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'id_session' => 'required|exists:chat_sessions,id_session',
            'pengirim' => 'required|string',
            'pesan' => 'required|string',
        ]);

        $message = ChatMessage::create([
            'id_session' => $request->id_session,
            'pengirim' => $request->pengirim,
            'pesan' => $request->pesan,
            'waktu' => now(),
        ]);

        // Publikasikan ke channel ably
        $ably = new AblyRest(env('ABLY_API_KEY'));
        $ably->channels->get('pesantren-chat')->publish('new-message', [
            'id_session' => $request->id_session,
            'pengirim' => $request->pengirim,
            'pesan' => $request->pesan,
            'waktu' => now()->toDateTimeString(),
        ]);

        return response()->json(['success' => true, 'message' => $message]);
    }


    public function startChatSession(Request $request)
    {
        $request->validate([
            'id_ortu' => 'required|exists:orang_tua,id',
        ]);

        $existing = \App\Models\ChatSession::where('id_ortu', $request->id_ortu)->first();
        if ($existing) return response()->json($existing);

        $new = \App\Models\ChatSession::create([
            'id_session' => \Illuminate\Support\Str::uuid(),
            'id_ortu' => $request->id_ortu,
        ]);

        return response()->json($new);
    }
}
