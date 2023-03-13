<?php

namespace App\Http\Controllers;

use App\Models\Pekan;
use Illuminate\Http\Request;

class PekanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $pekans = Pekan::where('periode_id', $request->periode)->orderBy('bulan')->get();
            return response()->json(['success' => true, 'pekans' => $pekans], 200);
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
            Pekan::updateOrCreate(
                [
                    'periode_id' => $request->periode_id,
                    'bulan' => $request->bulan,
                    'hari' => $request->hari,
                ],
                [
                    'jumlah' => $request->jumlah
                ]
            );
            return response()->json(['success' => true, 'msg' => 'Pekan Efektif Disimpan'], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pekan  $pekan
     * @return \Illuminate\Http\Response
     */
    public function show(Pekan $pekan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Pekan  $pekan
     * @return \Illuminate\Http\Response
     */
    public function edit(Pekan $pekan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pekan  $pekan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pekan $pekan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pekan  $pekan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pekan $pekan)
    {
        //
    }
}
