<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jurnal;

class JurnalController extends Controller
{
    public function index(Request $request) 
    {
        try {
            $kode = $request->rombel;

            $jurnals = Jurnal::where('pembelajaran_id', 'LIKE', $kode.':%')->whereHas('pembelajaran', function($q) {
                $q->where('active',1);
            })->with('pembelajaran', function($p) {
                $p->with('tema','subtema');
            })->get();
            return response()->json(['success' => true, 'jurnals' => $jurnals], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function store(Request $request)
    {
        try {
            Jurnal::updateOrCreate(
                [
                    'pembelajaran_id' => $request->pembelajaran_id,
                ],
                [
                    'kegiatan' => $request->kegiatan, 
                    'keterangan' => $request->keterangan, 
                    'media' => $request->media, 
                    'metode' => $request->metode, 
                    'tagihan' => $request->tagihan,
                    'materi' => $request->materi,
                    'tanggal' => $request->tanggal
                ]
            );

            return response()->json(['success' => true, 'msg' => 'Jurnal disimpan'], 200);
        } catch (\Exception $e) {
            // return response()->json(['success' => false, 'msg' => $e]);
            dd($e);
        }
    }

    public function show(Request $request)
    {
        try {
            $jurnal = Jurnal::where('pembelajaran_id', $request->pembelajaran_id)->first();
            return response()->json(['success' => true, 'jurnal' => $jurnal], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
