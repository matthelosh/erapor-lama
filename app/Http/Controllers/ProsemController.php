<?php

namespace App\Http\Controllers;

use App\Models\Prosem;
use Illuminate\Http\Request;

class ProsemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->query());
        if ($request->kelas) {
            $datas = [];
            $kelas = 'App\Models\Kelas'::where([
                ['kode_kelas','=', $request->kelas]
            ])->with('mapels')->first();

            foreach($kelas->mapels as $mapel)
            {
                $prosems = Prosem::where([
                    ['kelas','=', $request->kelas],
                    ['mapel_id','=', $mapel->kode_mapel]
                ])->get();

                foreach($prosems as $prosem) 
                {
                    if ($prosem->semester == '1') {
                        $datas['ganjil'][$mapel->kode_mapel][] = $prosem->kd_id;
                    } else {
                        $datas['genap'][$mapel->kode_mapel][] = $prosem->kd_id;
                    }
                }
            }

            return response()->json(['success' => true, 'prosems' => $datas], 200);
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
     * @param  \App\Models\Prosem  $prosem
     * @return \Illuminate\Http\Response
     */
    public function show(Prosem $prosem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prosem  $prosem
     * @return \Illuminate\Http\Response
     */
    public function edit(Prosem $prosem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Prosem  $prosem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prosem $prosem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prosem  $prosem
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prosem $prosem)
    {
        //
    }
}
