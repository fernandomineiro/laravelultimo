<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeriadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feriados', function (Blueprint $table) {

            $table->increments('id');
            $table->string('nome', 100)->nullable();
            $table->integer('dia')->nullable();
            $table->integer('mes')->nullable();
            $table->integer('ano')->nullable();
            $table->integer('dia_semana')->nullable();
            $table->integer('num_semana')->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('idestado')->nullable();
            $table->timestamps();

            $table->foreign('idestado', 'fk_feriado_estado1_idx')
                ->references('id')->on('estado')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('feriados');
    }
}
