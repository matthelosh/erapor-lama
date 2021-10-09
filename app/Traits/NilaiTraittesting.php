<?php
namespace App\Traits;

use App\Models\Nilai;
use App\Models\Prosem;
use App\Models\User;
use App\Models\Rombel;
use App\Models\Kelas;
use App\Models\Siswa;

trait NilaiTrait 
{

    public function nilais($request)
    {
        try {
            // dd($request->ppn);
            $ppn = ($request->ppn != 'null') ? ['ppn' => $request->ppn]: '%';
            $operator = ($request->ppn != 'null') ? '=': 'LIKE';
            // dd($ppn,$operator);
            $datas = Rombel::where('kode_rombel', $request->rombel)->with('siswas.nilais', fn($q) => $q->where([
                ['mapel_id','=', $request->mapel],
                ['rombel_id','=', $request->rombel],
                ['jenis','=', $request->jenis],
                ['ppn',$operator,$ppn],
                ['aspek','=',$request->aspek]
            ]))->first();
            // dd($datas);
            $ds=[];
            // $nilais=[];
            $kelas = ($request->aspek == 'k1' || $request->aspek == 'k2') ? 'all' : $request->aspek;

            $kds = Prosem::where([
                ['semester','=', substr($request->session()->get('periode'), 4,1)],
                ['kelas','=', 'all'],
                ['mapel_id','=',$request->mapel],
                ['ppn',$operator,$ppn],
            ])->get();
            // dd($datas->siswas[0]);
            $siswas = $datas->siswas;
            // fore
            $i=0;
            foreach($siswas as $siswa) {
                array_push($ds, ['nisn' => $siswa->nisn, 'nama' => $siswa->nama, 'nilais' => []]);
                $nilais=[];
                $n=0;
                foreach($kds as $kd) {
                    $kd = ($request->aspek == 'k4') ? str_replace('3.', '4.', $kd->kd_id) : $kd->kd_id;
                    array_push($nilais, ['kd_id' => $kd->kd_id, 'nilai' => 0]);
                    foreach($siswa->nilais as $nilai) {
                        if($nilais[$n]['kd_id'] === $nilai->kd_id) {
                            $nilais[$n]['nilai'] = $nilai->nilai;
                        }
                    }
                    $n++;
                }
                $ds[$i]['nilais']=$nilais;
                $i++;
            }
            // dd($ds);
            // dd('3.10' === '3.10');
            return $ds;
           

        } catch (\Exception $e) {
        //    dd($e->getMessage());
            return $e->getMessage();
        }
    }

