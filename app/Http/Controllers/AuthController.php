<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\AccessLog;
use App\Models\User;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Carbon;
class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');
            $login = Auth::attempt($credentials);
            if ($login) {
                $agent = new Agent();
                Session::put('periode', $this->periode_aktif()->kode_periode);
                $usr = $this->user($request->username);
                $usr->last_seen_at = Carbon::now()->format('Y-m-d H:i:s');
                $usr->save();
                $request->session()->put(['user' => $usr]);
                AccessLog::create(['user_id' => $usr->userid, 'os' => $agent->platform(), 'browser' => $agent->browser(), 'ip' => $request->ip()]);
                return response()->json(['success' => true, 'role' => $usr->role], 200);
            } else {
                $user_count = User::count();
                $msg = 'Username atau Sandi yang Anda Masukkan tidak sesuai.';
                $msg = ($user_count < 1 ) ? 'Belum ada pengguna. Silahkan Hubungi Admin!' : 'Username atau Sandi yang Anda Masukkan tidak sesuai.';
                
                return response()->json(['success' => false, 'old' => $credentials, 'msg' => $msg], 403);
            }
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'msg' => $e->getMessage(), 'code' => $e->getCode()], 500);
        }
    }

    public function periode_aktif()
    {
        try {
            $periode_aktif = 'App\Models\Periode'::where('active', true)->first();
            return $periode_aktif;
        } catch (\Exception $e) {
            dd($e);
        }
    }

    public function logout(Request $request)
    {
       Auth::logout();
       $request->session()->invalidate();
       $request->session()->regenerateToken();
       return redirect('/');
    }

    public function user($username)
    {
        try {
          return User::where('username', $username)->first();
      } catch (\Exception $e) {
        dd($e);
      }
    }
}
