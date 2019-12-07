<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComunicacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comunicacao', function (Blueprint $table) {
            $table->increments('id');
            $table->text('mensagem', 400)->nullable();
            $table->dateTime('data')->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('idcomunicacao_tipo');
            $table->unsignedInteger('idprospect');

            $table->index(["idcomunicacao_tipo"], 'fk_comunicacao_comunicacao_tipo1_idx');
            $table->index(["idprospect"], 'fk_comunicacao_prospect1_idx');

            $table->foreign('idcomunicacao_tipo', 'fk_comunicacao_comunicacao_tipo1_idx')
                ->references('id')->on('comunicacao_tipo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idprospect', 'fk_comunicacao_prospect1_idx')
                ->references('id')->on('prospect')
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
        Schema::dropIfExists('comunicacao');
    }
}
