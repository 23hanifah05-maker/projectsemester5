<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\RadiologiController;

Route::get('/', function () {
    return redirect()->route('poli.syaraf');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('poli.syaraf');
})->name('login.process');

Route::post('/logout', function () {
    return redirect()->route('login');
})->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {
    Route::get('/', [PendaftaranController::class, 'index'])->name('index');
    Route::get('/create', [PendaftaranController::class, 'create'])->name('create');
    Route::post('/', [PendaftaranController::class, 'store'])->name('store');
    Route::get('/{id}', [PendaftaranController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [PendaftaranController::class, 'edit'])->name('edit');
    Route::put('/{id}', [PendaftaranController::class, 'update'])->name('update');
    Route::delete('/{id}', [PendaftaranController::class, 'destroy'])->name('destroy');
    Route::get('/{id}/tambah', [PendaftaranController::class, 'tambah'])->name('tambah');
});

Route::get('/poli/syaraf', [PoliController::class, 'saraf'])->name('poli.syaraf');
Route::get('/poli/obgyn', [PoliController::class, 'obgyn'])->name('poli.obgyn');
Route::get('/poli/jantung', [PoliController::class, 'jantung'])->name('poli.jantung');
Route::get('/poli/jiwa', [PoliController::class, 'jiwa'])->name('poli.jiwa');

Route::get('/radiologi', [RadiologiController::class, 'index'])->name('radiologi.index');