<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\RadiologiController;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
| Halaman awal aplikasi diarahkan ke login dulu, bukan langsung ke poli.
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Auth (Login / Logout)
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Pendaftaran
|--------------------------------------------------------------------------
*/
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

/*
|--------------------------------------------------------------------------
| Poli
|--------------------------------------------------------------------------
*/
Route::get('/poli', function () {
    $daftarPoli = [
        'jantung' => 'Poli Jantung',
        'jiwa'    => 'Poli Jiwa',
        'syaraf'  => 'Poli Syaraf',
        'obgyn'   => 'Poli Obgyn',
    ];
    return view('poli.poli', [
        'namaPoli'   => 'Poli',
        'daftarPoli' => $daftarPoli,
        'poliAktif'  => null,
    ]);
})->name('poli');

Route::get('/poli/show/{slug}', function ($slug) {
    return view('poli.' . $slug);
})->name('poli.show');

Route::get('/poli/syaraf', [PoliController::class, 'saraf'])->name('poli.syaraf');
Route::get('/poli/obgyn', [PoliController::class, 'obgyn'])->name('poli.obgyn');
Route::get('/poli/jantung', [PoliController::class, 'jantung'])->name('poli.jantung');
Route::get('/poli/jiwa', [PoliController::class, 'jiwa'])->name('poli.jiwa');

/*
|--------------------------------------------------------------------------
| Radiologi
|--------------------------------------------------------------------------
*/
Route::get('/radiologi', [RadiologiController::class, 'index'])->name('radiologi.index');