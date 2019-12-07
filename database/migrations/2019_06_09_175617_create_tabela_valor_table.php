<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabelaValorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tabela_valor', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idoperadora');
            $table->unsignedInteger('idoperadora_unidade');
            $table->string('nome', 100);
            $table->string('descricao', 400);
            $table->datetime('expira')->nullable();
            $table->char('status', 1);
            
            $table->timestamps();
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
        Schema::dropIfExists('tabela_valor');
    }
}
