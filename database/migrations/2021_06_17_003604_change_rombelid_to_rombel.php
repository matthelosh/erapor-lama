<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRombelidToRombel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rombel_siswa', function (Blueprint $table) {
            $table->renameColumn('periode_id', 'periode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rombel_siswa', function (Blueprint $table) {
            $table->renameColumn('periode', 'periode_id');
        });
    }
}
