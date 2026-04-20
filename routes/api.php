<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post("/login", [ApiController::class, "login"]);
Route::get("/pengumuman", [ApiController::class, "pengumuman"]);
Route::get("/santri/ortu/{id}", [ApiController::class, "santriByOrtu"]);
Route::get("/kehadiran-mingguan/{id}", [ApiController::class, "kehadiranMingguan"]);
Route::get("/kehadiran-bytime/{id}", [ApiController::class, "kehadiranSummary"]);
Route::get("/perizinan/{id}", [ApiController::class, "perizinan"]);
Route::get("/chat/{parentId}/history", [ApiController::class, "chatHistory"]);
Route::post("/chat/{parentId}/send-api", [ApiController::class, "sendMessage"]);
