<?php
namespace App\Traits;


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
use Illuminate\Support\Facades\DB;

trait RaporTrait
{
	public function getPTS($request, $kelas, $siswa)
	{
        // dd($siswa);
		$pts = [];

        foreach($kelas->mapels as $mapel)
        {
            if ($mapel->kategori == 'wajib') {
                $pts['wajib'][$mapel->kode_mapel] = $mapel;
            } else{
                $pts['mulok'][$mapel->kode_mapel] = $mapel;
            }
        }
        foreach($pts['wajib'] as $mapel)
        {
            $nilai_pts_wajib = Nilai::where([
                ['siswa_id','=',$request->siswa_id],
                ['periode_id','=',$request->periode],
                ['rombel_id','=', $request->rombel],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['ppn','=', 'ts'],
                ['aspek','=','k3']
            ]);
            $rerata = $nilai_pts_wajib->avg('nilai');
            $nilai = $nilai_pts_wajib->groupBy('mapel_id', 'kd_id')->get(['mapel_id','kd_id',DB::raw('AVG(nilai) as avg')]);
            $nmax = 0;
            $kmax = '';
            $nmin = 0;
            $kmin = '';
            foreach($nilai as $n){
                // array_push($ns,['kd_id' => $n->kd_id, 'avg' => $n->avg]);
                if ($n->avg > $nmax) {
                    $nmax = $n->avg;
                    $kmax = $n->kd_id;
                } 

                if($nmin == 0) {
                    $nmin = $n->avg;
                    $kmin = $n->kd_id;
                } else if($n->avg < $nmin) {
                    $nmin = $n->avg;
                    $kmin = $n->kd_id;
                }

            }

            $kd_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$kmax],
                ['mapel_id','=',$mapel->kode_mapel],
                ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $kd_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$kmin],
                ['mapel_id','=',$mapel->kode_mapel],
                ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            

            $deskripsi = 'Ananda ' . $siswa->nama .' '.$this->kata($nmax, $mapel->pivot->kkm).' '.($kd_max->teks ?? '').', '.$this->kata($nmin, $mapel->pivot->kkm).' '.($kd_min->teks?? 'Cek KD'.$kmin);

            $ptswajib = [
                'nilai' => $rerata,
                'predikat' => $this->huruf($rerata, $mapel->pivot->kkm),
                'deskripsi' => $deskripsi,
                'kkm' => $mapel->pivot->kkm
            ];

