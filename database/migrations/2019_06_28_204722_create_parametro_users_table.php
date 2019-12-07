<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParametroUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parametro', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idoperadora');
            $table->unsignedInteger('idoperadora_unidade');
            $table->integer('confirmacao')->nullable();
            $table->integer('troca')->nullable();
            $table->integer('cancelamento')->nullable();
            $table->integer('disputa')->nullable();
            $table->integer('checkpoint')->nullable();
            $table->char('ativo', 1)->nullable();
            
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
        Schema::dropIfExists('parametro');
    }
}
