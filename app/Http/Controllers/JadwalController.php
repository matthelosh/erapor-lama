<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        try {
            $jadwals = Jadwal::where([
                ['periode_id', '=', $request->periode],
                ['guru_id', '=', $request->guru],
            ])->with('rombel')->get();

            return response()->json(['success' => true, 'jadwals' => $jadwals], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function showByHari(Request $request)
    {
        try {
            
        } catch (\Exception $e) {
            
        }
    }
}