            $pts['wajib'][$mapel->kode_mapel]['nilai'] = $ptswajib;
        }
        foreach($pts['mulok'] as $mapel)
        {
            $nilai_pts_mulok = Nilai::where([
                ['siswa_id','=',$request->siswa_id],
                ['periode_id','=',$request->periode],
                ['rombel_id','=', $request->rombel],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['ppn','=', 'ts'],
                ['aspek','=','k3']
            ]);
            $rerata = $nilai_pts_mulok->avg('nilai');
            $nilai = $nilai_pts_mulok->groupBy('mapel_id', 'kd_id')->get(['mapel_id','kd_id',DB::raw('AVG(nilai) as avg')]);
            $nmax = 0;
            $kmax = '';
            $nmin = 0;
            $kmin = '';
            foreach($nilai as $n){
                // array_push($ns,['kd_id' => $n->kd_id, 'avg' => $n->avg]);
                if ($n->avg > $nmax) {
                    $nmax = $n->avg;
                    $kmax = $n->kd_id;
                } else {
                    $nmin = $n->avg;
                    $kmin = $n->kd_id;
                }

            }

            $kd_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$kmax],
                ['mapel_id','=',$mapel->kode_mapel],
                ['kurikulum_id','=', $request->session()->get('kurikulum')],
            ])->first();

            $kd_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$kmin],
                ['mapel_id','=',$mapel->kode_mapel],
                ['kurikulum_id','=', $request->session()->get('kurikulum')],
            ])->first();

            

            $deskripsi = 'Ananda ' . $siswa->nama .' '.$this->kata($nmax, $mapel->pivot->kkm).' '.($kd_max->teks ?? '').', '.$this->kata($nmin, $mapel->pivot->kkm).' '.($kd_min->teks?? 'Cek KD '. $kmin);

            $ptsmulok = [
                'nilai' => $rerata,
                'predikat' => $this->huruf($rerata, $mapel->pivot->kkm),
                'deskripsi' => $deskripsi,
                'kkm' => $mapel->pivot->kkm
            ];

            $pts['mulok'][$mapel->kode_mapel]['nilai'] = $ptsmulok;
        }
        return $pts;
	}

	   public function getPAS($request, $kelas, $siswa)
    {
        try {
        // dd($siswa->nisn);
        // $nilais = Nilai::find(7857);

        // $nilais = Nilai::where([
        //     ['siswa_id','=',$siswa->nisn],
        //         ['periode_id','=',$request->periode],
        //         ['rombel_id','=', $request->rombel],
        //         ['mapel_id','=', 'bid'],
        //         ['jenis','!=', 'pts'],
        //         ['aspek','=','k3']
        // ])->get();
        // dd($nilais);
        $pas = [];
        foreach ($kelas->mapels as $mapel)
        {
            if($mapel->kategori == 'wajib') {
                $pas['wajib'][$mapel->kode_mapel] = $mapel;
            } else {
                $pas['mulok'][$mapel->kode_mapel] = $mapel;
            }
        }

        // Mapel Wajib
        foreach ($pas['wajib'] as $mapel)
        {
            // K3
            // $nilai3 = Nilai::where([
            //     ['siswa_id','=',$siswa->nisn],
            //     ['periode_id','=',$request->periode],
            //     ['rombel_id','=', $request->rombel],
            //     ['mapel_id','=',$mapel->kode_mapel],
            //     ['jenis','!=', 'pts'],
            //     ['aspek','=','k3']
            // ])->groupBy('kd_id')->get(['kd_id', DB::raw('AVG(nilai) AS nilai')]);
            
                $nilai3 = DB::table('nilais')
                            ->where([
                                    ['siswa_id','=',$siswa->nisn],
                                    ['periode_id','=',$request->periode],
                                    ['rombel_id','=', $request->rombel],
                                    ['mapel_id','=',$mapel->kode_mapel],
                                    ['jenis','<>', 'pts'],
                                    ['aspek','=','k3']
                            ])->select(DB::raw('AVG(nilai) as nilai, kd_id'))
                            ->groupBy('nilais.kd_id')
                            ->get();
            // dd($nilai3);
            $nilais3 = [];
            // dd($nilais3);
            $nr3=[];
            foreach($nilai3 as $nilai) {
                $nilais3[$nilai->kd_id] = $nilai->nilai;
                array_push($nr3, $nilai->nilai);
            }

            // dd(array_sum($nr3)/count($nr3));
            $n3max=count($nilais3) > 0 ? max($nilais3) : 0;
            $k3max=array_search($n3max, $nilais3);
            $n3min=count($nilais3) > 0 ? min($nilais3) : 0;
            $k3min=array_search($n3min, $nilais3);

            // $n3max=0;
            // $k3max='';
            // $n3min=0;
            // $k3min='';

            // foreach($nilais3 as $k=>$n)
            // {
            //     if($n > $n3max) {
            //         $n3max = $n;
            //         $k3max = $k;
            //     } else {
            //         $n3min = $n;
            //         $k3min = $k;
            //     }
            // }

            $kd3_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k3max],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $kd3_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k3min],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $deskripsi3 = $this->kata($n3max, $mapel->pivot->kkm).' '.($kd3_max->teks ?? '').'. '.$this->kata($n3min, $mapel->pivot->kkm).' '.($kd3_min->teks?? 'Cek KD '.$k3min);
            $rerata = (count($nilais3) > 0 ) ? array_sum(array_values($nilais3))/count($nilais3) : 0;
            // $rerata = $nilais3;

            $n3 = [
                'nilai' => $rerata,
                'predikat' => $this->huruf($rerata, $mapel->pivot->kkm),
                'deskripsi' => $deskripsi3,
                'kkm' => $mapel->pivot->kkm
            ];

            // KD 4
            $nilai_h4 = Nilai::where([
                ['siswa_id','=',$request->siswa_id],
                ['periode_id','=',$request->periode],
                ['rombel_id','=', $request->rombel],
                ['mapel_id','=',$mapel->kode_mapel],
                ['jenis','=', 'uh'],
                ['aspek','=','k4']
            ])->groupBy('kd_id')->get(['kd_id', DB::raw('AVG(nilai) AS nilai')]);
            // dd($nilai_h4);
            // $nilais = '';
            $nilais4 = [];
            if(count($nilai_h4) > 0) {
                foreach($nilai_h4 as $nilai)
                {
                    if($nilai->nilai > 0) {
                        $nilais4[$nilai->kd_id] = $nilai->nilai;
                    }
                    
                }
            }
            // dd(count($nilai_h4));
            
            $n4max= count($nilais4) > 0 ? max($nilais4) : 0;
            $k4max=array_search($n4max, $nilais4);
            $n4min= count($nilais4) > 0 ? min($nilais4) : 0;
            $k4min=array_search($n4min, $nilais4);

            // foreach($nilais4 as $k=>$n)
            // {
            //     if($n > $n4max) {
            //         $n4max = $n;
            //         $k4max = $k;
            //     } else {
            //         $n4min = $n;
            //         $k4min = $k;
            //     }
            // }

            $kd4_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k4max],
                ['mapel_id','=',$mapel->kode_mapel],
                ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $kd4_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k4min],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $deskripsi4 = $this->kata($n4max, $mapel->pivot->kkm).' '.($kd4_max->teks ?? '').'. '.$this->kata($n4min, $mapel->pivot->kkm).' '.($kd4_min->teks?? 'Cek KD '.$k4min.' '.$n4min.' '.$mapel->kode_mapel.'-'.$kd4_min.'-'.$nilai_h4->count());
            $rerata4 = (count($nilais4) > 0) ? array_sum(array_values($nilais4))/count($nilais4) : 0;

            $n4 = [
                'nilai' => $rerata4,
                'predikat' => $this->huruf($rerata4, $mapel->pivot->kkm),
                'deskripsi' => $deskripsi4,
                'kkm' => $mapel->pivot->kkm
            ];
            // dd($nilais4);

            $pas['rerata'][] = $n3['nilai'];
            $pas['wajib'][$mapel->kode_mapel]['nilai'] = ['k3' => $n3, 'k4' => $n4];
        }

        // Mulok
        foreach ($pas['mulok'] as $mapel)
        {
            // K3
            $nilai3 = Nilai::where([
                ['siswa_id','=',$request->siswa_id],
                ['periode_id','=',$request->periode],
                ['rombel_id','=', $request->rombel],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['jenis','=', 'uh'],
                ['aspek','=','k3']
            ])->groupBy('kd_id')->get(['kd_id', DB::raw('AVG(nilai) AS nilai')]);


            $nilais3 = [];
            foreach($nilai3 as $nilai){
                $nilais3[$nilai->kd_id] = $nilai->nilai;
            }
            $n3max=count($nilais3) > 0 ? max($nilais3) : 0;
            $k3max=array_search($n3max, $nilais3);
            $n3min=count($nilais3) > 0 ? min($nilais3) : 0;
            $k3min=array_search($n3min, $nilais3);

            // $n3max=0;
            // $k3max='';
            // $n3min=0;
            // $k3min='';

            // foreach($nilais3 as $k=>$n)
            // {
            //     if($n > $n3max) {
            //         $n3max = $n;
            //         $k3max = $k;
            //     } else {
            //         $n3min = $n;
            //         $k3min = $k;
            //     }
            // }

            $kd3_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k3max],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $kd3_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k3min],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $deskripsi3 = $this->kata($n3max, $mapel->pivot->kkm).' '.($kd3_max->teks ?? '').'. '.$this->kata($n3min, $mapel->pivot->kkm).' '.($kd3_min->teks?? 'Cek KD '.$k3min);
            // $rerata = (count($nilais3) > 0 ) ? array_sum(array_values($nilais3))/count($nilais3) : 0;
            $rerata3 = count($nilais3) > 0 ? array_sum(array_values($nilais3)) / count($nilais3) : 0;

            $n3 = [
                'nilai' => $rerata3,
                'predikat' => $this->huruf($rerata, $mapel->pivot->kkm),
                // 'predikat' => $this->huruf($rerata, $mapel->pivot->kkm),
                'deskripsi' => $deskripsi3,
                'kkm' => $mapel->pivot->kkm
            ];

            // KD 4
            $nilai_h4 = Nilai::where([
                ['siswa_id','=',$request->siswa_id],
                ['periode_id','=',$request->periode],
                ['rombel_id','=', $request->rombel],
                ['mapel_id','=',$mapel->kode_mapel],
                ['jenis','=', 'uh'],
                ['aspek','=','k4']
            ])->groupBy('kd_id')->get(['kd_id', DB::raw('AVG(nilai) AS nilai')]);

            $nilais4 = ['4.1' => 0];
            if(count($nilai_h4) > 0) {
                foreach($nilai_h4 as $nilai)
                {
                    $nilais4[$nilai->kd_id] = $nilai->nilai??0;
                }
            }
            $n4max=max($nilais4);
            $k4max=array_search($n4max, $nilais4);
            $n4min=min($nilais4);
            $k4min=array_search($n4min, $nilais4);

            // foreach($nilais4 as $k=>$n)
            // {
            //     if($n > $n4max) {
            //         $n4max = $n;
            //         $k4max = $k;
            //     } else {
            //         $n4min = $n;
            //         $k4min = $k;
            //     }
            // }

            $kd4_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k4max],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $kd4_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k4min],
                ['mapel_id','=',$mapel->kode_mapel],
                // ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $deskripsi4 = $this->kata($n4max, $mapel->pivot->kkm).' '.($kd4_max->teks ?? '').'. '.$this->kata($n4min, $mapel->pivot->kkm).' '.($kd4_min->teks?? 'Cek KD '.$k4min);
            $rerata4 = (count($nilais4) > 0) ? array_sum(array_values($nilais4))/count($nilais4) : 0;

            $n4 = [
                'nilai' => $rerata4,
                'predikat' => $this->huruf($rerata4, $mapel->pivot->kkm),
                'deskripsi' => $deskripsi4,
                'kkm' => $mapel->pivot->kkm
            ];

            $pas['rerata'][] = $n3['nilai'];
            $pas['mulok'][$mapel->kode_mapel]['nilai'] = ['k3' => $n3, 'k4' => $n4];
        }
        
        // Sikap
        $sikaps = [];
        $nilais1= Nilai::where([
            ['siswa_id','=',$request->siswa_id],
            ['periode_id','=',$request->periode],
            ['rombel_id','=', $request->rombel],
            ['mapel_id','=','pabp'],
            ['jenis','=', 'uh'],
            ['aspek','=','k1']
        ])->groupBy('kd_id')->get(['kd_id', DB::raw('AVG(nilai) AS nilai')]);
        $nilai_k1 = [];
        foreach($nilais1 as $nilai)
        {
            $nilai_k1[$nilai->kd_id] = $nilai->nilai??0;
        }
        $deskripsi1 = '';
        foreach($nilai_k1 as $k=>$n) 
        {

            $kd = Kd::where([
                ['kelas_id','=','all'],
                ['kode_kd','=',$k],
                ['mapel_id','=','pabp'],
                ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $deskripsi1 .= $this->kataSikap($n, 70)." ".$kd->teks .(count($nilai_k1) > 1 ? ', ':'');
        }
        $rerata1 = (count($nilai_k1) > 0) ? array_sum(array_values($nilai_k1)) / count($nilai_k1) : 0;
        $pas['sikap']['spiritual'] = [
            'id' => 1,
            'label' => 'Spiritual',
            'nilai' => $rerata1,
            'deskripsi' => $deskripsi1
        ];

        $nilais2= Nilai::where([
            ['siswa_id','=',$request->siswa_id],
            ['periode_id','=',$request->periode],
            ['rombel_id','=', $request->rombel],
            ['mapel_id','=','ppkn'],
            ['jenis','=', 'uh'],
            ['aspek','=','k2']
        ])->groupBy('kd_id')->get(['kd_id', DB::raw('AVG(nilai) AS nilai')]);
        
        $nilai_k2 = [];
        foreach($nilais2 as $nilai)
        {
            $nilai_k2[$nilai->kd_id] = $nilai->nilai??0;
        }
        $deskripsi2 = '';


        foreach( $nilai_k2 as $k=>$n ) 
        {

            $kd = Kd::where([
                ['kelas_id','=','all'],
                ['kode_kd','=',$k],
                ['mapel_id','=','ppkn'],
                ['kurikulum_id','=', $request->session()->get('kurikulum')]
            ])->first();

            $deskripsi2 .= $this->kataSikap($n, 70)." ".$kd->teks .(count($nilai_k2) > 1 ? ', ':'');
        }
        $rerata2 = (count($nilai_k2) > 0) ? array_sum(array_values($nilai_k2)) / count($nilai_k2) : 0;
        $pas['sikap']['sosial'] = [
            'id' => 2,
            'label' => 'Sosial',
            'nilai' => $rerata2,
            'deskripsi' => $deskripsi2
        ];

        $pas = $pas;
        return $pas;
        } catch(\Exception $e) {
            throw(['status' => 'Error', 'msg' => $e->getMessage()]);
        }
    }

    public function kata($nilai, $kkm)
    {
        $kkm = ($kkm != 0) ? $kkm : 75;
        switch ($nilai) {
            case ($nilai < $kkm):
                return "Perlu Bimbingan Dalam ";
                break;
            case ($nilai > $kkm):
                return "Cukup Baik Dalam ";
                break;
            case ($nilai > ($kkm+5)):
                return "Baik Dalam ";
                break;
            case ($nilai > ($kkm + 10)):
                return "Sangat Baik Dalam ";
                break;
        }
    }
    public function kataSikap ($nilai, $kkm)
    {
        $kkm = ($kkm != 0) ? $kkm : 75;
        switch ($nilai) {
            case ($nilai < $kkm):
                return "perlu bimbingan dalam aspek ";
                break;
            case ($nilai >= $kkm):
                return "cukup dalam aspek ";
                break;
            case ($nilai > ($kkm+5)):
                return " baik dalam aspek ";
                break;
            case ($nilai > ($kkm + 10)):
                return "Sangat baik dalam aspek ";
                break;
        }
    }

    public function huruf($nilai, $kkm)
    {
        $kkm = ($kkm != 0) ? $kkm : 70;
        switch ($nilai) {
            case ($nilai < $kkm):
                return "D";
                break;
            case ($nilai == $kkm ):
                return "C";
                break;
            case ($nilai > $kkm && $nilai < 90):
                return "B";
                break;
            case ($nilai > $kkm && $nilai >= 90):
                return "A";
                break;
        }
    }
}