<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

// Halaman awal → langsung ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Halaman Login
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

// Proses Login
Route::post('/login', [LoginController::class, 'login'])->name('login.process');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Poli Jantung
Route::get('/poli/jantung', function () {
    return view('poli.jantung');
})->name('poli.jantung');

// Poli Jiwa
Route::get('/poli/jiwa', function () {
    return view('poli.jiwa');
})->name('poli.jiwa');

// Poli Syaraf
Route::get('/poli/syaraf', function () {
    return view('poli.syaraf');
})->name('poli.syaraf');

// Poli Obgyn
Route::get('/poli/obgyn', function () {
    return view('poli.obgyn');
})->name('poli.obgyn');

// Radiologi
Route::get('/radiologi', function () {
    return view('poli.radiologi');
})->name('radiologi');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
