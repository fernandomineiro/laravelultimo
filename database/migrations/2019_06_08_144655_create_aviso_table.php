<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvisoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('aviso', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mensagem', 255)->nullable();
            $table->string('url', 500)->nullable();
            $table->char('visivel', 1)->nullable();
            $table->char('ativo', 1)->nullable();
            $table->dateTime('data_hora_abertura')->nullable();
            $table->dateTime('data_hora_encerramento')->nullable();
            $table->unsignedInteger('idmodulo');
            $table->unsignedInteger('idoperadora_grupo_medico')->nullable();
            $table->timestamps();

            $table->foreign('idmodulo', 'fk_aviso_idmodulo1_idx')
                ->references('id')->on('modulo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idoperadora_grupo_medico', 'fk_aviso_operadora_grupo_medico1_idx')
                ->references('id')->on('operadora_grupo_medico')
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
        Schema::dropIfExists('aviso');
    }
}
