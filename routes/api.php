<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\ApiController;

// Public routes
Route::post('/login', [ApiController::class, 'login']);
Route::get('/pengumuman', [ApiController::class, 'pengumuman']);
Route::get('/santri/{id}', [ApiController::class, 'santriById']);
Route::get('/santri/ortu/{id}', [ApiController::class, 'santriByOrtu']);
Route::get('/ortu/{id}', [ApiController::class, 'santriByOrtu']);
Route::get('/kehadiran-mingguan/{id}', [ApiController::class, 'kehadiranMingguan']);
Route::get('/kehadiran-bytime/{id}', [ApiController::class, 'kehadiranSummary']);
Route::get('/perizinan/{id}', [ApiController::class, 'perizinan']);

// Protected routes — butuh token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/broadcasting/auth', function (Request $request) {
        return Broadcast::auth($request);
    });
    Route::get('/chat/{parentId}/history', [ApiController::class, 'chatHistory']);
    Route::post('/chat/{parentId}/send-api', [ApiController::class, 'sendMessage']);
});