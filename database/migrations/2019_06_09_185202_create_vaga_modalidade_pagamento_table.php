<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVagaModalidadePagamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaga_modalidade_pagamento', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idmodalidade_pagamento');
            $table->unsignedInteger('idvaga');

            $table->timestamps();

            $table->foreign('idmodalidade_pagamento')->references('id')->on('modalidade_pagamento');
            $table->foreign('idvaga')->references('id')->on('vaga');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vaga_modalidade_pagamento');
    }
}
