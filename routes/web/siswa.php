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
// Fisik
Route::group(['prefix' => 'fisik'], function() {
    Route::get('/', [PageController::class, 'page'])->name('siswa.fisik');
    Route::post('/', [FisikController::class, 'index'])->name('fisik');
});
// Orang Tua
Route::group(['prefix' => 'ortu'], function() {
    Route::get('/', [PageController::class, 'page'])->name('siswa.ortu');
});

// Akademik
Route::group(['prefix' => 'akademik'], function() {
    Route::get('/', [PageController::class, 'page'])->name('siswa.akademik');
});

// Rapor
Route::group(['prefix' => 'rapor'], function() {
    Route::get('/', [PageController::class, 'page'])->name('siswa.rapor'); 
    Route::post('/arsip', [ArsipController::class, 'index'])->name('siswa.rapor.index');
});