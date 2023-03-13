<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use Illuminate\Http\Request;
use App\Models\Pemetaan;

class TemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            if ( $request->kelas ) {
                $temas = Tema::where('kelas_id','=',$request->kelas)->with('subtemas')->get();
            } else {
                $temas = Tema::with('subtemas')->get();
            }
            $semester = substr($request->session()->get('periode'), 4,1);
            $datas = Pemetaan::where([
                ['pemetaans.semester','=',$semester],
                ['pemetaans.kelas_id','=',$request->kelas]
            ])->whereHas('tema')->with('tema.subtemas')->groupBy('tema_id')->get(['tema_id']);
            // dd($datas);
            return response()->json(['success' => true, 'datas' => $datas], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 502);
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
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function show(Tema $tema)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function edit(Tema $tema)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tema $tema)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tema  $tema
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tema $tema)
    {
        //
    }
}
