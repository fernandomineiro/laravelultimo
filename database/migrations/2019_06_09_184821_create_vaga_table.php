<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVagaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaga', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idespecialidade');
            $table->unsignedInteger('idsala');
            //$table->unsignedInteger('idvaga_tipo_contratacao_efetivada')->nullable();
            $table->unsignedInteger('idvaga_status');
            $table->unsignedInteger('idtabela_valor');


            $table->datetime('data_inicio');
            $table->datetime('data_final');
            $table->datetime('data_criacao');
            $table->decimal('bonus', 10, 2)->nullable();
            $table->string('observacao', 500)->nullable();
            $table->char('visibilidade', 1);
            $table->decimal('valor_hora', 10, 2)->nullable();
            $table->decimal('valor_consulta', 10, 2)->nullable();
            $table->char('ativo', 1);
            
            $table->timestamps();
            
            $table->foreign('idespecialidade')->references('id')->on('especialidade');
            $table->foreign('idsala')->references('id')->on('sala');
            $table->foreign('idvaga_status')->references('id')->on('vaga_status');
            //$table->foreign('idvaga_tipo_contratacao_efetivada')->references('id')->on('vaga_tipo_contratacao');
            $table->foreign('idtabela_valor')->references('id')->on('tabela_valor');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaga');
    }
}
