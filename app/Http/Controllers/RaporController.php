<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\Nilai;
use App\Models\Prosem;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\Pemetaan;
use App\Models\Kd;
use App\Models\Tema;
use App\Models\Sekolah;
use App\Models\EkskulSiswa;
use App\Models\Saran;
use App\Models\Fisik;
use App\Models\Kesehatan;
use App\Models\Prestasi;
use App\Models\Absensi;
use App\Models\TanggalRapor;
use App\Models\Arsip;
use Illuminate\Support\Facades\DB;
use App\Traits\RaporTrait;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Google\Client;
use App\Services\GdriveServices;


class RaporController extends Controller
{

    use RaporTrait;

    public function index(Request $request, $rombel)
    {
        try {
            $rombel = Rombel::where('kode_rombel', $rombel)->with('siswas.ortu')->first();
            return response()->json(['success' => true,'msg' => 'Data Siswa', 'siswas' => $rombel->siswas], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }


    public function pts(Request $request)
    {
        // $rombel;
       
        try {
            $sekolah = Sekolah::find(1)->with('ks')->first();
            $siswa = Siswa::where('nisn', $request->siswa_id)->first();
           
            $rombel = Rombel::where('kode_rombel', $request->rombel)->with('walis')->first();
            // dd($rombel);
            $kelas = Kelas::where('kode_kelas', substr($request->rombel, 6,1))->with('mapels')->first();
            $pts = $this->getPTS($request, $kelas, $siswa);
            $tanggal_rapor = TanggalRapor::where('periode_id', $request->session()->get('periode'))->where('jenis_rapor','pts')->first();
            $saran = Saran::where([
                 ['periode_id','=', $request->session()->get('periode')],
                ['rombel_id', '=', $rombel->kode_rombel],
                ['siswa_id','=', $siswa->nisn],
                ['jenis_rapor','=','pts']
            ])->first();
            return response()->json([
                'success' => true,
                'rapor' => [
                    'sekolah' => $sekolah,
                    'siswa' => $siswa,
                    'rombel' => $rombel,
                    'pts' => $pts,
                    'saran' => $saran ,
                    'tanggal_rapor' => $tanggal_rapor ? $tanggal_rapor->tanggal : date('d M Y')
                ]
            ],200);
        } catch (\Exception $e) {
            // return response()->json(['success' => false, 'msg' => $e], 500);
            dd($e);
        }
    }
    
    public function pas(Request $request)
    {
        try {
            $sekolah = Sekolah::find(1)->with('ks')->first();
            $siswa = Siswa::where('nisn', $request->siswa_id)->first();
            $rombel = Rombel::where('kode_rombel', $request->rombel)->with('walis')->first();
            $kelas = Kelas::where('kode_kelas', substr($request->rombel, 6,1))->with('mapels')->first();
            $pas = $this->getPAS($request, $kelas,$siswa);
            $query = [
                 ['periode_id','=', $request->session()->get('periode')],
                ['rombel_id', '=', $rombel->kode_rombel],
                ['siswa_id','=', $siswa->nisn]
            ];

            // FIsik
            $getfisik = Fisik::where([
                ['periode_id','LIKE',substr($request->session()->get('periode'), 0,4).'%'],
                ['siswa_id','=', $siswa->nisn]
            ])->orderBy('periode_id')->get();
            // $i=1;
            $tb = [];
            $bb = [];
            foreach($getfisik as $fi)
            {
                $sem = substr($fi->periode_id,-1);
                $tb['sem'.$sem] = $fi->tb .' cm';
                $bb['sem'.$sem] = $fi->bb .' kg';
                // $i++;
            }
            // $fisik = $tb;
            // [
            //     ['id' => 1, 'label' => 'Tinggi Badan', 'sem1' => $tb['sem1'], 'sem2' => $tb['sem2']],
            //     ['id' => 2, 'label' => 'Berat Badan', 'sem1' => $bb['sem1'], 'sem2' => $bb['sem2']],
            // ];
            // Kesehatan
            $kesehatan = Kesehatan::where($query)->first();
            // Prestasi
            $prestasi = Prestasi::where($query)->first();
            // Absensi
            $absensi = Absensi::where($query)->first();
            $tanggal_rapor = TanggalRapor::where('periode_id', $request->session()->get('periode'))->where('jenis_rapor','pas')->first();
            return response()->json([
                'success' => true,
                'rapor' => [
                    'sekolah' => $sekolah,
                    'siswa' => $siswa,
                    'rombel' => $rombel,
                    'pas' => $pas,
                    'ekskul' => EkskulSiswa::where($query)->with('ekskuls')->get(),
                    'saran' => Saran::where($query)->where('jenis_rapor','pas')->first(),
                    'fisik' => 
                    [
                        ['id' => 1, 'label' => 'Tinggi Badan', 'sem1' => $tb['sem1']??'-', 'sem2' => $tb['sem2']??'-'],
                        ['id' => 2, 'label' => 'Berat Badan', 'sem1' => $bb['sem1']??'-', 'sem2' => $bb['sem2']??'-'],
                    ],
                    'kesehatan' => $kesehatan,
                    'prestasi' => $prestasi,
                    'absensi' => $absensi,
                    'tanggal_rapor' => $tanggal_rapor ? $tanggal_rapor->tanggal : date('d M Y')
                ]
            ],200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function savepdf(Request $request, GdriveServices $gdriveServices)
    {
        try {
            // dd($request->all());
            $filename = $request->file('file')->getClientOriginalName();
            $pecah = explode('-', $filename);
            $siswa = explode('.', $pecah[3]);
            Arsip::updateOrCreate([
                'kode_rapor' => $pecah[0].'-'.$pecah[1].$pecah[2].$siswa[0], 
                'filename' => $pecah[0].'-'.$pecah[3],
                'jenis' => $pecah[0]

            ],
            [
                'periode_id' =>$pecah[1], 
                'semester' => substr($pecah[1], -1),
                'rombel_id' => $pecah[1].'-'.$pecah[2], 
                'siswa_id' => $siswa[0], 
            ]
            );
            $path = $pecah[1].'/'.$pecah[1].'-'.$pecah[2].'/';
            Storage::putFileAs('public/arsip/'.$pecah[1].'/'.$pecah[1].'-'.$pecah[2].'/', $request->file('file'), $pecah[0].'-'.$pecah[3]);
            $content = $request->file('file')->getContent();
            // Upload PDF Ke Google Drive
            $arsip = $gdriveServices->upload($content,$filename);
            return response()->json(['success'=>true, 'msg' => $arsip],200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function toGdrive($f)
    {
        
    }
    
}

