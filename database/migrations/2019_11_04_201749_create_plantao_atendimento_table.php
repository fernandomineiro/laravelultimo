<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlantaoAtendimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plantao_atendimento', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idplantao');
            $table->unsignedInteger('idconvenio')->nullable();
            $table->integer('quantidadeAtendimento')->nullable();
            $table->decimal('valor_previsto', 10, 2)->nullable();
            $table->decimal('valor_remunerado', 10, 2)->nullable();
            $table->datetime('data_hora_inicio')->nullable();
            $table->datetime('data_hora_termino')->nullable();

            $table->foreign('idplantao')->references('id')->on('plantao');
            $table->foreign('idconvenio')->references('id')->on('convenio');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plantao_atendimento');
    }
}
