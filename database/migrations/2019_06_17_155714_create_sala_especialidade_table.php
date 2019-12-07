<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalaEspecialidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sala_especialidade', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idsala');
            $table->unsignedInteger('idespecialidade');

            $table->timestamps();

            $table->foreign('idsala')->references('id')->on('sala');
            $table->foreign('idespecialidade')->references('id')->on('especialidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sala_especialidade');
    }
}
