<?php

namespace App\Http\Controllers;

use App\Models\Pembelajaran;
use App\Models\Tema;
use Illuminate\Http\Request;

class PembelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if($request->role == 'wali') {
                // $datas = Pembelajaran::where('rombel_id', $request->rombel['kelas_id'])->get();
                $rombel_id = $request->rombel['kode_rombel'] ?? '21221-1';
                // $datas = Tema::where([
                //     ['kelas_id','=', $request->rombel['kelas_id']]
                // ])->whereHas('subtemas.pembelajaran', function($q) use($rombel_id) {
                //     $q->where('pembelajarans.rombel_id','=',$rombel_id);

                // })->with('subtemas.pembelajaran')->get();

                $datas = Tema::whereHas('pembelajaran', function($q) use ($rombel_id) {
                    $q->where('rombel_id','=', $rombel_id);
                })->with('subtemas', function($s){
                    $s->whereHas('pembelajaran')->with('pembelajaran', function($p) {
                        $p->with('guru', 'rombel');
                    });
                })->orderBy('kode_tema')->get();
            } else {

                $datas = Pembelajaran::where([
                    ['periode_id','=',$request->periode],
                    ['guru_id','=', $request->guru]
                ])->get();
                // dd($datas);
            }
            return response()->json(['success' => true, 'pembelajarans' => $datas], 200);
        } catch (\Exception $e) {
            dd($e);
            // return response()->json(['success' => false, 'msg' => $e]);
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
            // kode Pemb: rombel_id:tematik:ke
            Pembelajaran::updateOrCreate(
                [
                    'ke' => $request->ke,
                    'kode_pembelajaran' => $request->rombel.':'.$request->tematik.':'.$request->subtema.':'.$request->ke,
                    'periode_id' => $request->periode,
                    'rombel_id' => $request->rombel_id,
                    'guru_id' => $request->guru,
                    'tematik' => $request->tematik,
                    'tema_id' => $request->tema,
                    'subtema_id' => $request->subtema,
                ],
                [
                    'mupels' => $request->mupels,
                    'tujuan' => $request->tujuan,
                    'kegiatan' => $request->kegiatan,
                    'penilaian' => $request->penilaian,
                    'active' => 1,
                    'tanggal' => $request->tanggal,
                ]
            );

            return response()->json(['success' => true, 'msg' => 'Pembelajaran disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pembelajaran  $pembelajaran
     * @return \Illuminate\Http\Response
     */
    public function show(Pembelajaran $pembelajaran)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pembelajaran  $pembelajaran
     * @return \Illuminate\Http\Response
     */
    public function edit(Pembelajaran $pembelajaran)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pembelajaran  $pembelajaran
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pembelajaran $pembelajaran)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pembelajaran  $pembelajaran
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pembelajaran $pembelajaran)
    {
        //
    }
}
