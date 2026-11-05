<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\PoliController;
use App\Http\Controllers\RadiologiController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PencarianKodeController;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\TtvController;
use App\Http\Controllers\AssesmenController;
use App\Http\Controllers\ClinicalPathwayController;

/*
|--------------------------------------------------------------------------
| HAK AKSES
|--------------------------------------------------------------------------
| admin     : edit semua
| dokter    : edit SOAP, rujukan, informed consent, clinical pathway, resume.
|             Hanya LIHAT: TTV, kode ICD, radiologi, daftar pasien per poli.
| perawat   : edit TTV, S, O, clinical pathway.
|             Hanya LIHAT: A, P, ICD, rujukan, informed consent, resume, radiologi.
| pmik      : edit pendaftaran dan kode ICD (+ SNOMED).
|             Hanya LIHAT: riwayat kunjungan, SOAP, resume.
| radiologi : dashboard dan halaman radiologi.
*/


// ======================================================================
// TANPA LOGIN
// ======================================================================

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');


// ======================================================================
// HARUS LOGIN
// ======================================================================

Route::middleware('auth')->group(function () {

    // ------------------------------------------------------------------
    // Dashboard: semua role
    // ------------------------------------------------------------------
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    // ------------------------------------------------------------------
    // Pendaftaran: pmik (+ admin)
    // ------------------------------------------------------------------
    Route::middleware('role:pmik')
        ->prefix('pendaftaran')
        ->name('pendaftaran.')
        ->group(function () {

            Route::get('/', [PendaftaranController::class, 'index'])->name('index');
            Route::get('/create', [PendaftaranController::class, 'create'])->name('create');
            Route::post('/', [PendaftaranController::class, 'store'])->name('store');

            // Tambah kunjungan baru untuk pasien yang sudah terdaftar
            Route::post('/{no_rm}/kunjungan', [PendaftaranController::class, 'storeKunjungan'])
                ->name('kunjungan.store');

            Route::get('/{id}', [PendaftaranController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PendaftaranController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PendaftaranController::class, 'update'])->name('update');
            Route::delete('/{id}', [PendaftaranController::class, 'destroy'])->name('destroy');
            Route::get('/{id}/tambah', [PendaftaranController::class, 'tambah'])->name('tambah');
        });


    // ------------------------------------------------------------------
    // Poli (LIHAT SAJA): dokter, perawat, pmik (+ admin)
    // ------------------------------------------------------------------
    Route::middleware('role:dokter,perawat,pmik')->group(function () {

        Route::get('/poli', [PoliController::class, 'index'])->name('poli');

        Route::get('/poli/show/{slug}', function ($slug) {
            return view('poli.' . $slug);
        })->name('poli.show');

        // Poli Syaraf
        Route::get('/poli/syaraf', [PoliController::class, 'saraf'])
            ->name('poli.syaraf');

        Route::get('/poli/syaraf/detail/{no_rm}', [PoliController::class, 'riwayatSaraf'])
            ->name('poli.syaraf.detail');

        Route::get('/poli/syaraf/kunjungan-baru/{no_rm}', [PoliController::class, 'kunjunganSaraf'])
            ->name('poli.syaraf.kunjungan-baru');

        Route::get('/poli/syaraf/detail/{no_rm}/{id_pendaftaran}', [PoliController::class, 'kunjunganSarafDetail'])
            ->name('poli.syaraf.kunjungan.detail');

        // Clinical Pathway Poli Saraf (lihat)
        Route::get('/poli/saraf/clinical-pathway', [PoliController::class, 'clinicalPathway'])
            ->name('poli.clinical_pathway');

        // Poli Obgyn
        Route::get('/poli/obgyn', [PoliController::class, 'obgyn'])
            ->name('poli.obgyn');

        Route::get('/poli/obgyn/kunjungan/{no_rm}', [PoliController::class, 'riwayatObgyn'])
            ->name('poli.obgyn.kunjungan');

        Route::get('/poli/obgyn/kunjungan/{no_rm}/{id_pendaftaran}', [PoliController::class, 'kunjunganObgyn'])
            ->name('poli.obgyn.kunjungan.detail');

        Route::get('/poli/obgyn/kunjungan-baru/{no_rm}', [PoliController::class, 'kunjunganObgyn'])
            ->name('poli.obgyn.kunjungan-baru');

        // Poli Jantung & Jiwa
        Route::get('/poli/jantung', [PoliController::class, 'jantung'])
            ->name('poli.jantung');

        Route::get('/poli/jiwa', [PoliController::class, 'jiwa'])
            ->name('poli.jiwa');
    });


    // ------------------------------------------------------------------
    // Poli (UBAH STATUS PERIKSA): dokter, perawat (+ admin)
    // PMIK tidak boleh mengubah
    // ------------------------------------------------------------------
    Route::middleware('role:dokter,perawat')->group(function () {

        Route::patch('/poli/syaraf/status-periksa/{id}', [PoliController::class, 'updateStatusPeriksa'])
            ->name('poli.syaraf.status-periksa');

        Route::patch('/poli/obgyn/status-periksa/{id}', [PoliController::class, 'updateStatusPeriksaObgyn'])
            ->name('poli.obgyn.status-periksa');
    });


    // ------------------------------------------------------------------
    // TTV: perawat (+ admin)
    // ------------------------------------------------------------------
    Route::middleware('role:perawat')->group(function () {

        Route::post('/poli/syaraf/ttv', [TtvController::class, 'store'])
            ->name('poli.syaraf.ttv.store');

        Route::post('/poli/obgyn/ttv', [TtvController::class, 'store'])
            ->name('poli.obgyn.ttv.store');
    });


    // ------------------------------------------------------------------
    // Clinical Pathway (simpan): dokter, perawat (+ admin)
    // ------------------------------------------------------------------
    Route::middleware('role:dokter,perawat')->group(function () {

        Route::post('/poli/syaraf/clinical-pathway', [ClinicalPathwayController::class, 'store'])
            ->name('poli.syaraf.clinical-pathway.store');
    });


    // ------------------------------------------------------------------
    // Asesmen: dokter (S, O, A, P), perawat (S, O), pmik (kode ICD saja).
    // Pembatasan field per role WAJIB juga dilakukan di AssesmenController.
    // ------------------------------------------------------------------
    Route::middleware('role:dokter,perawat,pmik')->group(function () {

        Route::post('/poli/syaraf/assesmen', [AssesmenController::class, 'store'])
            ->name('poli.syaraf.assesmen.store');

        Route::post('/poli/obgyn/assesmen', [AssesmenController::class, 'store'])
            ->name('poli.obgyn.assesmen.store');
    });


    // ------------------------------------------------------------------
    // Rujukan & Informed Consent: dokter (+ admin)
    // ------------------------------------------------------------------
    Route::middleware('role:dokter')->group(function () {

        Route::post('/poli/syaraf/rujukan', [PoliController::class, 'kirimRujukan'])
            ->name('poli.syaraf.rujukan');

        Route::post('/poli/obgyn/rujukan', [PoliController::class, 'kirimRujukanObgyn'])
            ->name('poli.obgyn.rujukan');

        // Simpan informed consent (hanya untuk tindakan KB IUD)
        Route::post('/poli/obgyn/informed-consent', [PoliController::class, 'simpanInformedConsent'])
            ->name('poli.obgyn.informed.simpan');
    });


    // ------------------------------------------------------------------
    // Pencarian Kode ICD (ICD-10 & ICD-9-CM): pmik (+ admin)
    // ------------------------------------------------------------------
    Route::middleware('role:pmik')->group(function () {

        Route::get('/cari-kode/{jenis}', [PencarianKodeController::class, 'cari'])
            ->whereIn('jenis', ['penyakit', 'tindakan'])
            ->name('cari.kode');
    });


    // ------------------------------------------------------------------
    // Radiologi: radiologi (+ admin)
    // ------------------------------------------------------------------
    Route::middleware('role:radiologi')->group(function () {

        Route::get('/radiologi', [RadiologiController::class, 'index'])
            ->name('radiologi.index');
    });


    // ------------------------------------------------------------------
    // Audit Trail: admin saja
    // ------------------------------------------------------------------
    Route::middleware('role:admin')->group(function () {

        Route::get('/audit-trail', [AuditTrailController::class, 'index'])
            ->name('audit-trail.index');
    });
});