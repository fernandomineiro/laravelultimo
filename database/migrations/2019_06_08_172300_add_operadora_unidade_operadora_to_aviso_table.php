<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOperadoraUnidadeOperadoraToAvisoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('aviso', function (Blueprint $table) {
            //Adicionar campo referente a operadora e Unidade da Operadora a ser apresentado o aviso
            $table->unsignedInteger('idoperadora')->nullable();
            $table->unsignedInteger('idoperadora_unidade')->nullable();

            $table->foreign('idoperadora')->references('id')->on('operadora');
            $table->foreign('idoperadora_unidade')->references('id')->on('operadora_unidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('aviso', function (Blueprint $table) {
            //
            $table->dropForeign(['idoperadora']);
            $table->dropForeign(['idoperadora_unidade']);
            $table->dropColumn(['idoperadora', 'idoperadora_unidade']);            
        });
    }
}
