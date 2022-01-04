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
                ['ppn','=', 'ts'],
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
            ])->first();

            $kd_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$kmin],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            

            $deskripsi = 'Ananda ' . $siswa->nama .' '.$this->kata($nmax, $mapel->pivot->kkm).' '.($kd_max->teks ?? '').', '.$this->kata($nmin, $mapel->pivot->kkm).' '.($kd_min->teks?? 'Cek KD');

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
                ['ppn','=', 'ts'],
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
            ])->first();

            $kd_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$kmin],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            

            $deskripsi = 'Ananda ' . $siswa->nama .' '.$this->kata($nmax, $mapel->pivot->kkm).' '.($kd_max->teks ?? '').', '.$this->kata($nmin, $mapel->pivot->kkm).' '.($kd_min->teks?? 'Cek KD');

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
            $nr3=[];
            foreach($nilai3 as $nilai) {
                $nilais3[$nilai->kd_id] = $nilai->nilai;
                array_push($nr3, $nilai->nilai);
            }

            // dd(array_sum($nr3)/count($nr3));

            $n3max=0;
            $k3max='';
            $n3min=0;
            $k3min='';

            foreach($nilais3 as $k=>$n)
            {
                if($n > $n3max) {
                    $n3max = $n;
                    $k3max = $k;
                } else {
                    $n3min = $n;
                    $k3min = $k;
                }
            }

            $kd3_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k3max],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            $kd3_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k3min],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            $deskripsi3 = $this->kata($n3max, $mapel->pivot->kkm).' '.($kd3_max->teks ?? '').'. '.$this->kata($n3min, $mapel->pivot->kkm).' '.($kd3_min->teks?? 'Cek KD');
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

            $nilais4 = [];
            foreach($nilai_h4 as $nilai)
            {
                $nilais4[$nilai->kd_id] = $nilai->nilai??0;
            }

            $n4max=0;
            $k4max='';
            $n4min=0;
            $k4min='';

            foreach($nilais4 as $k=>$n)
            {
                if($n > $n4max) {
                    $n4max = $n;
                    $k4max = $k;
                } else {
                    $n4min = $n;
                    $k4min = $k;
                }
            }

            $kd4_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k4max],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            $kd4_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k4min],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            $deskripsi4 = $this->kata($n4max, $mapel->pivot->kkm).' '.($kd4_max->teks ?? '').'. '.$this->kata($n4min, $mapel->pivot->kkm).' '.($kd4_min->teks?? 'Cek KD');
            $rerata4 = (count($nilais4) > 0) ? array_sum(array_values($nilais4))/count($nilais4) : 0;

            $n4 = [
                'nilai' => $rerata4,
                'predikat' => $this->huruf($rerata4, $mapel->pivot->kkm),
                'deskripsi' => $deskripsi4,
                'kkm' => $mapel->pivot->kkm
            ];

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

            $n3max=0;
            $k3max='';
            $n3min=0;
            $k3min='';

            foreach($nilais3 as $k=>$n)
            {
                if($n > $n3max) {
                    $n3max = $n;
                    $k3max = $k;
                } else {
                    $n3min = $n;
                    $k3min = $k;
                }
            }

            $kd3_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k3max],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            $kd3_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k3min],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            $deskripsi3 = $this->kata($n3max, $mapel->pivot->kkm).' '.($kd3_max->teks ?? '').'. '.$this->kata($n3min, $mapel->pivot->kkm).' '.($kd3_min->teks?? 'Cek KD');
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

            $nilais4 = [];
            foreach($nilai_h4 as $nilai)
            {
                $nilais4[$nilai->kd_id] = $nilai->nilai??0;
            }

            $n4max=0;
            $k4max='';
            $n4min=0;
            $k4min='';

            foreach($nilais4 as $k=>$n)
            {
                if($n > $n4max) {
                    $n4max = $n;
                    $k4max = $k;
                } else {
                    $n4min = $n;
                    $k4min = $k;
                }
            }

            $kd4_max = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k4max],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            $kd4_min = Kd::where([
                ['kelas_id','=',$kelas->kode_kelas],
                ['kode_kd','=',$k4min],
                ['mapel_id','=',$mapel->kode_mapel],
            ])->first();

            $deskripsi4 = $this->kata($n4max, $mapel->pivot->kkm).' '.($kd4_max->teks ?? '').'. '.$this->kata($n4min, $mapel->pivot->kkm).' '.($kd4_min->teks?? 'Cek KD');
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
        $n1max=0;
        $k1max='';
        $n1min=0;
        $k1min='';
        foreach($nilai_k1 as $k=>$n) 
        {
            if($n > $n1max) {
                $n1max = $n;
                $k1max = $k;
            } 
            if($n1min == 0 || $n1min > $n) {
                $n1min = $n;
                $k1min = $k;
            }
        }
        $kd1_max = Kd::where([
            ['kelas_id','=','all'],
            ['kode_kd','=',$k1max],
            ['mapel_id','=','pabp'],
        ])->first();
        
        $kd1_min = Kd::where([
            ['kelas_id','=','all'],
            ['kode_kd','=',$k1min],
            ['mapel_id','=','pabp'],
        ])->first();
        // $mapel1 = Mapel::where([
        //     ['kode_mapel', '=','pabp'],

        // ])
        $deskripsi1 = $this->kataSikap($n1max, 70).' '.($kd1_max->teks ?? '').'. '.$this->kataSikap($n1min, 70).' '.($kd1_min->teks?? 'Cek KD');
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
        $n2max=0;
        $k2max='';
        $n2min=0;
        $k2min='';
        foreach( $nilai_k2 as $k=>$n ) 
        {
            if($n > $n2max) {
                $n2max = $n;
                $k2max = $k;
            } 
            if($n2min == 0 || $n2min > $n){
                $n2min = $n;
                $k2min = $k;
            }
        }
        $kd2_max = Kd::where([
            ['kelas_id','=','all'],
            ['kode_kd','=',$k2max],
            ['mapel_id','=','ppkn'],
        ])->first();
        
        $kd2_min = Kd::where([
            ['kelas_id','=','all'],
            ['kode_kd','=',$k2min],
            ['mapel_id','=','ppkn'],
        ])->first();
        
        $deskripsi2 = $this->kataSikap($n2max, 70).' '.($kd2_max->teks ?? '').'. '.$this->kataSikap($n2min, 70).' '.($kd2_min->teks?? 'Cek KD');
        $rerata2 = (count($nilai_k2) > 0) ? array_sum(array_values($nilai_k2)) / count($nilai_k2) : 0;
        $pas['sikap']['sosial'] = [
            'id' => 2,
            'label' => 'Spiritual',
            'nilai' => $rerata2,
            'deskripsi' => $deskripsi2
        ];

        $pas = $pas;
        return $pas;
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
                return "perlu bimbingan dalam ";
                break;
            case ($nilai >= $kkm):
                return "cukup";
                break;
            case ($nilai > ($kkm+5)):
                return " ";
                break;
            case ($nilai > ($kkm + 10)):
                return "Sangat ";
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