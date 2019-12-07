<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnIdvagaTipoContratacaoEfetivadaToVagaTipoContratacaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vaga_tipo_contratacao', function (Blueprint $table) {
            $table->unsignedInteger('idvaga_tipo_contratacao_efetivada')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vaga_tipo_contratacao', function (Blueprint $table) {
            $table->dropForeign('idvaga_tipo_contratacao_efetivada');
            $table->dropColumn('idvaga_tipo_contratacao_efetivada');
        });
    }
}
