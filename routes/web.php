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

Route::get('/', fn () => view('landing'));

// Login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/', [AuthController::class, 'authenticate'])->name('auth.authenticate');

// Public laporan kehadiran
Route::get('/laporan-kehadiran', [AttendanceController::class, 'index'])->name('laporan.kehadiran');
Route::get('/laporan-kehadiran/export', [AttendanceController::class, 'export'])->name('laporan.kehadiran.export');

// Semua route yang butuh login dan role admin/superadmin
Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {
    // Dashboard tunggal
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource routes
    Route::resource('management', ManagementController::class)->names('management');
    Route::resource('santri', SantriController::class)->names('santri');
    Route::resource('orang-tua', OrangtuaController::class)->names('orangtua');
    Route::resource('staff', StaffController::class)->names('staff');
    Route::resource('report', ReportController::class)->names('report');
    Route::resource('faq', FaqController::class);
    Route::resource('chat', ChatController::class)->names('chat');
    Route::resource('announcement', PengumumanController::class)->names('announcement');
    Route::resource('pengumuman', PengumumanController::class)->names('pengumuman');
    Route::resource('attendance', AttendanceController::class)->names('attendance');

    // Tambahan endpoint khusus
    Route::get('/chat/{id_staf}/{id_ortu}', fn ($id_staf, $id_ortu) => view('chat', compact('id_staf', 'id_ortu')));
    Route::get('/announcement', fn () => view('announcement'))->name('announcement');
    Route::post('/api/chat/send', [ChatController::class, 'store']);
    Route::get('/api/chat/session/{id}', [ChatController::class, 'getSessionMessages']);
    Route::get('/laporan-kehadiran/export', [AttendanceController::class, 'export'])->name('attendance.export');
});

// Reset password routes untuk web
Route::get('/reset-password', [AuthController::class, 'showResetForm'])
    ->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'processWebReset'])
    ->name('password.update');

// Success page setelah reset password
Route::get('/reset-success', function () {
    return view('reset-success');
})->name('reset.success');

// Deep link handler untuk mobile app
Route::get('/app-redirect', function () {
    return view('app-redirect');
})->name('app.redirect');
