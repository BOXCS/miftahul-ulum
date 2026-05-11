<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\ApiController;

Route::post("/login", [ApiController::class, "login"]);
// Auth channel Reverb untuk mobile (tidak pakai Laravel session)
Route::post("/broadcasting/auth", [ApiController::class, "broadcastAuth"]);
Route::get("/pengumuman", [ApiController::class, "pengumuman"]);
Route::get("/faq", [ApiController::class, "faq"]);
Route::get("/faq/{id}", [ApiController::class, "faqDetail"]);
Route::get("/santri/ortu/{id}", [ApiController::class, "santriByOrtu"]);
Route::get("/santri/{id}", [ApiController::class, "santriById"]);
Route::get("/ortu/{id}", [ApiController::class, "santriByOrtu"]);
Route::get("/kehadiran-mingguan/{id}", [ApiController::class, "kehadiranMingguan"]);
Route::get("/kehadiran-bytime/{id}", [ApiController::class, "kehadiranSummary"]);
Route::get("/perizinan/{id}", [ApiController::class, "perizinan"]);
Route::post("/perizinan", [ApiController::class, "storePerizinan"]);
Route::get("/chat/{parentId}/history", [ApiController::class, "chatHistory"]);
Route::post("/chat/{parentId}/send-api", [ApiController::class, "sendMessage"]);

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
    Route::post('/fcm-token', [ApiController::class, 'saveFcmToken']);
});