<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\KehadiranController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagementController;
use App\Http\Controllers\OrangtuaController;
use App\Http\Controllers\SantriController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\ChatController;

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

//Route::get('/', function () {
//    return view('attendance');
//});

// Route::get('/', function () {
//     return redirect('/attendance');
// });

// Route::get('/attendance', [AttendanceController::class, 'attendance']);

// Halaman Utama
Route::get('/', function () {
    return view('landing');
});

// Halaman login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'authenticate'])->name('auth.authenticate');

Route::get('/laporan-kehadiran', [AttendanceController::class, 'index'])->name('laporan.kehadiran');
Route::get('/laporan-kehadiran/export', [AttendanceController::class, 'export'])->name('laporan.kehadiran.export');

// Halaman Register
// Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);

// Route::get('/register-superadmin', [AuthController::class, 'showSuperadminForm'])->name('register.superadmin');
// Route::post('/register-superadmin', [AuthController::class, 'registerSuperadmin']);


// Dashboard Admin (akses seperti sebelumnya)
Route::middleware(['auth', 'role:admin'])->group(function () {
});

// Dashboard Superadmin (kosongan)
Route::middleware(['auth', 'role:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard', [SuperAdminController::class, 'index'])->name('superadmin.dashboard');
});
// Halaman Lainnya (Harus Login)

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('management', ManagementController::class)->names('management');
    Route::resource('santri', SantriController::class)->names('santri');
    Route::resource('orang-tua', OrangtuaController::class)->names('orangtua');
    Route::resource('staff', StaffController::class)->names('staff');

    Route::resource('report', ReportController::class)->names('report');

    Route::get('/chat/{id_staf}/{id_ortu}', function ($id_staf, $id_ortu) {
        return view('chat', compact('id_staf', 'id_ortu'));
    });

    Route::get('/announcement', function () {
        return view('announcement');
    })->name('announcement');
    Route::resource('faq', FaqController::class);

    Route::resource('chat', ChatController::class)->names('chat');
    Route::post('/api/chat/send', [App\Http\Controllers\ChatController::class, 'store']);
    Route::get('/api/chat/session/{id}', [ChatController::class, 'getSessionMessages']);

    Route::resource('announcement', PengumumanController::class)->names('announcement');

    // Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
    // Route::post('/pengumuman', [PengumumanController::class, 'store'])->name('pengumuman.store');

    Route::resource('pengumuman', PengumumanController::class)->names('pengumuman');
    Route::resource('attendance', AttendanceController::class)->names('attendance');
    Route::get('/laporan-kehadiran/export', [AttendanceController::class, 'export'])->name('attendance.export');
});

// });
