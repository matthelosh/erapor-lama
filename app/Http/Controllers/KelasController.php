<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        try {
            $kelas = Kelas::with('mapels', 'rombels', 'temas')->get();
            // dd($kelas);
            return response()->json(['success' => true, 'kelas' => $kelas], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 501);
        }
    }

    public function show(Request $request, $id)
    {
    	// dd($id);
    	try {
    		$kelas = Kelas::find($id);
    		// dd($kelas->mapels);
    		return response()->json(['success' => true, 'msg' => 'Data Kelas Mapel', 'mapels' => $kelas->mapels], 200);
    	} catch (\Exception $e) {
    		return response()->json(['success' => false, 'msg' => $e->getMessage()], 501);
    		
    	}
    }

    public function attachMapel(Request $request, $id)
    {
    	try {
    		Kelas::find($id)->mapels()->attach($request->mapels);
    		return response()->json(['success' => true, 'msg' => "Data Mapel Ditambahkan."], 200);
    	} catch (\Exception $e) {
    		return response()->json(['success' => false, 'msg' => $e->getMessage()], 502);
    	}
    }
    
    public function detachMapel(Request $request, $id)
    {
    	try {
    		Kelas::find($id)->mapels()->detach($request->mapels);
    		return response()->json(['success' => true, 'msg' => "Data Mapel Dikeluarkan."], 200);
    	} catch (\Exception $e) {
    		return response()->json(['success' => false, 'msg' => $e->getMessage()], 502);
    	}
    }

}
