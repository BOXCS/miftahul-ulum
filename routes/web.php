<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

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
