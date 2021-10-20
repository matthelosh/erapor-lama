<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', [PageController::class, 'page'])->name('wali.home');
Route::get('/about', [PageController::class, 'page'])->name('wali.about');


Route::group(['prefix' => 'siswa'], function() {
    Route::get('/', [PageController::class, 'page'])->name('wali.siswa');
    Route::post('/', [SiswaController::class, 'index'])->name('wali.siswa.index');
    Route::post('/store', [SiswaController::class, 'store'])->name('wali.siswa.store');
    Route::post('/import', [SiswaController::class, 'import'])->name('wali.siswa.import');
    Route::post('/nonmembers', [SiswaController::class, 'nonmembers'])->name('wali.siswa.nonmembers');
    Route::put('/nonaktifkan', [SiswaController::class, 'inactivate'])->name('wali.siswa.inactivate');
    Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('wali.siswa.delete');
});

Route::group(['prefix' => 'penilaian', 'middleware' => ['guru']], function(){
    Route::get('/', [PageController::class, 'page'])->name('wali.penilaian');
    
});

 // Mapel
 Route::group(['prefix' => 'mapel'], function() {
    Route::get('/', [PageController::class, 'mapel'])->name('wali.mapel'); 
    Route::post('/', [MapelController::class, 'index'])->name('mapel.index'); 
    Route::post('/store', [MapelController::class, 'store'])->name('mapel.store')->middleware('admin'); 
    Route::post('/tanpakelas', [MapelController::class, 'noGrade'])->name('mapel.nograde');
});


// Nilai
Route::group(['prefix' => 'nilai'], function() {
    Route::post('/', [NilaiController::class, 'index'])->name('nilai.index');
    Route::post('/save', [NilaiController::class, 'store'])->name('nilai.store');
    Route::post('/import', [NilaiController::class, 'import'])->name('nilai.import');
    Route::post('/ledger', [NilaiController::class, 'ledger'])->name('nilai.ledger');
    Route::delete('/{kd}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
});

 // ROmbel
 Route::group(['prefix' => 'rombel'], function() {
    Route::get('/', [PageController::class, 'rombel'])->name('dashboard.rombel');
    Route::post('/', [RombelController::class, 'index'])->name('rombel.index');
});

// Rapor
Route::group(['prefix' => 'rapor'], function() {
    Route::get('/', [PageController::class, 'page'])->name('wali.rapor'); 
    Route::post('/cetak', [RaporController::class, 'cetak'])->name('wali.rapor.cetak'); 
    Route::post('/pts', [RaporController::class, 'pts'])->name('wali.rapor.pts'); 
    Route::post('/pas', [RaporController::class, 'pas'])->name('wali.rapor.pas'); 
    Route::post('/savepdf', [RaporController::class, 'savepdf'])->name('wali.rapor.savepdf');
    Route::post('/{rombel}', [RaporController::class, 'index'])->name('walirapor.index');
});
// Data Rapor
Route::group(['prefix' => 'datarapor'], function() {
    Route::delete('/', [DataraporController::class, 'delete'])->name('ekskul.data.delete');
    Route::post('/ekskul', [DataRaporController::class, 'ekskul'])->name('ekskul');
    Route::post('/dataekskul', [DataRaporController::class, 'ekskulSiswa'])->name('ekskul.siswa');
    Route::post('/ekskul/create', [DataRaporController::class, 'ekskulSiswaCreate'])->name('ekskul.siswa.create');
    Route::post('/saran', [DataRaporController::class, 'saran'])->name('saran');
    Route::post('/saran/create', [DataRaporController::class, 'saranCreate'])->name('saran.create');
    Route::post('/fisik', [DataRaporController::class, 'fisik'])->name('fisik');
    Route::post('/fisik/create', [DataRaporController::class, 'fisikCreate'])->name('fisik.create');
    Route::post('/kesehatan', [DataRaporController::class, 'kesehatan'])->name('kesehatan');
    Route::post('/kesehatan/create', [DataRaporController::class, 'kesehatanCreate'])->name('kesehatan.create');
    Route::post('/prestasi', [DataRaporController::class, 'prestasi'])->name('prestasi');
    Route::post('/prestasi/create', [DataRaporController::class, 'prestasiCreate'])->name('prestasi.create');
    Route::post('/absensi', [DataRaporController::class, 'absensi'])->name('absensi');
    Route::post('/absensi/create', [DataRaporController::class, 'absensiCreate'])->name('absensi.create');
    
});

Route::group(['prefix' => 'jadwal'], function() {
    Route::get('/', [PageController::class, 'page'])->name('wali.jadwal');
    Route::post('/', [JadwalController::class, 'index'])->name('jadwal.index');
});

// Pekan Efektif
Route::group(['prefix' => 'pe'], function() {
   Route::get('/', [PageController::class, 'page'])->name('wali.pekan');
   Route::post('/', [PekanController::class, 'index'])->name('pe.index');
   Route::post('/store', [PekanController::class, 'store'])->name('pe.store');
});