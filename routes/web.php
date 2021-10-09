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
        // dd(Auth::user()->role);
        $role = Auth::user()->role;
        switch($role) {
            case "admin":
                $rute = $role;
                break;
            case "wali":
                $rute = $role;
                break;
            case "siswa":
                $rute = $role;
                break;
            default: 
                $rute = 'mapel';
                break;
        }
        return redirect('/'.$rute);
    }
    return Inertia::render('Login', ['page' => 'login', 'page_title' => 'Login', /*'nouser' => $msg*/]);
})->name('login');


// Route::group(['prefix' => $rute])
Route::group(['prefix' => '/', 'middleware' => 'auth'], function() {
    Route::group(['prefix' => 'admin'], __DIR__.'/web/admin.php');
    Route::group(['prefix' => 'wali'], __DIR__.'/web/wali.php');
    Route::group(['prefix' => 'mapel'], __DIR__.'/web/mapel.php');
    Route::group(['prefix' => 'siswa'], __DIR__.'/web/siswa.php');
});

// Route::group

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth', 'checkRole']], function() {
    // dd(\Request::path());
    Route::get('/', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/about', [ PageController::class, 'about'])->name('dashboard.about');
    Route::get('/profile', [ PageController::class, 'profile'])->name('dashboard.profile');
    // $rute = (Auth::user()->role != 'admin' || Auth::user()->role != 'wali' ||  Auth::user()->role != 'siswa' ) ? 'mapel' : Auth::user()->role; 
    // Route Admin
    // Route::group(['prefix' => $rute], __DIR__.'/web/'.$rute.'.php');

    // // Route Wali
    // Route::group(['prefix' => 'wali'],  __DIR__.'/web/wali.php');

    // // Route Guru Mapel
    // Route::group(['prefix' => 'mapel'],  __DIR__.'/web/mapel.php');

    // // Route Siswa/Wali
    // Route::group(['prefix' => 'siswa'],  __DIR__.'/web/siswa.php');
});

Route::get('logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
