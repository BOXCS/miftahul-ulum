<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\IotController;

Route::post("/login", [ApiController::class, "login"]);
// Auth channel Reverb untuk mobile (tidak pakai Laravel session)
Route::post("/broadcasting/auth", [ApiController::class, "broadcastAuth"]);
Route::get("/pengumuman", [ApiController::class, "pengumuman"]);
Route::get("/santri/ortu/{id}", [ApiController::class, "santriByOrtu"]);
Route::get("/santri/{id}", [ApiController::class, "santriById"]);
Route::get("/ortu/{id}", [ApiController::class, "santriByOrtu"]);
Route::get("/kehadiran-mingguan/{id}", [ApiController::class, "kehadiranMingguan"]);
Route::get("/kehadiran-bytime/{id}", [ApiController::class, "kehadiranSummary"]);
Route::get("/perizinan/{id}", [ApiController::class, "perizinan"]);
Route::post("/perizinan", [ApiController::class, "submitPerizinan"]);
Route::get("/perizinan-by-ortu/{parentId}", [ApiController::class, "perizinanByOrtu"]);
Route::get("/chat/{parentId}/history", [ApiController::class, "chatHistory"]);
Route::post("/chat/{parentId}/send-api", [ApiController::class, "sendMessage"]);

// FAQ — diakses oleh mobile (hanya yang is_active=true)
Route::get("/faq", [ApiController::class, "getFaqs"]);
Route::get("/faq/{id}", [ApiController::class, "getFaqById"]);

// ─────────── IoT / Fingerprint (ESP32 ↔ Web) ───────────
Route::get("/iot/ping", [IotController::class, "ping"]);                 // ESP32 auto-discovery
Route::get("/iot/prayer-times", [IotController::class, "prayerTimes"]);  // Debug jadwal sholat aktif
Route::get("/iot/poll", [IotController::class, "poll"]);                 // ESP32 polling
Route::post("/iot/enroll-request", [IotController::class, "enrollRequest"]);   // Web → ESP32
Route::post("/iot/enroll-complete", [IotController::class, "enrollComplete"]); // ESP32 lapor balik
Route::post("/absensi/fingerprint", [IotController::class, "attendanceScan"]); // ESP32 → Server
