<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToOrtus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ortus', function (Blueprint $table) {
            $table->enum('status_ayah', ['hidup', 'meninggal'])->default('hidup');
            $table->enum('status_ibu', ['hidup', 'meninggal'])->default('hidup');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ortus', function (Blueprint $table) {
            $table->dropColumn('status_ayah', 'status_ibu');
        });
    }
}
