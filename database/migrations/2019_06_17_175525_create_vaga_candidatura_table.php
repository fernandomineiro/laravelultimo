<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVagaCandidaturaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaga_candidatura', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idvaga');
            $table->unsignedInteger('idmedico');
            $table->string('tipo_contratacao_rpa', 100)->nullable();
            $table->string('tipo_contratacao_clt', 100)->nullable();
            $table->string('tipo_contratacao_pj'    , 100)->nullable();
            $table->char('ativo', 1);
            
            $table->timestamps();
            $table->foreign('idvaga')->references('id')->on('vaga');
            $table->foreign('idmedico')->references('id')->on('medico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaga_candidatura');
    }
}
