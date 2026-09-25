<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('poli.syaraf');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::post('/login', function () {
    return redirect()->route('poli.syaraf');
})->name('login.process');

Route::post('/logout', function () {
    return redirect()->route('login');
})->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/poli/syaraf', function () {
    return view('poli.syaraf');
})->name('poli.syaraf');

Route::get('/poli/obgyn', function () {
    return view('poli.obgyn');
})->name('poli.obgyn');

Route::get('/poli/jantung', function () {
    return view('poli.jantung');
})->name('poli.jantung');

Route::get('/poli/jiwa', function () {
    return view('poli.jiwa');
})->name('poli.jiwa');

Route::get('/radiologi', function () {
    return view('radiologi');
})->name('radiologi');