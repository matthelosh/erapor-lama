<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isGuru
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ( $request->user()->level == 'guru' ) {
            return $next($request);
        } else {
            return back()->with(['success' => false, 'message' => 'Laman tersebut hanya boleh diakses Guru'], 403);
        }
    }
}
