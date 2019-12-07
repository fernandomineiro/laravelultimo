<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerfilAcessoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfil_acesso', function (Blueprint $table) {
            $table->increments('id');
            $table->char('visualizacao', 1)->nullable();
            $table->char('cadastro', 1)->nullable();
            $table->char('edicao', 1)->nullable();
            $table->char('exclusao', 1)->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('idacesso');
            $table->unsignedInteger('idperfil')->nullable();

            $table->timestamps();

            $table->foreign('idacesso', 'fk_perfil_acesso_acesso1_idx')
                ->references('id')->on('acesso')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idperfil', 'fk_perfil_acesso_perfil1_idx')
                ->references('id')->on('perfil')
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
        Schema::dropIfExists('perfil_acesso');
    }
}
