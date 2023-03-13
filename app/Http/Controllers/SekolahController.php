<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sekolah;

class SekolahController extends Controller
{
    public function index(Request $request)
    {
    	try {
    		$sekolah = Sekolah::with('ks', 'ops')->first();
    		return response()->json(['success' => true, 'sekolah' => $sekolah, 'msg' => 'Data Sekolah'], 200);
    	} catch (\Exception $e) {
    		return response()->json(['success' => false, 'msg' => $e->getMessage()], 502);
    	}
    }
     public function store(Request $request)
     {
     	try {
     		Sekolah::updateOrCreate(
     			[
     				'npsn' => $request->npsn
     			],
     			[
     				'nama_sekolah' => $request->nama_sekolah,
     				'alamat' => $request->alamat,
     				'desa' => $request->desa,
     				'kec' => $request->kec,
     				'kab' => $request->kab,
     				'prov' => $request->prov,
     				'kode_pos' => $request->kode_pos,
     				'telp' => $request->telp,
     				'email' => $request->email,
     				'website' => $request->website,
     				'ks' => $request->ks,
     				'operator' => $request->operator
     			]

     		);
     		return response()->json(['success' => true, 'msg' => 'Data Sekolah disimpan'], 200);
     	} catch (\Exception $e) {
     		return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 502);
     	}
     }
}
