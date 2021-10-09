<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EkskulSiswa;
use App\Models\Ekskul;
use App\Models\Saran;
use App\Models\Fisik;
use App\Models\Kesehatan;
use App\Models\Prestasi;
use App\Models\Absensi;

class DataRaporController extends Controller
{
    public function ekskul(Request $request)
    {
        try {
            $ekskuls = Ekskul::all();
            return response()->json(['success' => true, 'ekskuls' => $ekskuls], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }
    public function ekskulSiswa(Request $request)
    {
        try {
            
            $data_ekskuls = EkskulSiswa::where([
                ['periode_id','=', $request->periode],
                ['rombel_id','=', $request->rombel],
                ['siswa_id', '=', $request->query('siswa')]
            ])->with('ekskuls')->get();
            return response()->json(['success' => true, 'dataEkskuls' => $data_ekskuls ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function ekskulSiswaCreate(Request $request)
    {
        try {
            $data = $request->all();
            EkskulSiswa::updateOrCreate(
                ['periode_id' => $data['periode_id'],
                'rombel_id' =>  $data['rombel_id'],
                'siswa_id' => $data['siswa_id'],
                'ekskul_id' => $data['ekskul_id']],
                ['ket' => $data['ket']]
            );
            // dd($data['periode_id']);
            $data['ekskuls'] = Ekskul::where('kode_ekskul', $request->ekskul_id)->first();
            return response()->json(['success' => true, 'msg' => 'Data Ekskul disimpan', 'ekskul' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function saran(Request $request)
    {
        try {
            $saran = Saran::where([
                ['periode_id', '=', $request->periode_id],
                ['rombel_id', '=', $request->rombel_id],
                ['siswa_id', '=', $request->siswa_id],
            ])->first();
            return response()->json(['success' => true, 'saran' => $saran], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function saranCreate(Request $request)
    {
        // dd($request->all());
        try {
            Saran::updateOrCreate(
                [
                    'periode_id' => $request->periode_id,
                    'rombel_id' => $request->rombel_id,
                    'siswa_id' => $request->siswa_id,
                    'jenis_rapor' => $request->jenis_rapor,
                ],
                [ 
                    
                    'teks' => $request->teks
                ]
            );
            return response()->json(['success' => true, 'msg' => 'Data Saran disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);  
        }
    }
    public function fisik(Request $request)
    {
        try {
            $fisik = Fisik::where([
                ['periode_id', '=', $request->periode_id],
                ['rombel_id', '=', $request->rombel_id],
                ['siswa_id', '=', $request->siswa_id],
            ])->first();
            return response()->json(['success' => true, 'fisik' => $fisik], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }
    public function fisikCreate(Request $request)
    {
        try {
            Fisik::updateOrCreate(
                ['periode_id' => $request->periode_id,
                'rombel_id' => $request->rombel_id,
                'siswa_id' => $request->siswa_id],
                ['tb' => $request->tb, 'bb' => $request->bb]
            );
            return response()->json(['success' => true, 'msg' => 'Data Fisik disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);  
        }
    }

    public function kesehatan(Request $request)
    {
        try {
            $kesehatan = Kesehatan::where([
                 ['periode_id', '=', $request->periode_id],
                ['rombel_id', '=', $request->rombel_id],
                ['siswa_id', '=', $request->siswa_id],
            ])->first();
            return response()->json(['success' => true, 'kesehatan' => $kesehatan], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function kesehatanCreate(Request $request)
    {
        try {
            Kesehatan::updateOrCreate(
                 [
                    'periode_id' => $request->periode_id,
                    'rombel_id' => $request->rombel_id,
                    'siswa_id' => $request->siswa_id
                ],
                [
                    'pendengaran' => $request->pendengaran,
                    'penglihatan' => $request->penglihatan,
                    'gigi' => $request->gigi,
                    'lainnya' => $request->lainnya,
                ]
            );
            return response()->json(['success' => true, 'msg' => 'Data Kesehatan disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function Prestasi(Request $request)
    {
        try {
            $prestasi = Prestasi::where([
                 ['periode_id', '=', $request->periode_id],
                ['rombel_id', '=', $request->rombel_id],
                ['siswa_id', '=', $request->siswa_id]
            ])->first();
            return response()->json(['success' => true, 'prestasi' => $prestasi], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function prestasiCreate(Request $request)
    {
        try {
            Prestasi::updateOrCreate(
                [
                    'periode_id' => $request->periode_id,
                    'rombel_id' => $request->rombel_id,
                    'siswa_id' => $request->siswa_id
                ],
                [
                    'akademik' => $request->akademik ?? null,
                    'olahraga' => $request->olahraga ?? null,
                    'kesenian' => $request->kesenian ?? null,
                ]
            );
            return response()->json(['success' => true, 'msg' => 'Data Prestasi disimpan.'], 200);
        } catch( \Exception $e)
        {
             return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function absensi(Request $request)
    {
         try {
            $absensi = Absensi::where([
                 ['periode_id', '=', $request->periode_id],
                ['rombel_id', '=', $request->rombel_id],
                ['siswa_id', '=', $request->siswa_id],
            ])->first();
            return response()->json(['success' => true, 'absensi' => $absensi], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

     public function absensiCreate(Request $request)
    {
        try {
            Absensi::updateOrCreate(
                 [
                    'periode_id' => $request->periode_id,
                    'rombel_id' => $request->rombel_id,
                    'siswa_id' => $request->siswa_id
                ],
                [
                    'a' => $request->a,
                    'i' => $request->i,
                    's' => $request->s,
                ]
            );
            return response()->json(['success' => true, 'msg' => 'Data Absensi disimpan'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        $model = 'App\Models\\' .$request->model;
        try {
            $model::find($request->id)->delete();
            return response()->json(['success' => true, 'msg' => 'Data Ekskul Siswa dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }


}
