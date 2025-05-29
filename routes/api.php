<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\MobileDataController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\SantriController;
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
Route::get('/kehadiran-bytime/{id}',[MobileDataController::class, 'kehadiranByIdByTime']);
Route::get('/kehadiran-mingguan/{id}', [MobileDataController::class,'kehadiranSeminggu']);
Route::get('/perizinan/{id}', [MobileDataController::class, 'perizinanSetahun']);
Route::get('/ortu/{id}',[MobileDataController::class, 'dataOrtuSantriById']);
Route::get('/pengumuman', [MobileDataController::class, 'pengumuman']);
Route::prefix('santri')->group(function () {
    Route::get('/', [SantriController::class, 'apiIndex']);
    Route::get('/{id}', [SantriController::class, 'apiShow']);
    Route::get('/{id}/profile', [SantriController::class, 'apiProfile']);
});
Route::get('/santri/ortu/{idOrtu}', [SantriController::class, 'apiByOrtuId']);
Route::get('/chat/user-info/{session}', function($sessionId) {
    $session = \App\Models\ChatSession::where('id_session', $sessionId)->first();

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
// routes/api.php
Route::post('/forgot-password', [AuthController::class, 'sendResetLink']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
