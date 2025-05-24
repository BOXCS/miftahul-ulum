<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\MobileDataController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ChatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/attendance', [AttendanceController::class, 'getAttendanceData']);

Route::get('/akun', [AkunController::class, 'index']); // Ambil semua akun
Route::post('/akun', [AkunController::class, 'store']); // Tambah akun
Route::get('/akun/{id}', [AkunController::class, 'show']); // Ambil akun berdasarkan ID
Route::put('/akun/{id}', [AkunController::class, 'update']); // Update akun
Route::delete('/akun/{id}', [AkunController::class, 'destroy']); // Hapus akun

// API for mobile
Route::get('/kehadiran/{id}', [MobileDataController::class, 'kehadiranById']);
Route::get('/santri/{id}', [MobileDataController::class, 'dataSantriById']);
Route::get('/pengumuman', [MobileDataController::class, 'pengumuman']);

Route::get('/chat/messages/{session}', [MobileDataController::class, 'chatMessagesBySession']);

Route::get('/chat/user-info/{session}', function ($sessionId) {
    $session = \App\Models\ChatSession::with('orangTua')->where('id_session', $sessionId)->first();

    if (!$session || !$session->orangTua) {
        return response()->json(['message' => 'Session or user not found'], 404);
    }

    // Cari santri yang wali-nya orang_tua ini (ambil nama santri-nya sebagai "wali_dari")
    $santri = \App\Models\Santri::where('id_ortu', $session->id_ortu)->first();

    return [
        'nama_orang_tua' => $session->orangTua->nama_lengkap,
        'asal_daerah' => $session->orangTua->alamat,
        'wali_dari' => $santri ? $santri->nama : null,
    ];
});


// for mobile authentication
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
