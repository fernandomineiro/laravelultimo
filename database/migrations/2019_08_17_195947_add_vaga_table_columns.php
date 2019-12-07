<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVagaTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaga', function (Blueprint $table) {

            $table->char('recorrencia', 1)->nullable();
            $table->dateTime('recorrencia_fim');
            $table->tinyInteger('possivel_clt')->nullable();

        });

        Schema::create('vaga_recorrencia', function (Blueprint $table){

            $table->increments('id');
            $table->unsignedInteger('idvaga');
            $table->tinyInteger('domingo');
            $table->tinyInteger('segunda');
            $table->tinyInteger('terca');
            $table->tinyInteger('quarta');
            $table->tinyInteger('quinta');
            $table->tinyInteger('sexta');
            $table->tinyInteger('sabado');

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
        Schema::table('vaga', function (Blueprint $table) {
            //
        });
    }
}
