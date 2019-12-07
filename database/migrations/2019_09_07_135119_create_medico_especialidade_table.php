<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicoEspecialidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medico_especialidade', function (Blueprint $table) {
            
            $table->increments('id');
            $table->unsignedInteger('idmedico');
            $table->unsignedInteger('idespecialidade');
            $table->unsignedInteger('idinstituicao');
            $table->dateTime('data_inicio')->nullable();
            $table->dateTime('data_conclusao')->nullable();
            $table->char('ativo', 1)->nullable();
            
            $table->timestamps();

            $table->foreign('idmedico')->references('id')->on('medico');
            $table->foreign('idespecialidade')->references('id')->on('especialidade');
            $table->foreign('idinstituicao')->references('id')->on('instituicao');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('medico_especialidade');
    }
}
