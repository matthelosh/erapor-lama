<?php

namespace App\Providers;

use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter as GoogleDriveGoogleDriveAdapter;
use Illuminate\Support\ServiceProvider;
// use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Masbug\Flysystem\GoogleDriveAdapter;
use \Illuminate\Filesystem\FilesystemAdapter;


class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Storage::extend("google", function($app, $config) {
            $client = new \Google\Client();
            $client->setApplicationName('Drive-Rapor');
            $client->setClientId($config['clientId']);
            $client->setClientSecret($config['clientSecret']);
            $client->refreshToken($config['refreshToken']);
            
            // $client->setAuthConfig(env('GOOGLE_SERVICE_ACCOUNT_JSON_LOCATION'));
            // $client->setScopes(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/drive.file']);
            // $client->setSubject('matthelosh@sdn1-bedalisodo.sch.id');
            // $client->setAccessType('offline');
            $service = new \Google_Service_Drive($client);
            // $adapter = new GoogleDriveAdapter($service, '1RzdMyPY8hK_l49UUOqtcSIdKpBqR51IV');
            // $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, 'RaporDrive');
            // $driver = new \League\Flysystem\Filesystem($adapter);
            // $fs =  new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);

            $options =[];
            $adapter = new GoogleDriveGoogleDriveAdapter($service, $config['folderId']);

            
            
            return new Filesystem($adapter);
        });
    }

}
