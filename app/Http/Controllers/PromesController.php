<?php

namespace App\Http\Controllers;

use App\Models\Promes;
use Illuminate\Http\Request;

class PromesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if(!$request->q) {
                $promes = Promes::where([
                    ['kelas','=',$request->kelas],
                    ['semester','=', $request->semester],
                ])->with('tema', 'subtema', 'mapel')->with('kd', function($k) use ($request) {
                    $kelas = substr($request->kelas, 6,1);
                    $k->where('mapel_id', $request->mapel)->where('kelas_id', $kelas);
                })->get();
                // $promes = Promes::all();
            } else {
                if($request->q == 'byhari') {
                    $jadwal = 'App\Models\Jadwal'::where('rombel_id', $request->kelas)->where('periode_id', $request->periode)->where('guru_id', $request->guru)->first();
                    // dd($jadwal);
                    $pekans = 'App\Models\Pekan'::where('hari', $jadwal->hari)->where('periode_id',$request->periode)->get();
                    $promes = Promes::where([
                        ['kelas','=',$request->kelas],
                        ['semester','=', $request->semester],
                    ])->with('tema', 'subtema', 'mapel')->with('kd', function($k) use ($request) {
                        $kelas = substr($request->kelas, 6,1);
                        $k->where('mapel_id', $request->mapel)->where('kelas_id', $kelas);
                    })->get();
                    return response()->json(['success' => true, 'promes' => $promes, 'pekans' => $pekans],200);
                 } else if($request->q == 'rpp') {
                    $promes = Promes::where([
                        ['kelas','=', $request->rombel],
                        ['mapel_id', '=', $request->mapel],
                        ['tematik','=', $request->tematik]
                    ])->with('kd', function($q) use($request) {
                        $q->where('kelas_id', substr($request->rombel, 6,1))->where('mapel_id', $request->mapel);
                    })->get();

                    return response()->json(['success' => true, 'promes' => $promes], 200);
                 }
            }
            
        } catch (\Exception $e) {
            dd($e);
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
            $kelas = substr($request->rombel, 6,1);
            Promes::updateOrCreate(
                [
                    'id' => $request->id ?? null,
                    'kelas' => $request->rombel,
                    'semester' => $request->semester,
                    'tematik' => $request->tematik,
                    'tema_id' => $request->tema,
                    'subtema_id' => $request->subtema_id ?? null,
                    'mapel_id' => $request->mapel,
                    'kd_id' => $request->kd_id,
                ],
                [
                    
                    'bulan' => $request->bulan,
                    'minggu' => $request->minggu
                ]
            );

            return response()->json(['success' => true, 'msg' => 'Data Promes disimpan'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Promes  $promes
     * @return \Illuminate\Http\Response
     */
    public function show(Promes $promes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Promes  $promes
     * @return \Illuminate\Http\Response
     */
    public function edit(Promes $promes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Promes  $promes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Promes $promes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Promes  $promes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Promes $promes)
    {
        //
    }
}
