<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sala', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idoperadora_unidade');
            $table->string('nome', 100)->nullable();
            $table->string('descricao', 400)->nullable();
            $table->string('cor_rgb', 7)->nullable();
            $table->char('ativo', 1)->nullable();
            
            $table->timestamps();

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
        Schema::dropIfExists('sala');
    }
}
