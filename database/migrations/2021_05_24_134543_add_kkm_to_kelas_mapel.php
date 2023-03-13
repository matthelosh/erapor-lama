<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKkmToKelasMapel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelas_mapel', function (Blueprint $table) {
            $table->integer('kkm')->default(75);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelas_mapel', function (Blueprint $table) {
            $table->dropColumn('kkm');
        });
    }
}
