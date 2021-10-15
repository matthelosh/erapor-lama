<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

trait AdminPage
{
	public function adminPage($request)
	{
		// dd(url()->current());
		$name = explode('.', Route::currentRoutename());
		$dir = array_reduce($name, function($a, $c) {
			return $a .= ucfirst($c).'/';
		});
		// dd($name);
		return Inertia::render(rtrim($dir, '/'), ['page' => end($name), 'page_title' => ucfirst(end($name))]);
	}
}