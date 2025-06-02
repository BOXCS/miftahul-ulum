<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SantriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * API: Get santri by orang tua ID - HANYA DATA SANTRI
     */
    public function apiByOrtuId($id_ortu): JsonResponse
    {
        try {
            $santri = Santri::where('id_ortu', $id_ortu)
                ->get()
                ->map(function ($item) {
                    return [
                        'id_santri' => $item->id_santri,
                        'nama' => $item->nama,
                        'tahun_angkatan' => $item->tahun_angkatan,
                        'sidik_jari' => $item->sidik_jari,
                        'status' => $item->status,
                        'id_ortu' => $item->id_ortu,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Data santri berhasil diambil berdasarkan ID orang tua',
                'data' => $santri
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data santri',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get all santri data
     */
    public function apiIndex(): JsonResponse
    {
        try {
            $santri = Santri::with('ortu')->get()->map(function ($item) {
                return [
                    'id_santri' => $item->id_santri,
                    'nama' => $item->nama,
                    'tahun_angkatan' => $item->tahun_angkatan,
                    'sidik_jari' => $item->sidik_jari,
                    'status' => $item->status,
                    'id_ortu' => $item->id_ortu,
                    'ortu' => $item->ortu ? [
                        'nama_lengkap' => $item->ortu->nama_lengkap,
                        'alamat' => $item->ortu->alamat,
                        'no_telp' => $item->ortu->no_telp
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data santri berhasil diambil',
                'data' => $santri
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data santri',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get specific santri data
     */
    public function apiShow(string $id): JsonResponse
    {
        try {
            $santri = Santri::with('ortu')->where('id_santri', $id)->first();

            if (!$santri) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data santri tidak ditemukan'
                ], 404);
            }

            $santriData = [
                'id_santri' => $santri->id_santri,
                'nama' => $santri->nama,
                'tahun_angkatan' => $santri->tahun_angkatan,
                'sidik_jari' => $santri->sidik_jari,
                'status' => $santri->status,
                'id_ortu' => $santri->id_ortu,
                'ortu' => $santri->ortu ? [
                    'nama_lengkap' => $santri->ortu->nama_lengkap,
                    'alamat' => $santri->ortu->alamat,
                    'no_telp' => $santri->ortu->no_telp
                ] : null
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data santri berhasil diambil',
                'data' => $santriData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data santri',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get santri profile for mobile app
     */
    public function apiProfile(string $id): JsonResponse
    {
        try {
            $santri = Santri::with('ortu')->find($id);

            if (!$santri) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data santri tidak ditemukan'
                ], 404);
            }

            $santriData = [
                'id' => $santri->id,
                'id_santri' => $santri->id_santri,
                'nama' => $santri->nama,
                'tahun_angkatan' => $santri->tahun_angkatan,
                'sidik_jari' => $santri->sidik_jari,
                'status' => $santri->status,
                'id_ortu' => $santri->id_ortu,
                'ortu' => $santri->ortu ? [
                    'nama_lengkap' => $santri->ortu->nama_lengkap,
                    'alamat' => $santri->ortu->alamat,
                    'no_telp' => $santri->ortu->no_telp
                ] : null
            ];

            return response()->json([
                'success' => true,
                'message' => 'Data santri berhasil diambil',
                'data' => $santriData
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data santri',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function apiByOrtuId(string $idOrtu): JsonResponse
    {
        try {
            $santri = Santri::with('ortu')->where('id_ortu', $idOrtu)->get()->map(function ($item) {
                return [
                    'id_santri' => $item->id_santri,
                    'nama' => $item->nama,
                    'tahun_angkatan' => $item->tahun_angkatan,
                    'sidik_jari' => $item->sidik_jari,
                    'status' => $item->status,
                    'id_ortu' => $item->id_ortu,
                    'ortu' => $item->ortu ? [
                        'nama_lengkap' => $item->ortu->nama_lengkap,
                        'alamat' => $item->ortu->alamat,
                        'no_telp' => $item->ortu->no_telp,
                    ] : null,
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Data santri berhasil diambil',
                'data' => $santri
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data santri',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $idSantriTerakhir=Santri::select('tahun_angkatan', DB::raw('MAX(id_santri) as id_terbaru'))->groupBy('tahun_angkatan')->get();
        $ortu = OrangTua::get(['id_ortu', 'nama_lengkap']);
        return view('management.santri.create', compact('ortu', 'idSantriTerakhir'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $tahun = date('Y');
        $request->validate([
            'id_santri' => 'required|string',
            'nama' => 'required|string',
            'tahun_angkatan' => "required|numeric|max:$tahun",
            'id_ortu' => 'required|string',
            'status' => 'required|string',
        ]);
        Santri::create($request->all());
        return redirect()->route('management.index')->with('success', 'Santri berhasil ditambahkan.');
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
        $idSantriTerakhir=Santri::select('tahun_angkatan', DB::raw('MAX(id_santri) as id_terbaru'))->groupBy('tahun_angkatan')->get();
        $ortu = OrangTua::get(['id_ortu', 'nama_lengkap']);
        $santri = Santri::with('ortu')->find($id);
        return view('management.santri.create', compact('santri', 'idSantriTerakhir', 'ortu'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tahun = date('Y');
        $request->validate([
            'id_santri' => 'required|string',
            'nama' => 'required|string',
            'tahun_angkatan' => "required|numeric|max:$tahun",
            'id_ortu' => 'required|string',
            'status' => 'required|string',
        ]);
        $santri = Santri::find($id);
        $santri->update($request->all());
        return redirect()->route('management.index')->with('success', 'Santri berhasil ditambahkan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $santri = Santri::find($id);
        $santri->delete();

        return redirect()->route('management.index')->with('success', 'Santri berhasil dihapus');
    }
}
