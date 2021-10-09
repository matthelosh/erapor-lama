<?php

namespace App\Http\Controllers;

use App\Models\Periode;
use Illuminate\Http\Request;
use Exception;
use Inertia\Inertia;

class PeriodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $periodes = Periode::all();
            return response()->json(['success' => true, 'periode' => $periodes], 200);
        } catch ( \Exception $e )
        {
            return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 502);
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
        $request->validate(
            [
                'kode_periode' => 'unique:periodes'
            ],
            [
                'kode_periode.unique' => 'Periode ini sudah ada.'
            ]
        );
        try {
            Periode::create([
                'kode_periode' => $request->kode_periode,
                'tapel' => $request->tapel,
                'semester' => $request->semester,
                'deskripsi' => $request->deskripsi
            ]);
            return response()->json(['success' => true, 'msg' => 'Data periode ditambahkan'], 200);
        } catch ( \Exception $e )
        {
            return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 501);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Periode  $periode
     * @return \Illuminate\Http\Response
     */
    public function show(Periode $periode)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Periode  $periode
     * @return \Illuminate\Http\Response
     */
    public function edit(Periode $periode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Periode  $periode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $check1 = Periode::where('active', true)->count();

            if ( $request->active == 1 ) {
                Periode::where('active', 1)->update(['active' => 0]);
            } else {
                if ( $check1 == 1 ) {
                    throw new Exception("Langsung pilih periode yang ingin diaktifkan tanpa menonaktifkan periode sekarang.", 1);
                    
                }
            }
            $periode = Periode::find($id);
            $periode->update(['active' => $request->active]);
            $status = ($request->active == 1) ? ' diaktifkan.' : ' dinonaktifkan.';
            session(['periode' => $periode->kode_periode]);
            $oldPeriode = Inertia::getShared('periode');
            Inertia::share(['periode' => function () use ($oldPeriode) {
                return $oldPeriode = $periode->kode_periode;
            }]);
            return response()->json(['success' => true, 'msg' => 'Periode telah'.$status ], 200);
            


            
        } catch ( \Exception $e ) {
            return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 502);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Periode  $periode
     * @return \Illuminate\Http\Response
     */
    public function destroy(Periode $periode)
    {
        //
    }
}
