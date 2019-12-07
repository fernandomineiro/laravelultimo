<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plantao', function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('idvaga');
            $table->unsignedInteger('idmedico')->nullable();
            $table->datetime('check_in')->nullable();
            $table->datetime('check_out')->nullable();
            $table->integer('check_in_modo')->nullable();
            $table->integer('check_out_modo')->nullable();
            $table->integer('minutos_inf')->nullable();
            $table->integer('minutos_remunerados')->nullable();
            $table->decimal('valor_previsto', 10, 2)->nullable();
            $table->decimal('valor_remunerado', 10, 2)->nullable();
            $table->datetime('data_encerramento')->nullable();
            $table->decimal('latidude_check_in', 20, 14)->nullable();
            $table->decimal('Longetude_check_in', 20, 14)->nullable();
            $table->decimal('latidude_check_out', 20, 14)->nullable();            
            $table->decimal('Longetude_check_out', 20, 14)->nullable();
            $table->datetime('data_inicio')->nullable();
            $table->datetime('data_termino')->nullable();

            
            $table->timestamps();
            $table->foreign('idvaga')->references('id')->on('vaga');
            $table->foreign('idmedico')->references('id')->on('medico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plantao');
    }
}
