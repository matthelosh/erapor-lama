<?php

namespace App\Http\Controllers;

use App\Models\Nilai;
use App\Models\Rombel;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Traits\NilaiTrait;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    use NilaiTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Request $request)
    {
        
        if ( $request->rombel || $request->mapel) {
            return response()->json(['success' => true, 'nilais' => $this->nilais($request)]);
        } else {
            return response()->json(['ikhtisar' => $this->prosentase($request)]);
        }
    }

    public function ledger(Request $request)
    {
        try {
            $datas = [];
            $rombel = Rombel::where('kode_rombel', $request->rombel_id)->with('siswas')->first();
            $kelas = Kelas::where('kode_kelas', $rombel->kelas_id)->with('mapels')->first();
            $s=0;
            foreach($rombel->siswas as $siswa)
            {
                $datas[$s] = ['nisn' => $siswa->nisn, 'nama' => $siswa->nama, 'nilais' => []];
                foreach($kelas->mapels as $mapel) {
                    
                    $nilai = Nilai:: where([
                        ['periode_id', '=', $request->session()->get('periode')],
                        ['rombel_id', '=', $request->rombel_id],
                        ['siswa_id', '=', $siswa->nisn],
                        ['mapel_id', '=', $mapel->kode_mapel],
                    ])->avg('nilai');
                    // $datas[$siswa->nisn]->nilais[$mapel->kode_mapel] = $nilai;
                    $datas[$s]['nilais'][$mapel->kode_mapel] = $nilai;
                }
                $s++;
            }
            return response()->json(['success' => true, 'nilais' => $datas], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }
    public function import(Request $request)
    {
        $datas = $request->all();
        // dd($datas['siswas']);
        try {
            foreach($datas['siswas'] as $siswa) {
                foreach($siswa['nilais'] as $nilai)
                {
                    if (isset($nilai['kd_id'])) {
                        Nilai::updateOrCreate(
                            [
                                'periode_id'=> $datas['periode_id'],
                                'semester'=> $datas['semester'],
                                'rombel_id'=> $datas['rombel_id'],
                                'aspek'=> $datas['aspek'],
                                'jenis'=>$datas['jenis'],
                                'mapel_id'=> $datas['mapel_id'],
                                'kd_id'=> $nilai['kd_id'],
                                'ppn'=> $nilai['ppn']??'ts',
                                'siswa_id' => $siswa['nisn'],
                            ],
                            [
                                'nilai' => $nilai['nilai']
                            ]
                        );
                    }
                }
            }
            return response()->json(['success' => true, 'msg' => 'Data Nilai Diimpor'], 200);
        } catch ( \Exception $e)
        {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // dd($request->data);
            // $i = 0;
            // dd(count($request['data']['nilais']));
            foreach($request['data']['nilais'] as $nilai)
            {
                Nilai::updateOrCreate(
                    [

                        'periode_id'=> $request['data']['periode_id'],
                        'semester'=> $request['data']['semester'],
                        'rombel_id'=> $request['data']['rombel_id'],
                        'aspek'=> $request['data']['aspek'],
                        'jenis'=>$request['data']['jenis'],
                        'mapel_id'=> $request['data']['mapel_id'],
                        'kd_id'=> $request['data']['kd_id'],
                        'ppn'=> $request['data']['ppn']??'ts',
                        'siswa_id' => $nilai['nisn'],
                        
                    ],
                    [
                        'nilai' => $nilai['nilai']
                    ]
                );
            }
            return response()->json(['success' => true, 'msg' => 'Data Nilai Disimpan'], 200);
        } catch ( \Exception $e )
        {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function show(Nilai $nilai)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function edit(Nilai $nilai)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nilai $nilai)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Nilai  $nilai
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $kd)
    {
        // dd($request->all());
        try {
            Nilai::where([
                ['periode_id', '=', $request->periode],
                ['mapel_id' ,'=', $request->mapel],
                ['semester' ,'=', $request->semester],
                ['jenis' ,'=', $request->jenis],
                ['ppn' ,'=', $request->ppn],
                ['rombel_id' ,'=', $request->rombel],
                ['kd_id' ,'=', $kd]
            ])->delete();
            return response()->json([ 'success' =>true, 'msg' => 'Data nilai dihapus.'], 200);
        } catch (\Exception $e) {
            return response()->json([ 'success' => false, 'msg' => $e->getMessage()], 500);
        }
    }
}
