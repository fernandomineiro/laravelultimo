<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVagaTipoContratacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaga_tipo_contratacao', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idtipo_contratacao');
            $table->unsignedInteger('idvaga');

            $table->timestamps();

            $table->foreign('idtipo_contratacao')->references('id')->on('tipo_contratacao');
            $table->foreign('idvaga')->references('id')->on('vaga');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaga_tipo_contratacao');
    }
}
