<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
// use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use League\Flysystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Masbug\Flysystem\GoogleDriveAdapter;


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
            $client->setAuthConfig(dir('./credentials.json'));
            $client->setScopes('https://www.googleapis.com/auth/drive');
            // $client->setSubject('matthelosh@sdn1-bedalisodo.sch.id');
            $client->setAccessType('offline');
            $service = new \Google\Service\Drive($client);
            // $adapter = new GoogleDriveAdapter($service, '1RzdMyPY8hK_l49UUOqtcSIdKpBqR51IV');
            $adapter = new \Masbug\Flysystem\GoogleDriveAdapter($service, 'RaporDrive');
            $driver = new \League\Flysystem\Filesystem($adapter);
            $fs =  new \Illuminate\Filesystem\FilesystemAdapter($driver, $adapter);
            return $fs;
        });
    }

}
