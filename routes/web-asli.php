<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
});

Route::group(['prefix' => 'auth'], function(){
    Route::post('login', [AuthController::class, 'login']);
});

Route::get('/login', function(){

    if(Auth::check()) {
        return redirect('/dashboard');
    }
    // $status = DB::table('status')->first();
    // if (!$status) {
    //     $msg = 'Jalankan Seeder untuk mengisi data utama.';
    // } else {
    //     $msg=null;
    // }
    return Inertia::render('Login', ['page' => 'login', 'page_title' => 'Login', /*'nouser' => $msg*/]);
})->name('login');

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function() {
    Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/about', [ PageController::class, 'about'])->name('dashboard.about');
    Route::get('/profile', [ PageController::class, 'profile'])->name('dashboard.profile');
    
    Route::group(['prefix' => 'user', 'middleware' => ['admin']], function() {
    	Route::get('/', [ PageController::class, 'user' ])->name('dashboard.user');	
    	Route::post('/', [ UserController::class, 'index' ])->name('user.index');	
    	Route::post('/store', [ UserController::class, 'store' ])->name('user.store');	
    	Route::post('/import', [ UserController::class, 'import' ])->name('user.import');
        Route::post('/grantsiswa', [ UserController::class, 'grantSiswa' ])->name('user.grantsiswa');
        Route::post('/grantguru', [ UserController::class, 'grantGuru' ])->name('user.grantguru');
        Route::delete('/many', [ UserController::class, 'deleteMany'])->name('user.delete.many');	
        Route::put('/many', [ UserController::class, 'resetMany'])->name('user.reset.many');   
        Route::delete('/{id}', [ UserController::class, 'destroy'])->name('user.delete');   
        Route::put('/{id}', [ UserController::class, 'reset'])->name('user.reset');   
    });

    // Guru
    Route::group(['prefix' => 'guru', 'middleware' => 'admin'], function() {
        Route::get('/', [PageController::class, 'guru'])->name('dashboard.guru');
        Route::post('/', [GuruController::class, 'index'])->name('guru.index');
        Route::post('/store', [GuruController::class, 'store'])->name('guru.store');
        Route::post('/import', [GuruController::class, 'import'])->name('guru.import');
        Route::delete('/many', [GuruController::class, 'deleteMany'])->name('guru.delete.many');
        Route::delete('/{id}', [GuruController::class, 'destroy'])->name('guru.destroy');
        // Route::post('/createaccount', [GuruController::class, 'createAccount'])->name('guru.account.create');
    });

    // Siswa
    Route::group(['prefix' => 'siswa'], function() {
        Route::get('/', [PageController::class, 'siswa'])->name('dashboard.siswa');
        Route::post('/', [SiswaController::class, 'index'])->name('siswa.index');
        Route::post('/store', [SiswaController::class, 'store'])->name('siswa.store')->middleware(['admin', 'guru']);
        Route::post('/import', [SiswaController::class, 'import'])->name('siswa.import');
        Route::post('/nonmembers', [SiswaController::class, 'nonmembers'])->name('siswa.nonmembers')->middleware('admin');
        Route::put('/nonaktifkan', [SiswaController::class, 'inactivate'])->name('siswa.inactivate')->middleware('admin');
        Route::delete('/{id}', [SiswaController::class, 'destroy'])->name('siswa.delete')->middleware('admin');
    });

    Route::group(['prefix' => 'ortu'], function() {
       Route::post('/simpan', [OrtuController::class, 'store'])->name('ortu.store');
       Route::post('/impor', [OrtuController::class, 'impor'])->name('ortu.impor');
       Route::post('/{id}', [OrtuController::class, 'show'])->name('ortu.show');
        // response('hi');
    });

    // ROmbel
    Route::group(['prefix' => 'rombel'], function() {
        Route::get('/', [PageController::class, 'rombel'])->name('dashboard.rombel');
        Route::post('/', [RombelController::class, 'index'])->name('rombel.index');
        Route::post('/store', [RombelController::class, 'store'])->name('rombel.store')->middleware('admin');
        Route::post('/import', [RombelController::class, 'import'])->name('rombel.import')->middleware('admin');
        Route::post('/masukkan', [RombelController::class, 'masukkan'])->name('rombel.masukkan-siswa')->middleware('admin');
        Route::post('/keluarkan', [RombelController::class, 'keluarkan'])->name('rombel.keluarkan-siswa')->middleware('admin');
        Route::post('/duplikat', [RombelController::class, 'duplikat'])->name('rombel.duplikat-siswa')->middleware('admin');
        Route::post('/{id}', [RombelController::class, 'show'])->name('rombel.show');
        Route::put('/{id}', [RombelController::class, 'update'])->name('rombel.update')->middleware('admin');
        Route::delete('/{id}', [RombelController::class, 'destroy'])->name('rombel.delete')->middleware('admin');
    });
    
    // Mapel
    Route::group(['prefix' => 'mapel'], function() {
        Route::get('/', [PageController::class, 'mapel'])->name('dashboard.mapel'); 
        Route::post('/', [MapelController::class, 'index'])->name('mapel.index'); 
        Route::post('/store', [MapelController::class, 'store'])->name('mapel.store')->middleware('admin'); 
        Route::post('/tanpakelas', [MapelController::class, 'noGrade'])->name('mapel.nograde');
    });

    // Periode
    Route::group(['prefix' => 'periode'], function() {
        Route::get('/', [PageController::class, 'periode'])->name('dashboard.periode')->middleware('admin');
        Route::post('/', [PeriodeController::class, 'index'])->name('periode.index');
        Route::post('/store', [PeriodeController::class, 'store'])->name('periode.store')->middleware('admin');
        Route::post('/import', [PeriodeController::class, 'import'])->name('periode.import')->middleware('admin');
        Route::put('/{id}', [PeriodeController::class, 'update'])->name('periode.update')->middleware('admin');
        Route::delete('/{id}', [PeriodeController::class, 'destroy'])->name('periode.destroy')->middleware('admin');
    });

    Route::group(['prefix' => 'jadwal'], function() {
        Route::get('/', [PageController::class, 'jadwal'])->name('dashboard.jadwal');
        Route::post('/', [JadwalController::class, 'index'])->name('jadwal.index');
    });

    // Pekan Efektif
    Route::group(['prefix' => 'pe'], function() {
       Route::get('/', [PageController::class, 'pekan'])->name('dashboard.pe');
       Route::post('/', [PekanController::class, 'index'])->name('pe.index');
       Route::post('/store', [PekanController::class, 'store'])->name('pe.store');
    });

    // Sekolah
    Route::group(['prefix' => 'sekolah', 'middleware' => ['admin']], function(){
        Route::get('/', [PageController::class, 'sekolah'])->name('dashboard.sekolah');
        Route::post('/', [SekolahController::class, 'index'])->name('sekolah.index'); 
        Route::post('/store', [SekolahController::class, 'store'])->name('sekolah.store'); 
    });

    // Kelasß
    Route::group(['prefix' => 'kelas', 'middleware' => ['admin']], function() {
        Route::get('/', [PageController::class, 'kelas'])->name('dashboard.kelas');
        Route::post('/', [KelasController::class, 'index'])->name('kelas.index');

        Route::post('/{id}', [KelasController::class, 'show'])->name('kelas.show');
        Route::post('/{id}/attach', [KelasController::class, 'attach'])->name('kelas.attach');
    });

    // Kd
    Route::group(['prefix' => 'kd',  'middleware' => ['admin']],function() {
        Route::post('/', [KdController::class, 'index'])->name('kd.index');
        Route::delete('/', [KdController::class, 'destroy'])->name('kd.destroy');
        Route::post('/impor', [KdController::class, 'impor'])->name('kd.impor');
        Route::post('/store', [KdController::class, 'store'])->name('kd.store');
    });

    // Tema
    Route::group([ 'prefix' => 'tema'], function() {
        Route::post('/', [TemaController::class, 'index'])->name('tema.index');
    });
    Route::group(['prefix' => 'prosem'], function() {
        Route::get('/', [PageController::class, 'prosem'])->name('dashboard.prosem');
        Route::post('/', [ProsemController::class, 'index'])->name('prosem.index');
    });

    Route::group(['prefix' => 'promes'], function() {
       Route::post('/', [PromesController::class, 'index'])->name('promes.index'); 
       Route::post('/store', [PromesController::class, 'store'])->name('promes.store'); 
    });
    // Guru dan Wali
    Route::group(['prefix' => 'pembelajaran', 'middleware' => ['guru']], function(){
        Route::get('/', [PageController::class, 'pembelajaran'])->name('dashboard.pembelajaran');
        Route::post('/', [PembelajaranController::class, 'index'])->name('pembelajaran.index');
        Route::post('/store', [PembelajaranController::class, 'store'])->name('pembelajaran.store');

    });
    Route::group(['prefix' => 'penilaian', 'middleware' => ['guru']], function(){
        Route::get('/', [PageController::class, 'penilaian'])->name('dashboard.penilaian');
        
    });

    Route::group(['prefix' => 'pemetaan'], function() {
        Route::get('/', [PageController::class, 'pemetaan'])->name('dashbboard.pemetaan');
        Route::post('/', [PemetaanController::class, 'index'])->name('pemetaan.index'); 
    });

    // Jurnal KBM
    Route::group(['prefix' => 'jurnal'], function() {
        Route::post('/', [JurnalController::class, 'index'])->name('jurnal.index');
        Route::post('/create', [JurnalController::class, 'store'])->name('jurnal.store');
        Route::post('/show', [JurnalController::class, 'show'])->name('jurnal.show');
    });

    // Nilai
    Route::group(['prefix' => 'nilai'], function() {
        Route::post('/', [NilaiController::class, 'index'])->name('nilai.index');
        Route::post('/save', [NilaiController::class, 'store'])->name('nilai.store');
        Route::post('/import', [NilaiController::class, 'import'])->name('nilai.import');
        Route::post('/ledger', [NilaiController::class, 'ledger'])->name('nilai.ledger');
        Route::delete('/{kd}', [NilaiController::class, 'destroy'])->name('nilai.destroy');
    });

    // Rapor
    Route::group(['prefix' => 'rapor'], function() {
        Route::get('/', [PageController::class, 'rapor'])->name('dashboard.rapor'); 
        Route::post('/cetak', [RaporController::class, 'cetak'])->name('rapor.cetak'); 
        Route::post('/pts', [RaporController::class, 'pts'])->name('rapor.pts'); 
        Route::post('/pas', [RaporController::class, 'pas'])->name('rapor.pas'); 
        Route::post('/{rombel}', [RaporController::class, 'index'])->name('rapor.index');
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

    Route::group(['prefix' => 'pengaturan', 'middleware' => ['admin']], function() {
        Route::get('/rapor', [PageController::class, 'pengaturanRapor'])->name('setting.rapor'); 
        Route::post('/tanggalrapor', [TanggalraporController::class, 'index'])->name('setting.tanggalrapor'); 
        Route::post('/tanggalrapor/simpan', [TanggalraporController::class, 'store'])->name('setting.tanggalrapor.store'); 
        Route::delete('/tanggalrapor/{id}', [TanggalraporController::class, 'destroy'])->name('setting.tanggalrapor.destroy'); 

        // Menu
        Route::group(['prefix' => 'menu', 'middleware' => ['admin']], function() {
            Route::get('/', [PageController::class, 'menu'])->name('dashboard.menu');
            Route::post('/', [MenuController::class, 'index'])->name('menu.index');
            Route::post('/store', [MenuController::class, 'store'])->name('menu.store');
            Route::delete('/{id}', [MenuController::class, 'destroy'])->name('menu.destroy');
        });
    });

    Route::group(['prefix' => 'logs', 'middleware' => ['admin']], function() {
        Route::get('/', [PageController::class, 'log'])->name('log');
        Route::post('/', [LogController::class, 'index'])->name('log.index');
    });
});

Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
