<?php

use App\Http\Controllers\AkunController;
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
