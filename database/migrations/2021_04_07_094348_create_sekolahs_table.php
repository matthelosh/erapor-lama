<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSekolahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('npsn', 30)->unique();
            $table->string('nama_sekolah', 100);
            $table->string('alamat');
            $table->string('desa');
            $table->string('kec');
            $table->string('kab');
            $table->string('prov');
            $table->string('kode_pos');
            $table->string('telp');
            $table->string('email');
            $table->string('website');
            $table->string('ks');
            $table->string('operator');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sekolahs');
    }
}
