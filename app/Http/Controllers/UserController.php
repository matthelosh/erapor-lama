<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
        	$users = User::where([['level', '!=', 'admin']])->with('userable')->get();
        	return response()->json(['success' => true, 'users' => $users], 200);
        } catch ( \Exception $e ) {
            return response()->json([ 'success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 502);
        }
    }

    public function store(Request $request)
    {
		// $default_password = '12345';

		$upd_fields = [
			'username' => $request->username,
			'email' => $request->email,
			'level' => $request->level,
			'role' => $request->role,
			'is_active' => true
		];

		if ( $request->password != '' || $request->password != null ) {
			$upd_fields['password'] = Hash::make($request->password);
		}
    	try {
    		User::updateOrCreate(
    			[
    				'userid' => $request->userid,
    			],
    			$upd_fields
    		);
    		return response()->json([
    			'success' => true,
    			'msg' => 'Data Pengguna Disimpan'
    		], 200);
    		
    	} catch (\Exception $e) {
    		return response()->json([
    			'success' => false,
    			'msg' => $e->getCode().': '.$e->getMessage()
    		], 501);
    	}
		// dd($upd_fields);
    }

    public function grantSiswa(Request $request)
    {
    	try {
    		$siswas = $request->siswas;
    		foreach($siswas as $siswa )
    		{
    			User::updateOrCreate(
    				[
    					'userid' => $siswa['nisn'],
    					'level' => 'siswa',
    					'role' => 'siswa',
    					'userable_id' => $siswa['id'],
    					'userable_type' => 'App\Models\Siswa',
    					'is_active' => 1
    				],
    				[
    					'username' => $siswa['nisn'],
    					'password' => Hash::make((string)$siswa['tanggal_lahir']),
    					'email' => $siswa['nisn'].'@sdn1-bedalisodo.sch.id',
    				]

    			);
    		}
    		return response()->json(['success' => true, 'msg' => 'Akun siswa diaktifkan dengan username = NISN, password= Tanggal Lahir (Tahun-Bulan-Tanggal; contoh: 2000-02-19).'], 200);
    	} catch (\Exception $e) {
    		return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
    	}
    }
	public function grantGuru(Request $request)
    {
    	try {
    		$gurus = $request->gurus;
    		foreach($gurus as $guru )
    		{
    			$nama = explode(" ", $guru['name']);
    			User::updateOrCreate(
    				[
    					'userid' => $guru['nip'],
    					'level' => 'guru',
    					'role' => $guru['role'],
    					'userable_id' => $guru['id'],
    					'userable_type' => 'App\Models\Guru',
    					'is_active' => 1
    				],
    				[
    					'username' => strtolower($nama[0]),
    					'password' => Hash::make('12345'),
    					'email' => $guru['email'],
    				]

    			);
    		}
    		return response()->json(['success' => true, 'msg' => 'Akun guru diaktifkan dengan password default 12345.'], 200);
    	} catch (\Exception $e) {
    		return response()->json(['success' => false, 'msg' => $e->getMessage()], 500);
    	}
    }

    public function import(Request $request)
    {
    	try {
    		
    		$newusers = $request->users;
    		foreach($newusers as $user) 
    		{
    			User::updateOrCreate(
    				[
    					'nip' => $user['nip'],
						'username' => $user['username']
    				],
    				[
    					'name' => $user['nama'],
    					'email' => $user['email'],
    					'password' => Hash::make($user['password']),
    					'jk' => $user['jk'],
    					'alamat' => $user['alamat'],
    					'hp' => $user['hp'],
    					'level' => $user['level'],
    					'role' => $user['role'],
    					'status' => $user['status'] ?? 'gtt',
						'is_active' => true
    				]
    			);
    		}
    		return response()->json(['success' => true, 'msg' => 'Data Pengguna Berhasil Diimpor.'], 200);
    	} catch (\Exception $e) {
    		return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 501);
    	}
    }

    public function deleteMany(Request $request)
    {
    	try {
    		$users = $request->users;
    		foreach($users as $user) {
    			User::find($user['id'])->delete();
    		}
    		return response()->json(['success' => true, 'msg' => 'Data Beberapa Pengguna Berhasil Dihapus.'], 200);

    	} catch (\Exception $e) {
    		return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 501);
    	}
    }

	public function destroy(Request $request, $id)
	{
		try {
			User::find($id)->delete();
			return response()->json(['success' => true, 'msg' => 'Data Pengguna dihapus.'], 200);
		} catch ( \Exception $e )
		{
			return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 501);
		}
	}

	public function reset(Request $request, $id)
	{
		try {
			$password = $request->ttl ?? '12345';
			User::find($id)->update(['password' => Hash::make($password)]);
			return response()->json(['success' => true, 'msg' => 'Password telah direset.'], 200);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 501);
		}
	}
	public function resetMany(Request $request)
	{
		try {
			$users = $request->users;
			// dd($users);
			foreach($users as $user) {
				$password = $user['userable']['tanggal_lahir'] ?? '12345';
				User::find($user['id'])->update(['password' => Hash::make($password)]);
			}
			return response()->json(['success' => true, 'msg' => 'Password telah direset.'], 200);
		} catch (\Exception $e) {
			return response()->json(['success' => false, 'msg' => $e->getCode().':'.$e->getMessage()], 501);
		}
	}
	
}
