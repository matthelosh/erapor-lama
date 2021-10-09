<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       // $this->call([
       //      UserSeeder::class,
       //      AngkatanSeeder::class,
       //      KelasSeeder::class,
       //      MenuSeeder::class,
       //      PeriodeSeeder::class,
            // RoleSeeder::class,
       //      MapelSeeder::class
       // ]);


        $files = ['angkatans','ekskuls','kds','kelas_mapel','kelas','mapels','menu_role','menus','pemetaans', 'periodes','prosems','roles','subtemas','temas'];
         // $db_bin = '/usr/bin/mysql';
         $db_bin = 'mysql';
         $db = [
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE')
        ];
        for($i=0;$i<count($files);$i++) {
            $path = public_path('files/'.$files[$i].'.sql');
            // $sql = file_get_contents($path);
            // DB::unprepared($sql);
            
           exec("{$db_bin} --user={$db['username']} --password={$db['password']} --host={$db['host']} --database {$db['database']} < $path");
        }

       DB::table('status')->insert(['ok' => 1]);
       $this->call([UserSeeder::class,]);
    }
}
