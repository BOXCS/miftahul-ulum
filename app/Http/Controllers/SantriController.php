<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
        return view('management.santri.create');
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
        $santri = Santri::with('ortu')->find($id);
        $edit = true;
        return view('management.santri.create', compact('santri', 'edit'));
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
        $santri = Santri::find($id);
        $santri->delete();

        return redirect()->route('management.index')->with('success', 'Santri berhasil dihapus');
    }
}
