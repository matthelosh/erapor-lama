<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promes', function (Blueprint $table) {
            $table->id();
            $table->string('kelas');
            $table->string('semester');
            $table->boolean('tematik')->default(1);
            $table->string('tema_id')->nullable();
            $table->string('subtema_id')->nullable();
            $table->string('mapel_id');
            $table->string('kd_id');
            $table->string('bulan');
            $table->string('minggu');
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
        Schema::dropIfExists('promes');
    }
}
