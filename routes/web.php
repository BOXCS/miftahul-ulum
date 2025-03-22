<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


// Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Halaman login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate']);

// Halaman Register
Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/register-superadmin', [AuthController::class, 'showSuperadminForm'])->name('register.superadmin');
Route::post('/register-superadmin', [AuthController::class, 'registerSuperadmin']);


// Dashboard Admin (akses seperti sebelumnya)
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Dashboard Superadmin (kosongan)
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
});

// Halaman Lainnya (Harus Login)
Route::middleware('auth')->group(function () {
    Route::get('/management', function () {
        return view('management');
    })->name('management');

    Route::get('/attendance', function () {
        return view('attendance');
    })->name('attendance');

    Route::get('/chat', function () {
        return view('chat');
    })->name('chat');

    Route::get('/announcement', function () {
        return view('announcement');
    })->name('announcement');
});
