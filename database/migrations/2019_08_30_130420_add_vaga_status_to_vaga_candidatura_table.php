<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVagaStatusToVagaCandidaturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaga_candidatura', function (Blueprint $table) {
            //
            $table->unsignedInteger('idvaga_status')->nullable();
            $table->foreign('idvaga_status')->references('id')->on('vaga_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['idvaga_status']);
        Schema::table('vaga_candidatura', function (Blueprint $table) {
            //
        });
    }
}
