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

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Pendaftaran
|--------------------------------------------------------------------------
*/

Route::prefix('pendaftaran')->name('pendaftaran.')->group(function () {

    Route::get('/', [PendaftaranController::class, 'index'])
        ->name('index');

    Route::get('/create', [PendaftaranController::class, 'create'])
        ->name('create');

    Route::post('/', [PendaftaranController::class, 'store'])
        ->name('store');

    Route::get('/{id}', [PendaftaranController::class, 'show'])
        ->name('show');

    Route::get('/{id}/edit', [PendaftaranController::class, 'edit'])
        ->name('edit');

    Route::put('/{id}', [PendaftaranController::class, 'update'])
        ->name('update');

    Route::delete('/{id}', [PendaftaranController::class, 'destroy'])
        ->name('destroy');

    Route::get('/{id}/tambah', [PendaftaranController::class, 'tambah'])
        ->name('tambah');
});


/*
|--------------------------------------------------------------------------
| Poli
|--------------------------------------------------------------------------
*/

// Rute poli utama melalui PoliController
Route::get('/poli', [PoliController::class, 'index'])
    ->name('poli');


// Menampilkan poli berdasarkan slug
Route::get('/poli/show/{slug}', function ($slug) {
    return view('poli.' . $slug);
})->name('poli.show');


/*
|--------------------------------------------------------------------------
| Poli Syaraf
|--------------------------------------------------------------------------
*/

// Halaman utama Poli Syaraf
Route::get('/poli/syaraf', [PoliController::class, 'saraf'])
    ->name('poli.syaraf');


// Detail pasien Poli Syaraf
Route::get('/poli/syaraf/detail/{no_rm}', function ($no_rm) {

    return view('poli.syaraf-detail', [
        'no_rm' => $no_rm
    ]);

})->name('poli.syaraf.detail');


// Detail kunjungan Poli Syaraf
Route::get('/poli/syaraf/detail/{no_rm}/{tanggal}', function ($no_rm, $tanggal) {

    return view('poli.syaraf-kunjungan-baru', [
        'no_rm' => $no_rm,
        'tanggal' => $tanggal
    ]);

})->name('poli.syaraf.kunjungan.detail');


// Kunjungan baru Poli Syaraf
Route::get('/poli/syaraf/kunjungan-baru/{no_rm}', function ($no_rm) {

    return view('poli.syaraf-kunjungan-baru', [
        'no_rm' => $no_rm
    ]);

})->name('poli.syaraf.kunjungan-baru');


/*
|--------------------------------------------------------------------------
| Clinical Pathway Poli Saraf
|--------------------------------------------------------------------------
*/

Route::get(
    '/poli/saraf/clinical-pathway',
    [PoliController::class, 'clinicalPathway']
)->name('poli.clinical_pathway');


/*
|--------------------------------------------------------------------------
| Poli Obgyn
|--------------------------------------------------------------------------
*/

Route::get('/poli/obgyn', [PoliController::class, 'obgyn'])
    ->name('poli.obgyn');


// Kunjungan Poli Obgyn
Route::get('/poli/obgyn/kunjungan/{no_rm}', function ($no_rm) {

    return view('poli.obgyn-kunjungan', [
        'no_rm' => $no_rm
    ]);

})->name('poli.obgyn.kunjungan');


// Detail kunjungan Poli Obgyn
Route::get('/poli/obgyn/kunjungan/{no_rm}/{tanggal}', function ($no_rm, $tanggal) {

    return view('poli.obgyn-kunjungan-baru', [
        'no_rm' => $no_rm,
        'tanggal' => $tanggal
    ]);

})->name('poli.obgyn.kunjungan.detail');


// Kunjungan baru Poli Obgyn
Route::get('/poli/obgyn/kunjungan-baru/{no_rm}', function ($no_rm) {

    return view('poli.obgyn-kunjungan-baru', [
        'no_rm' => $no_rm
    ]);

})->name('poli.obgyn.kunjungan-baru');


/*
|--------------------------------------------------------------------------
| Poli Jantung
|--------------------------------------------------------------------------
*/

Route::get('/poli/jantung', function () {

    return view('poli.jantung');

})->name('poli.jantung');


/*
|--------------------------------------------------------------------------
| Poli Jiwa
|--------------------------------------------------------------------------
*/

Route::get('/poli/jiwa', [PoliController::class, 'jiwa'])
    ->name('poli.jiwa');


/*
|--------------------------------------------------------------------------
| Pencarian Kode ICD
|--------------------------------------------------------------------------
| Diagnosis ICD-10 dan Tindakan ICD-9-CM
*/

Route::get(
    '/cari-kode/{jenis}',
    [PencarianKodeController::class, 'cari']
)
->whereIn('jenis', ['penyakit', 'tindakan'])
->name('cari.kode');


/*
|--------------------------------------------------------------------------
| Radiologi
|--------------------------------------------------------------------------
*/

Route::get('/radiologi', [RadiologiController::class, 'index'])
    ->name('radiologi.index');


/*
|--------------------------------------------------------------------------
| Audit Trail
|--------------------------------------------------------------------------
*/

Route::get('/audit-trail', [AuditTrailController::class, 'index'])
    ->name('audit-trail.index');