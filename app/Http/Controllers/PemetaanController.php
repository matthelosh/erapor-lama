<?php

namespace App\Http\Controllers;

use App\Models\Pemetaan;
use Illuminate\Http\Request;

class PemetaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $datas = [];
            if ( !$request->q ) {
                $petas = Pemetaan::where([
                    ['kelas_id','=', $request->kelas],
                    ['tema_id','=', $request->tema],
                    ['subtema_id','=', $request->subtema],
                ])->with('mapel')->get();
                foreach($petas as $peta)
                {
                    $kds = 'App\Models\Kd'::where([
                        ['kelas_id','=', $request->kelas],
                        ['mapel_id','=', $peta->mapel_id],
                        ['kode_kd', 'LIKE', '%'.substr($peta->kd_id, 1)]
                    ])->get();
                    $datas[] = ['id' => $peta->id, 'tema_id' => $peta->tema_id, 'subtema_id' => $peta->subtema_id, 'mapel_id' => $peta->mapel_id, 'mapel' => $peta->mapel, 'kd_id' => $peta->kd_id, 'kds' => $kds];
                }
                // dd('halo');
            } else {
                if ( $request->q == 'nontema') {
                    $petas = Pemetaan::where([
                        ['tematik', '=', $request->tematik],
                        ['mapel_id','=', $request->mapel],
                        ['kelas_id','=',substr($request->rombel,6,1)],
                        ['semester','=', $request->semester]
                    ])->with('kd', function($k) use($request){
                        $k->where('mapel_id', $request->mapel)->where('kelas_id', substr($request->rombel,6,1));
                    })->with('mapel')->orderByRaw('LENGTH(tema_id) asc')->get();
                    $datas= $petas;
                } else {
                    $semester = $request->semester;
                    $kelas = $request->kelas;
                    $temas = 'App\Models\Tema'::where([
                        ['kelas_id' ,'=', $request->kelas]
                    ])->whereHas('subtemas.pemetaans', function($q) use ($semester){
                        $q->where('pemetaans.semester','=',$semester);
                            // ->with('kd', function($k) {
                            //     $k->where();
                            // });
                    })->with('subtemas.pemetaans', function($p)  use ($kelas){
                        $kd = $p->get('kd_id')[0]['kd_id'];
                        $p->with('kd', function($k) use ($kd, $kelas) {
                            $k->where('kds.mapel_id','=',$k->get('mapel_id')[0]['mapel_id'])->where('kds.kelas_id','=',$kelas);
                        })->with('mapel');
                        
                    })->get();
                    $datas = $temas;
                }
            }
            return response()->json(['success' => true, 'pemetaans' => $datas], 200);
        } catch (\Exception $e) {
            dd($e);
            return response()->json(['success' => false, 'msg' => $e]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pemetaan  $pemetaan
     * @return \Illuminate\Http\Response
     */
    public function show(Pemetaan $pemetaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pemetaan  $pemetaan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pemetaan $pemetaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pemetaan  $pemetaan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pemetaan $pemetaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pemetaan  $pemetaan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pemetaan $pemetaan)
    {
        //
    }
}
