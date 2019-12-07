<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProspectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prospect', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome', 255)->nullable();
            $table->string('apelido', 100)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('telefone1', 45)->nullable();
            $table->string('ramal1', 45)->nullable();
            $table->string('telefone2', 45)->nullable();
            $table->string('ramal2', 45)->nullable();
            $table->string('descricao', 400)->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('idstatus_prospect');

            $table->index(["idstatus_prospect"], 'fk_prospect_status_prospect1_idx');

            $table->foreign('idstatus_prospect', 'fk_prospect_status_prospect1_idx')
                ->references('id')->on('status_prospect')
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
        Schema::dropIfExists('prospect');
    }
}
