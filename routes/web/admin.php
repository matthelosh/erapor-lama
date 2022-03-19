<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

Route::get('/', [PageController::class, 'page'])->name('admin.home');
Route::get('/about', [PageController::class, 'page'])->name('admin.about');
Route::group(['prefix' => 'user', 'middleware' => ['admin']], function() {
    	Route::get('/', [ PageController::class, 'page' ])->name('admin.user');	
    	Route::post('/', [ UserController::class, 'index' ])->name('admin.user.index');	
    	Route::post('/store', [ UserController::class, 'store' ])->name('admin.user.store');	
    	Route::post('/import', [ UserController::class, 'import' ])->name('admin.user.import');
        Route::post('/grantsiswa', [ UserController::class, 'grantSiswa' ])->name('admin.user.grantsiswa');
        Route::post('/grantguru', [ UserController::class, 'grantGuru' ])->name('admin.user.grantguru');
        Route::delete('/many', [ UserController::class, 'deleteMany'])->name('admin.user.delete.many');	
        Route::put('/many', [ UserController::class, 'resetMany'])->name('admin.user.reset.many');   
        Route::delete('/{id}', [ UserController::class, 'destroy'])->name('admin.user.delete');   
        Route::put('/{id}', [ UserController::class, 'reset'])->name('admin.user.reset');   
    });

    // Administrasi
    Route::group(['prefix' => 'kartu-uji'], function() {
        Route::get('/', [PageController::class, 'page'])->name('admin.kartuji');
    });


    // Guru
    Route::group(['prefix' => 'guru', 'middleware' => 'admin'], function() {
        Route::get('/', [PageController::class, 'page'])->name('admin.guru');
        Route::post('/', [GuruController::class, 'index'])->name('admin.guru.index');
        Route::post('/store', [GuruController::class, 'store'])->name('admin.guru.store');
        Route::post('/import', [GuruController::class, 'import'])->name('admin.guru.import');
        Route::delete('/many', [GuruController::class, 'deleteMany'])->name('admin.guru.delete.many');
        Route::delete('/{id}', [GuruController::class, 'destroy'])->name('admin.guru.destroy');
        // Route::post('/createaccount', [GuruController::class, 'createAccount'])->name('guru.account.create');
    });

    // Siswa
    Route::group(['prefix' => 'siswa'], function() {
        Route::get('/', [PageController::class, 'page'])->name('admin.siswa');
        Route::post('/', [SiswaController::class, 'index'])->name('admin.siswa.index');
        Route::post('/store', [SiswaController::class, 'store'])->name('admin.siswa.store');
        Route::post('/import', [SiswaController::class, 'import'])->name('admin.siswa.import');
        Route::post('/nonmembers', [SiswaController::class, 'nonmembers'])->name('admin.siswa.nonmembers');
        Route::put('/nonaktifkan', [SiswaController::class, 'inactivate'])->name('admin.siswa.inactivate');
        Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('admin.siswa.delete');
    });

    Route::group(['prefix' => 'ortu'], function() {
       Route::post('/simpan', [OrtuController::class, 'store'])->name('admin.ortu.store');
       Route::post('/impor', [OrtuController::class, 'impor'])->name('admin.ortu.impor');
       Route::post('/{id}', [OrtuController::class, 'show'])->name('admin.ortu.show');
        // response('hi');
    });

    // ROmbel
    Route::group(['prefix' => 'rombel'], function() {
        Route::get('/', [PageController::class, 'page'])->name('admin.rombel');
        Route::post('/', [RombelController::class, 'index'])->name('admin.rombel.index');
        Route::post('/store', [RombelController::class, 'store'])->name('admin.rombel.store');
        Route::post('/import', [RombelController::class, 'import'])->name('radmin.ombel.import');
        Route::post('/masukkan', [RombelController::class, 'masukkan'])->name('admin.rombel.masukkan-siswa');
        Route::post('/keluarkan', [RombelController::class, 'keluarkan'])->name('admin.rombel.keluarkan-siswa');
        Route::post('/duplikat', [RombelController::class, 'duplikat'])->name('admin.rombel.duplikat-siswa');
        Route::post('/{id}', [RombelController::class, 'show'])->name('admin.rombel.show');
        Route::put('/{id}', [RombelController::class, 'update'])->name('admin.rombel.update');
        Route::delete('/{id}', [RombelController::class, 'destroy'])->name('admin.rombel.delete');
    });
    
    // Mapel
    Route::group(['prefix' => 'mapel'], function() {
        Route::get('/', [PageController::class, 'page'])->name('admin.mapel'); 
        Route::post('/', [MapelController::class, 'index'])->name('admin.mapel.index'); 
        Route::post('/store', [MapelController::class, 'store'])->name('admin.mapel.store'); 
        Route::post('/tanpakelas', [MapelController::class, 'noGrade'])->name('admin.mapel.nograde');
        Route::delete('/{id}', [MapelController::class, 'destroy'])->name('admin.mapel.destroy');
    });

    Route::group(['prefix' => 'jadwal'], function() {
        Route::get('/', [PageController::class, 'page'])->name('admin.jadwal');
        Route::post('/', [JadwalController::class, 'index'])->name('admin.jadwal.index');
    });

    // Pekan Efektif
    Route::group(['prefix' => 'pe'], function() {
       Route::get('/', [PageController::class, 'page'])->name('admin.pe');
       Route::post('/', [PekanController::class, 'index'])->name('admin.pe.index');
       Route::post('/store', [PekanController::class, 'store'])->name('admin.pe.store');
    });

    // Kelasß
    Route::group(['prefix' => 'kelas', 'middleware' => ['admin']], function() {
        Route::get('/', [PageController::class, 'page'])->name('admin.kelas');
        Route::post('/', [KelasController::class, 'index'])->name('admin.kelas.index');
        Route::post('/{id}', [KelasController::class, 'show'])->name('admin.kelas.show');
    // Att/Detach Mapel Kelas
        Route::post('/{id}/mapel/attach', [KelasController::class, 'attachMapel'])->name('admin.kelas.mapel.attach');
        Route::post('/{id}/mapel/detach', [KelasController::class, 'detachMapel'])->name('admin.kelas.mapel.detach');
    });

    // Kd
    Route::group(['prefix' => 'kd',  'middleware' => ['admin']],function() {
        Route::post('/', [KdController::class, 'index'])->name('admin.kd.index');
        Route::delete('/', [KdController::class, 'destroy'])->name('admin.kd.destroy');
        Route::post('/impor', [KdController::class, 'impor'])->name('admin.kd.impor');
        Route::post('/store', [KdController::class, 'store'])->name('admin.kd.store');
    });

    // Tema
    Route::group([ 'prefix' => 'tema'], function() {
        Route::post('/', [TemaController::class, 'index'])->name('admin.tema.index');
    });

    Route::group(['prefix' => 'pengaturan', 'middleware' => ['admin']], function() {
        Route::get('/rapor', [PageController::class, 'page'])->name('admin.setting.rapor'); 
        Route::post('/tanggalrapor', [TanggalraporController::class, 'index'])->name('admin.setting.tanggalrapor'); 
        Route::post('/tanggalrapor/simpan', [TanggalraporController::class, 'store'])->name('admin.setting.tanggalrapor.store'); 
        Route::delete('/tanggalrapor/{id}', [TanggalraporController::class, 'destroy'])->name('admin.setting.tanggalrapor.destroy'); 

        // Menu
        Route::group(['prefix' => 'menu', 'middleware' => ['admin']], function() {
            Route::get('/', [PageController::class, 'page'])->name('admin.setting.menu');
            Route::post('/', [MenuController::class, 'index'])->name('admin.menu.index');
            Route::post('/store', [MenuController::class, 'store'])->name('admin.menu.store');
            Route::delete('/{id}', [MenuController::class, 'destroy'])->name('admin.menu.destroy');
        });

        // Periode
        Route::group(['prefix' => 'periode'], function() {
            Route::get('/', [PageController::class, 'page'])->name('admin.setting.periode');
            Route::post('/', [PeriodeController::class, 'index'])->name('admin.periode.index');
            Route::post('/store', [PeriodeController::class, 'store'])->name('admin.periode.store');
            Route::post('/import', [PeriodeController::class, 'import'])->name('admin.periode.import');
            Route::put('/{id}', [PeriodeController::class, 'update'])->name('admin.periode.update');
            Route::delete('/{id}', [PeriodeController::class, 'destroy'])->name('admin.periode.destroy');
        });

        // Akses Log
        Route::group(['prefix' => 'logs', 'middleware' => ['admin']], function() {
            Route::get('/', [PageController::class, 'page'])->name('admin.setting.log');
            Route::post('/', [LogController::class, 'index'])->name('admin.log.index');
        });

        // Sekolah
        Route::group(['prefix' => 'sekolah', 'middleware' => ['admin']], function(){
            Route::get('/', [PageController::class, 'page'])->name('admin.setting.sekolah');
            Route::post('/', [SekolahController::class, 'index'])->name('admin.sekolah.index'); 
            Route::post('/store', [SekolahController::class, 'store'])->name('admin.sekolah.store'); 
        });
    });