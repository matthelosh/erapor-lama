<?php

namespace App\Services;

use Attribute;
use Google\Service\Drive\Resource\Files;
use Google_Client;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Masbug\Flysystem\GoogleDriveAdapter;
use League\Flysystem\AdapterInterface;

class GdriveServices {

    public function createFolder( $folder)
    {
        // Check if $folder exists
        // $dirs = collect(Storage::disk('google')->listContents('', true));
        $exists = $this->checkFolder($folder);

        if (!$exists) {
            $newDir = Storage::disk('google')->makeDirectory($folder, 'id');
            if($newDir) $dir = $this->checkFolder($folder);
            return $dir['path'];
        } else {
            return $exists['path'];
        }

    }

    public function checkFolder($folder)
    {
        $dirs = collect(Storage::disk('google')->listContents('', true));
        $exists = $dirs->where('type','=','dir')->where('filename','=', $folder )->first();
        return $exists;
    }

    public function checkFile($path, $filename)
    {
        try {
            $files = collect(Storage::disk('google')->listContents($path, false));
            $exists = $files->where('type','=','file')->where('name','=',$filename)->first();
            if ($exists) {
                Storage::disk('google')->delete($exists['path']);
                return true;
            }
        } catch (\FilesystemException | \UnableToCheckExistance $th) {
            dd($th);
        }
    }

    public function upload($file, $name)
    {
        try {
            $names = explode('-', $name);
            $folder = $this->createFolder($names[1].'-'.$names[2]);
            $filename = $names[0].'-'.$names[3];
            $delExist = $this->checkFile($folder, $filename);
            // if($delExist) {
            $save = Storage::disk('google')->put($folder.'/'.$filename, $file);
            return $save;
            // } else {
            //     $save = Storage::disk('google')->put($folder.'/'.$filename, $file);
            //     return $save;
            // }
            
            
            
        
        } catch (\FilesystemException | \UnableToCheckExistance $th) {
            dd($th);
        }
    }

    public function listContents()
    {
        // $contents = Storage::disk('google')->listContents();
        // return $contents;
    }
}