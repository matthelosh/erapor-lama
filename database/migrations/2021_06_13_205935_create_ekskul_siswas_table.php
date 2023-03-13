<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEkskulSiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ekskul_siswas', function (Blueprint $table) {
            $table->id();
            $table->string('periode_id');
            $table->string('rombel_id');
            $table->string('ekskul_id');
            $table->string('siswa_id');
            $table->string('ket');
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
        Schema::dropIfExists('ekskul_siswas');
    }
}
