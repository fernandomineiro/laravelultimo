<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('valor', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idtabela_valor');
            $table->unsignedInteger('idconvenio');
            $table->unsignedInteger('idespecialidade');
            $table->decimal('valor_rpa', 10, 2)->nullable();
            $table->decimal('valor_clt', 10, 2)->nullable();
            $table->decimal('valor_pj', 10, 2)->nullable();            
            $table->char('ativo', 1);
            
            $table->timestamps();
            $table->foreign('idtabela_valor')->references('id')->on('tabela_valor');
            $table->foreign('idconvenio')->references('id')->on('convenio');
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
        Schema::dropIfExists('valor');
    }
}
