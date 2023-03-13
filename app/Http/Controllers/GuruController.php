<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $gurus = Guru::where('nip','<>','001')->with('user')->get();
            return response()->json(['success' => true, 'gurus' => $gurus], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function import(Request $request)
    {
        try {
            
            $newgurus = $request->gurus;
            foreach($newgurus as $guru) 
            {
                Guru::updateOrCreate(
                    [
                        'nip' => $guru['nip'],
                    ],
                    [
                        'name' => $guru['nama'],
                        'email' => $guru['email'],
                        'jk' => $guru['jk'],
                        'alamat' => $guru['alamat'],
                        'hp' => $guru['hp'],
                        'role' => $guru['role'],
                        'status' => $guru['status'] ?? 'gtt',
                        'active' => true
                    ]
                );
            }
            return response()->json(['success' => true, 'msg' => 'Data Guru Berhasil Diimpor.'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 501);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $default_password = '12345';

        $upd_fields = [
            'nip' => $request->nip,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'jk' => $request->jk,
            'status' => $request->status,
            'hp' => $request->hp,
            'alamat' => $request->alamat,
            'active' => true
        ];

        try {
            Guru::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                $upd_fields
            );
            return response()->json([
                'success' => true,
                'msg' => 'Data Guru Disimpan'
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'msg' => $e->getCode().': '.$e->getMessage()
            ], 501);
        }
        // dd($upd_fields);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Guru  $guru
     * @return \Illuminate\Http\Response
     */
    public function show(Guru $guru)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Guru  $guru
     * @return \Illuminate\Http\Response
     */
    public function edit(Guru $guru)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Guru  $guru
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guru $guru)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Guru  $guru
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            $guru = Guru::find($id);
            $user = 'App\Models\User'::where('userid', $guru->nip)->delete();
            $guru->delete();
            return response()->json(['success' => true, 'msg' => 'Data Guru ' . $guru->name . ' dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    public function deleteMany(Request $request)
    {
        try {
            $gurus = $request->gurus;
            foreach($gurus as $guru)
            {
                $guru = Guru::find($guru['id']);
                $user = 'App\Models\User'::where('userid', $guru->nip)->delete();
                $guru->delete();
            }
            return response()->json(['success' => true, 'msg' => 'Data Guru dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
        }
    }
}
