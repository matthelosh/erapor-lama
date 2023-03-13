<?php

namespace App\Traits;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
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
		// $parameters['q'] = "mimeType='application/vnd.google-apps.folder'  and trashed=false";
		// $folders = Storage::disk('google')->directories();
		// dd($name);
		return Inertia::render(rtrim($dir, '/'), ['page' => end($name), 'page_title' => ucfirst(end($name))]);
	}
}