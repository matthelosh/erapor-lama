<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

Route::get('/', [PageController::class, 'page'])->name('mapel.home');
// Route::get('/', function(){
//     dd('mapel');
// })->name('mapel.home');

Route::get('/about', [PageController::class, 'page'])->name('mapel.about');


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
    Route::get('/', [PageController::class, 'page'])->name('mapel.penilaian');
    
});

 // Mapel
 Route::group(['prefix' => 'mapel'], function() {
    Route::get('/', [PageController::class, 'mapel'])->name('mapel.mapel'); 
    Route::post('/', [MapelController::class, 'index'])->name('mapel.index'); 
    Route::post('/store', [MapelController::class, 'store'])->name('mapel.store')->middleware('admin'); 
    Route::post('/tanpakelas', [MapelController::class, 'noGrade'])->name('mapel.nograde');
});

// Administrasi
 // Pembelajaran
 Route::group(['prefix' => 'pembelajaran'], function() {
   Route::get('/', [PageController::class, 'page'])->name('mapel.pembelajaran');
   Route::group(['prefix' => 'ahe'], function() {
      Route::get('/', [PageController::class, 'page'])->name('mapel.components.ahe');
   });
 });

 Route::post('/perangkat/ahe', function(Request $request) {
   try {
      $nama = $request->nama;
      $file = $request->file;
      $user = $request->user();
      Storage::putFileAs('public/files/perangkat/'.$user->userid.'/', $file, $nama.'.pdf');
      return response()->json(['success' => true, 'msg' => 'File terupload']);
   } catch (\Exception $e) {
      dd($e);
   }
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

