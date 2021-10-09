<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kd;

class KdController extends Controller
{
    public function index(Request $request)
    {
        $where = ($request->kelas) ? [['mapel_id','=', $request->mapel],['kelas_id','=',$request->kelas]] : ['mapel_id','=', $request->mapel];

        try {
            $kds = Kd::where($where)->orderBy('id', 'DESC')->get();
            return response()->json(['success' => true, 'msg' => 'Data KD', 'kds' => $kds], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 502);
        }
    }

    public function impor(Request $request)
    {
        try {
            foreach($request->kds as $kd)
            {
                Kd::updateOrCreate(
                    [
                        'kelas_id' => $request->kelas,
                        'mapel_id' => $request->mapel,
                        'kode_kd' => $kd['kode_kd'],
                        'agama' => $kd['agama']
                    ],
                    [
                        'teks' => $kd['teks']
                    ]
                );

                return response()->json(['success' => true, 'msg' => 'Data Kd Diimpor'], 200 );
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e]);
        }
    }

    public function store(Request $request)
    {
        try {
            $kd = (object) $request->kd;
            Kd::updateOrCreate(
                [
                    "id" => $kd->id ?? null,
                    "kode_kd" => $kd->kode_kd,
                    "mapel_id" => $kd->mapel_id,
                    "kelas_id" => $kd->kelas_id,
                    "agama" => $kd->agama,
                ],
                [
                    "teks" => $kd->teks,
                ]
            );
             return response()->json(['success' => true, 'msg' => 'Data Kd Disimpan'], 200 );
        } catch (\Exception $e) {
            // return response()->json(['success' => false, 'msg' => $e]);
            dd($e);
        }
    }

    public function destroy(Request $request)
    {
        try {
            $ids = $request->ids;
            foreach($ids as $id)
            {
                Kd::find($id)->delete();
            }
            return response()->json(['success' => true, 'msg' => 'Data Kd Disimpan'], 200 );
        } catch (\Exception $e) {
            dd($e);
        }
    }
}
