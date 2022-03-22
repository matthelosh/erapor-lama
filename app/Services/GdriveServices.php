<?php

namespace App\Services;

use Attribute;
use Google_Client;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Masbug\Flysystem\GoogleDriveAdapter;
use League\Flysystem\AdapterInterface;

class GdriveServices {

    public function createFolder( $folder)
    {
        
    }

    public function upload($file, $name)
    {
        $names = explode('-', $name);
        $folder = $this->createFolder($names[1].'-'.$names[2]);
        
        
        dd(Storage::disk('google')->listContents('', true));
        
    }

    public function listContents()
    {
        // $contents = Storage::disk('google')->listContents();
        // return $contents;
    }
}