    public function prosentase($request)
    {
        // dd($request->user());

        $role = $request->user()->role;

        if ($role == 'wali') {
            $rombel = Rombel::where([
                ['guru_id','=', $request->user()->nip],
                ['periode_id', '=', $request->session()->get('periode')]
            ])->with('siswas')->first();
            // dd($rombel);
            $kelas = Kelas::where('kode_kelas', $rombel->kelas_id)->with('mapels', function($q) {
                $q->select('kode_mapel', 'label', 'nama_mapel');
            })->first();
        }
        $datas = [];
        $pais = [];
        $periode = $request->session()->get('periode');
        $semester= substr($periode,4,1);
        // $kelas= $rombel->kelas_id;

        foreach($kelas->mapels as $mapel)
        {
            // array_push($datas, $mapel->kode_mapel);
            $datas[$mapel->kode_mapel]['label'] = $mapel->label;
            $datas[$mapel->kode_mapel]['kode'] = $mapel->kode_mapel;
            $kds = Prosem::where([
                ['semester','=',$semester],
                ['kelas','=',$kelas->kode_kelas],
                ['mapel_id','=',$mapel->kode_mapel]
            ])->get();

            if ($mapel->kode_mapel === 'pabp' ) {
                $kds_pabp = Prosem::where([
                    ['semester','=',$semester],
                    ['kelas','=','all'],
                    ['mapel_id','=', 'pabp'],
                    // ['ppn', '=','ts']
                ])->get();

                $datas['pabp']['nh1']['jml_kd'] = $kds_pabp->count();
                $datas['pabp']['nh1']['kds'] = $kds_pabp;


            }
            if ($mapel->kode_mapel === 'ppkn' ) {
                $kds_ppkn = Prosem::where([
                    ['semester','=',$semester],
                    ['kelas','=','all'],
                    ['mapel_id','=', 'ppkn'],
                    // ['ppn', '=','ts']
                ])->get();

                $datas['ppkn']['nh2']['jml_kd'] = $kds_ppkn->count();
                $datas['ppkn']['nh2']['kds'] = $kds_ppkn;
                // dd($datas);
            }
            
            $kd_ts = Prosem::where([
                ['semester','=',$semester],
                ['kelas','=',$kelas->kode_kelas],
                ['mapel_id','=',$mapel->kode_mapel],
                ['ppn', '=','ts']
            ])->get();
            $kd_as = Prosem::where([
                ['semester','=',$semester],
                ['kelas','=',$kelas->kode_kelas],
                ['mapel_id','=',$mapel->kode_mapel],
                ['ppn', '=','as']
            ])->get();
            $datas[$mapel->kode_mapel]['nh3']['jml_kd'] = $kds->count();
            $datas[$mapel->kode_mapel]['nh3']['kds'] = $kds;
            $datas[$mapel->kode_mapel]['nh4']['jml_kd'] = $kds->count();
            $datas[$mapel->kode_mapel]['nh4']['kds'] = $kds;

            // $datas[$mapel->kode_mapel]['nh3_as']['jml_kd'] = $kd_as->count();
            // $datas[$mapel->kode_mapel]['nh3_as']['kds'] = $kd_as;
            // $datas[$mapel->kode_mapel]['nh4_as']['jml_kd'] = $kd_as->count();
            // $datas[$mapel->kode_mapel]['nh4_as']['kds'] = $kd_as;
            $datas[$mapel->kode_mapel]['nts']['jml_kd'] = $kd_ts->count();
            $datas[$mapel->kode_mapel]['nts']['kds'] = $kd_ts;
            $datas[$mapel->kode_mapel]['nas']['jml_kd'] = $kd_as->count();
            $datas[$mapel->kode_mapel]['nas']['kds'] = $kd_as;
             
            foreach( $kds as $kd)
            {

                if ($mapel->kode_mapel === 'pabp') {
                    $nilai_uh1 = Nilai::where([
                        ['periode_id','=', $request->session()->get('periode')],
                        ['mapel_id', '=', 'pabp'],
                        ['semester','=',$semester],
                        ['rombel_id','=', $rombel->kode_rombel],
                        ['jenis', '=','uh'],
                        ['aspek','=', 'k1']
                    ])->select('kd_id')->groupBy('kd_id')->get();
                    $datas['pabp']['nh1']['dinilai'] = $nilai_uh1->count();
                    $datas['pabp']['nh1']['prosentase'] = $datas['pabp']['nh1']['dinilai'] / $datas['pabp']['nh1']['jml_kd'] * 100;
                }
                if ($mapel->kode_mapel === 'ppkn') {
                    $nilai_uh2 = Nilai::where([
                        ['periode_id','=', $request->session()->get('periode')],
                        ['mapel_id', '=', 'ppkn'],
                        ['semester','=',$semester],
                        ['rombel_id','=', $rombel->kode_rombel],
                        ['jenis', '=','uh'],
                        ['aspek','=', 'k2']
                    ])->select('kd_id')->groupBy('kd_id')->get();
                     $datas['ppkn']['nh2']['dinilai'] = $nilai_uh2->count();
                     $datas['ppkn']['nh2']['prosentase'] = $datas['ppkn']['nh2']['dinilai'] / $datas['ppkn']['nh2']['jml_kd'] * 100;
                }

                $nilai_uh3 = Nilai::where([
                    ['periode_id','=', $request->session()->get('periode')],
                    ['semester','=',$semester],
                    ['mapel_id', '=', $mapel->kode_mapel],
                    ['rombel_id','=', $rombel->kode_rombel],
                    ['jenis', '=','uh'],
                    // ['ppn', '=','ts'],
                    // ['kd_id','=', $kd->kd_id]
                ])->select('kd_id')->groupBy('kd_id')->get();

                // $nilai_uh3_as = Nilai::where([
                //     ['periode_id','=', $request->session()->get('periode')],
                //     ['mapel_id', '=', $mapel->kode_mapel],
                //     ['rombel_id','=', $rombel->kode_rombel],
                //     ['jenis', '=','uh'],
                //     ['ppn', '=','as'],

                // ])->select('kd_id')->groupBy('kd_id')->get();

                $nilai_uh4 = Nilai::where([
                    ['periode_id','=', $request->session()->get('periode')],
                    ['mapel_id', '=', $mapel->kode_mapel],
                    ['semester','=',$semester],
                    ['rombel_id','=', $rombel->kode_rombel],
                    ['jenis', '=','uh'],
                    ['aspek','=','k4'],
                    // ['ppn', '=','ts'],
                    // ['kd_id','=', $kd->kd_id]
                ])->select('kd_id')->groupBy('kd_id')->get();

                // $nilai_uh4_as = Nilai::where([
                //     ['periode_id','=', $request->session()->get('periode')],
                //     ['mapel_id', '=', $mapel->kode_mapel],
                //     ['rombel_id','=', $rombel->kode_rombel],
                //     ['jenis', '=','uh'],
                //     ['aspek','=','k4'],
                //     ['ppn', '=','as'],

                // ])->select('kd_id')->groupBy('kd_id')->get();
                
                $nilai_ts = Nilai::where([
                    ['periode_id','=', $request->session()->get('periode')],
                    ['mapel_id', '=', $mapel->kode_mapel],
                    ['semester','=',$semester],
                    ['rombel_id','=', $rombel->kode_rombel],
                    ['jenis', '=','pts']
                    // ['kd_id','=', $kd->kd_id]
                ])->select('kd_id')->groupBy('kd_id')->get();
                
                $nilai_as = Nilai::where([
                    ['periode_id','=', $request->session()->get('periode')],
                    ['mapel_id', '=', $mapel->kode_mapel],
                    ['semester','=',$semester],
                    ['rombel_id','=', $rombel->kode_rombel],
                    ['jenis', '=','pas']
                    // ['kd_id','=', $kd->kd_id]
                ])->select('kd_id')->groupBy('kd_id')->get();
                // if($nilai != null){

                    
                    $datas[$mapel->kode_mapel]['nh3']['dinilai'] = $nilai_uh3->count();
                    // $datas[$mapel->kode_mapel]['nh3_as']['dinilai'] = $nilai_uh3_as->count();
                    $datas[$mapel->kode_mapel]['nh4']['dinilai'] = $nilai_uh4->count();
                    // $datas[$mapel->kode_mapel]['nh4_as']['dinilai'] = $nilai_uh4_as->count();
                    $datas[$mapel->kode_mapel]['nts']['dinilai'] = $nilai_ts->count();
                    $datas[$mapel->kode_mapel]['nas']['dinilai'] = $nilai_as->count();


                    $datas[$mapel->kode_mapel]['nh3']['prosentase'] = $datas[$mapel->kode_mapel]['nh3']['dinilai'] / $datas[$mapel->kode_mapel]['nh3']['jml_kd'] * 100;
                    // $datas[$mapel->kode_mapel]['nh3_as']['prosentase'] = $datas[$mapel->kode_mapel]['nh3_as']['dinilai'] / $datas[$mapel->kode_mapel]['nh3_as']['jml_kd'] * 100;
                    $datas[$mapel->kode_mapel]['nh4']['prosentase'] = $datas[$mapel->kode_mapel]['nh4']['dinilai'] / $datas[$mapel->kode_mapel]['nh4']['jml_kd'] * 100;
                    // $datas[$mapel->kode_mapel]['nh4_as']['prosentase'] = $datas[$mapel->kode_mapel]['nh4_as']['dinilai'] / $datas[$mapel->kode_mapel]['nh4_as']['jml_kd'] * 100;

                    $datas[$mapel->kode_mapel]['nts']['prosentase'] = $datas[$mapel->kode_mapel]['nts']['dinilai'] / $datas[$mapel->kode_mapel]['nts']['jml_kd'] * 100;
                    $datas[$mapel->kode_mapel]['nas']['prosentase'] = $datas[$mapel->kode_mapel]['nas']['dinilai'] / $datas[$mapel->kode_mapel]['nas']['jml_kd'] * 100;


                // } else {
                //     $datas[$mapel->kode_mapel]['nilais'][$kd->kd_id] = null;
                // }
            }

            
        }
        // $nilais = Nilai::where([
        //     ['periode_id','=', $request->session()->get('periode')],
        //     ['mapel_id', '=', 'pabp'],
        //     ['rombel_id','=', '20212-6'],
            
        // ])->get();
        // dd($kelas->mapels);
        return $datas;
    }
}