<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Pemetaan;
use App\Traits\AdminPage;

class PageController extends Controller
{

    use AdminPage;


    public function page(Request $request)
    {
        switch($request->user()->role)
        {
            case 'admin':
                return $this->adminPage($request);
            break;
            case 'wali':
                return $this->adminPage($request);
            break;
            case 'siswa':

            break;
            default:

            break;
            
        }
    }
    // public function dashboard() {
    //     return Inertia::render('Home', ['page' => 'dashboard', 'page_title' => 'Dashboard']);
    // }

    // public function guru(Request $request)
    // {
    //     return Inertia::render('Guru', ['page' => 'guru', 'page_title' => 'Guru']);
    // }

    // public function about() {
    //     return Inertia::render('About', ['page' => 'about', 'page_title' => 'About']);
    // }

    // public function profile(Request $request)
    // {
    //     return Inertia::render('Profile', ['page' => 'profile', 'page_title' => 'Profile']);
    // }

    // public function user() {
    //     return Inertia::render('User', ['page' => 'user', 'page_title' => 'Pengguna']);
    // }

    // public function siswa(Request $request)
    // {
    //     return Inertia::render('Siswa', ['page' => 'siswa', 'page_title' => 'Siswa']);
    // }

    // public function rombel(Request $request)
    // {
    //     return Inertia::render('Rombel', ['page' => 'rombel', 'page_title' => 'Rombel']);
    // }
    // public function kelas(Request $request)
    // {
    //     return Inertia::render('Kelas', ['page' => 'kelas', 'page_title' => 'Kelas']);
    // }

    // public function jadwal(Request $request)
    // {
    //     return Inertia::render('Jadwal', [ 'page' => 'jadwal', 'page_title' => 'Jadwal']);
    // }

    // public function pekan(Request $request)
    // {
    //     return Inertia::render('Pekan', ['page' => 'pekan', 'page_title' => 'Pekan Efektif']);
    // }

    // public function prosem(Request $request)
    // {
    //     return Inertia::render('Prosem', ['page' => 'prosem', 'page_title' => 'Program Semester']);
    // }
    // public function mapel(Request $request)
    // {
    //     return Inertia::render('Mapel', ['page' => 'mapel', 'page_title' => 'Mapel']);
    // }

    // public function periode(Request $request)
    // {
    //     return Inertia::render('Periode', ['page'=>'periode', 'page_title' => 'Periode']);
    // }

    // public function sekolah(Request $request)
    // {
    //     return Inertia::render('Sekolah', ['page' => 'sekolah', 'page_title' => 'Sekolah']);
    // }

    // public function pemetaan(Request $request)
    // {
    //     return Inertia::render('Pemetaan', ['page' => 'pemetaan', 'page_title' => 'Pemetaan']);
    // }
    // public function pembelajaran(Request $request)
    // {
    //     return Inertia::render('Pembelajaran', ['page' => 'pembelajaran', 'page_title' => 'Pembelajaran']);
    // }
    // public function penilaian(Request $request)
    // {
    //     return Inertia::render('Penilaian', ['page' => 'penilaian', 'page_title' => 'Penilaian']);
    // }

    // public function rapor(Request $request)
    // {
    //     return Inertia::render('Rapor', ['page' => 'rapor', 'page_title' => 'Rapor']);
    // }

    // public function pengaturanRapor(Request $request)
    // {
    //     return inertia::render('Setting/Rapor', ['page' => 'setting-rapor', 'page_title' => 'Pengaturan Rapor']);
    // }

    // public function log(Request $request)
    // {
    //     return Inertia::render('AksesLog', ['page' => 'akses_log', 'page_title' => 'Catatan Akses']);
    // }

    // public function menu(Request $request)
    // {
    //     return Inertia::render('Setting/Menu', ['page' => 'menu', 'page_title' => 'Menu']);
    // }
}
