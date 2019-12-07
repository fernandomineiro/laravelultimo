<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOperadoraUnidadeToOperadorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operador', function (Blueprint $table) {
            //
            $table->unsignedInteger('idoperador_unidade')->nullable();
           
            $table->foreign('idoperador_unidade')->references('id')->on('operadora_unidade');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operador', function (Blueprint $table) {
            //
            $table->dropForeign(['idoperador_unidade']);
            $table->dropColumn(['idoperador_unidade']);
        });
    }
}
