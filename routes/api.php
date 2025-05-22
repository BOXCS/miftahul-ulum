<?php

use App\Http\Controllers\AkunController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\AttendanceController;
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

Route::get('/chat/user-info/{session}', function($sessionId) {
    $session = \App\Models\ChatSession::where('id_session', $sessionId)->first();
    return [
        'nama_orang_tua' => $session->nama_orang_tua,
        'wali_dari' => $session->wali_dari,
        'asal_daerah' => $session->asal_daerah,
    ];
});

// for mobile authentication
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'index']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
