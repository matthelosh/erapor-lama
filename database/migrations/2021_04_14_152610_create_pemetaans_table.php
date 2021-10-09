<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePemetaansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pemetaans', function (Blueprint $table) {
            $table->id();
            $table->string('kelas_id');
            $table->string('tema_id');
            $table->string('subtema_id');
            $table->string('mapel_id');
            $table->string('kd_id');
            $table->string('semester', 1);
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
        Schema::dropIfExists('pemetaans');
    }
}
