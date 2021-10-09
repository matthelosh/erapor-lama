<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rombel;
use App\Models\Periode;

class RombelController extends Controller
{
    public function index(Request $request)
    {
        // dd($request->all());
        if($request->mode == 'select') {
            try {
                $rombels = Rombel::where('periode_id', $request->session()->get('periode'))->get();
                return response()->json(['success' => true, 'rombels' => $rombels], 200);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'msg' => $e->getMessage()], 503);
            }
        } else {
            try {
                // dd($request->periode);
                $periode = $request->periode;
                $rombels = Rombel::with('walis','kelas', 'siswas')->where('rombels.periode_id', $request->periode)->get();
                return response()->json([ 'success' => true, 'rombels' => $rombels ], 200);
            } catch ( \Exception $e ) {
                return response()->json([ 'success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 502);
            }
        }
    }

    public function store(Request $request)
    {
    	$request->validate(
    		[
    			'kode_rombel' => 'unique:rombels',
    			'label' => 'required',
    			'kelas_id' => 'required',
    			'periode_id' => 'required'
    		],
    		[
    			'kode_rombel.unique' => 'Rombel ' . $request->kelas_id . ' sudah ada di periode ini',
    			'label.required' => 'Label Rombel harus diisi',
    			'kelas_id.required' => 'Pilih Kelas Dulu',
    			'periode_id.required' => 'Pilih Periode dulu'
    		]
    	);

    	try {
    		Rombel::create([
    			'kode_rombel' => $request->kode_rombel,
    			'label' => $request->label,
    			'kelas_id' => $request->kelas_id,
    			'periode_id' => $request->periode_id,
    			'guru_id' => $request->guru_id ?? null
    		]);
    		return response()->json(['success' => true, 'msg' => 'Data Rombel baru tersimpan'], 200);
    	} catch ( \Exception $e ) {
    		return response()->json(['success' => false, 'msg' => $e->getCode() .':'.$e->getMessage()], 502);
    	}
    }

    public function show(Request $request, $id)
    {
        try {
            $rombel = Rombel::where('id',$id)->with('siswas')->first();
            
            return response()->json(['success' => true, 'members' => $rombel->siswas], 200);
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function masukkan(Request $request)
    {
        try {
            if ( $request->mode == 'impor' ) {
                foreach( $request->data as $siswas) {
                    $siswa = 'App\Models\Siswa'::where('nisn', $siswas['nisn'])->first();
                    Rombel::find($request->query('id'))->siswas()->attach($siswa->id, ['periode' => $request->session()->get('periode')]);
                }
            } else {
                foreach($request->data as $siswa)
                {
                    Rombel::find($request->query('id'))->siswas()->attach($siswa['id'], ['periode' => $request->session()->get('periode')]);

                }
            }
            return response()->json(['success' => true, 'msg' => 'Data Siswa dimasukakn ke rombel ini.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 502);
        }
    }



    public function keluarkan(Request $request)
    {
        try {
            foreach ( $request->data as $siswa )
            {
                Rombel::find($request->query('id'))->siswas()->detach($siswa['id']);
            }
            return response()->json(['success' => true, 'msg' => 'Siswa dikeluarkan dari rombel.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 502);
        }
    }

    public function duplikat(Request $request)
    {
        try {
            $periode = $request->periode;
            $rombels = $request->rombels;
            // dd($rombels);
            // kelas_id
            
            foreach ($rombels as $rombel)
            {
                $rmbl = explode('-', $rombel['kode_rombel']);
                $perd = substr($periode, 0,4).'1';
                $r = Rombel::where('kode_rombel', $perd.'-'.$rmbl[1])->first();
                // dd($r->siswas);
                $siswas = $r->siswas;
                // dd($siswas);
                foreach($siswas as $siswa)
                {
                    // dd($siswa);
                    Rombel::find($rombel['id'])->siswas()->attach($siswa['id'], ['periode' => $periode]);
                }
            }
            return response()->json(['success' => true, 'msg' => 'Data siswa berhasil disalin.'], 200);
        } catch (\Exception $e){
            return response()->json(['success' => false, 'msg' =>$e->getCode().':'.$e->getMessage()], 502);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            Rombel::find($id)->update($request->all());
            return response()->json(['success' => true, 'msg' => 'Data Rombel diperbarui'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 501);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $rombel = Rombel::find($id);
            // dd($rombel->siswas());
            $rombel->siswas()->detach();
            $rombel->delete();

            return response()->json(['success' => true, 'msg' => 'Rombel dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 502);
        }
    }
}
