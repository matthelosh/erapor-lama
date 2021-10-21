<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', [PageController::class, 'page'])->name('siswa.home');

// Periode
Route::group(['prefix' => 'periode'], function() {
    Route::post('/', [PeriodeController::class, 'index'])->name('periode.index');
});

// Profil
Route::group(['prefix' => 'profil'], function() {
    Route::get('/', [PageController::class, 'page'])->name('siswa.profil');
    Route::post('/update', [SiswaController::class, 'update_foto'])->name('siswa.update-foto');
});
// Akademik
Route::group(['prefix' => 'akademik'], function() {
    Route::get('/', [PageController::class, 'page'])->name('siswa.akademik');
});
// Rapor
Route::group(['prefix' => 'rapor'], function() {
    Route::get('/', [PageController::class, 'page'])->name('wali.rapor'); 
    Route::post('/cetak', [RaporController::class, 'cetak'])->name('wali.rapor.cetak'); 
    Route::post('/pts', [RaporController::class, 'pts'])->name('wali.rapor.pts'); 
    Route::post('/pas', [RaporController::class, 'pas'])->name('wali.rapor.pas'); 
    Route::post('/{rombel}', [RaporController::class, 'index'])->name('walirapor.index');
});