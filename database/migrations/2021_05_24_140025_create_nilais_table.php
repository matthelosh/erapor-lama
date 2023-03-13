<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNilaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->string('periode_id');
            $table->string('semester');
            $table->string('rombel_id');
            $table->string('mapel_id');
            $table->string('mid');
            $table->string('siswa_id');
            $table->string('kd_id');
            $table->string('jenis');
            $table->string('aspek');
            $table->string('teknik');
            $table->float('nilai');
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
        Schema::dropIfExists('nilais');
    }
}
