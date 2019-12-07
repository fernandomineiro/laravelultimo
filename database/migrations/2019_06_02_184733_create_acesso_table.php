<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAcessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('acesso', function (Blueprint $table) {
            $table->increments('id');
            $table->string('acesso',45);
            $table->char('ativo',1);
            $table->unsignedInteger('idmodulo');
            $table->timestamps();

            $table->foreign('idmodulo', 'fk_acesso_modulo1_idx')
                ->references('id')->on('modulo')
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
        Schema::dropIfExists('acesso');
    }
}
