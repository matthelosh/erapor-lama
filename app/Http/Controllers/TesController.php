<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TesController extends Controller
{
    public function gdrive()
    {
        try {
            $metadata = new \Google\Service\Drive\DriveFile(array(
                'mimeType' => 'application/vnd.google-apps.folder',
                'name' => 'Mboh'
            ));
            $dirs = collect(Storage::disk('google')->listContents('', true));
            $dir = $dirs->where('type', '=', 'dir')->where('filename','=','Tes')->first();
            if (!$dir) {
                $mkdr = Storage::disk('google')->makeDirectory('Tes');
                dd($mkdr);
            } else {
                dd($dir);
            }           

            return response()->json(['success' => true, 'msg' => $msg], 200);
        } catch (\FilesystemException | \UnableToCheckExistance $th) {
            dd($th);
        }
    }

    // public function gdrive()
    // {
    //     try {
    //         $contents = Storage::disk('s3')->listContents('', true);
    //         return response()->json(['success' => true, 'msg' => $contents], 200);
    //     } catch (\Throwable $th) {
    //         dd($th);
    //     }
    // }
}
