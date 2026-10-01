<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\RadiologiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PencarianKodeController;
use App\Http\Controllers\AuditTrailController;

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
// Rute poli utama kini mengambil data dari database melalui PoliController
Route::get('/poli', [PoliController::class, 'index'])->name('poli');

Route::get('/poli/show/{slug}', function ($slug) {
    return view('poli.' . $slug);
})->name('poli.show');

Route::get('/poli/syaraf', [PoliController::class, 'saraf'])->name('poli.syaraf');
Route::get('/poli/syaraf/detail/{no_rm}', function ($no_rm) {
    return view('poli.syaraf-detail', ['no_rm' => $no_rm]);
})->name('poli.syaraf.detail');
Route::get('/poli/syaraf/detail/{no_rm}/{tanggal}', function ($no_rm, $tanggal) {
    return view('poli.syaraf-kunjungan-baru', compact('no_rm', 'tanggal'));
})->name('poli.syaraf.kunjungan.detail');
Route::get('/poli/syaraf/kunjungan-baru/{no_rm}', function ($no_rm) {
    return view('poli.syaraf-kunjungan-baru', ['no_rm' => $no_rm]);
})->name('poli.syaraf.kunjungan-baru');

/* ----------------------------------------------------
 * Rute Clinical Pathway Poli Saraf (Tambahan Baru)
 * ---------------------------------------------------- */
Route::get('/poli/saraf/clinical-pathway', [PoliController::class, 'clinicalPathway'])->name('poli.clinical_pathway');

Route::get('/poli/obgyn', [PoliController::class, 'obgyn'])->name('poli.obgyn');
Route::get('/poli/obgyn/kunjungan/{no_rm}', function ($no_rm) {
    return view('poli.obgyn-kunjungan', ['no_rm' => $no_rm]);
})->name('poli.obgyn.kunjungan');
Route::get('/poli/obgyn/kunjungan/{no_rm}/{tanggal}', function ($no_rm, $tanggal) {
    return view('poli.obgyn-kunjungan-baru', compact('no_rm', 'tanggal'));
})->name('poli.obgyn.kunjungan.detail');
Route::get('/poli/obgyn/kunjungan-baru/{no_rm}', function ($no_rm) {
    return view('poli.obgyn-kunjungan-baru', ['no_rm' => $no_rm]);
})->name('poli.obgyn.kunjungan-baru');

Route::get('/poli/jantung', function () {
    return view('poli.jantung');
})->name('poli.jantung');

Route::get('/poli/jiwa', [PoliController::class, 'jiwa'])->name('poli.jiwa');

/*
|--------------------------------------------------------------------------
| Pencarian Kode ICD (Diagnosis ICD-10 & Tindakan ICD-9-CM)
|--------------------------------------------------------------------------
| Dipakai oleh kolom Penyakit & Tindakan di halaman kunjungan baru.
| {jenis} hanya boleh: penyakit | tindakan
*/
Route::get('/cari-kode/{jenis}', [PencarianKodeController::class, 'cari'])
    ->whereIn('jenis', ['penyakit', 'tindakan'])
    ->name('cari.kode');

/*
|--------------------------------------------------------------------------
| Radiologi
|--------------------------------------------------------------------------
*/
Route::get('/radiologi', [RadiologiController::class, 'index'])->name('radiologi.index');

/*
|--------------------------------------------------------------------------
| Audit Trail
|--------------------------------------------------------------------------
*/
Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit-trail.index');