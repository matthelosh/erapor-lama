<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('gurus')->insert([
            'nip' => '001', 
            'name' => 'Administrator', 
            'email' => 'admin@ais.com', 
            'hp' => '+6200000000', 
            'jk' => 'l', 
            'role' => 'admin', 
            'status' => 'gtt', 
            'active' => true, 
            'alamat' => 'Malang' 
        ]);

        DB::table('users')->insert([
            'userid' => '001',
            'username' => 'admin',
            'email' => 'admin@ais.com',
            'password' => Hash::make('123'),
            'level' => 'admin',
            'role' => 'admin',
            'userable_id' => 1,
            'userable_type' => 'App\Models\Guru'
        ]);
    }
}
