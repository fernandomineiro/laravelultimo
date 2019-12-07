<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusContratacaoHorasToPlantaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plantao', function (Blueprint $table) {
            //
            $table->unsignedInteger('idplantao_status');
            $table->unsignedInteger('idtipo_contratacao')->nullable();
            $table->string('hora_planejada', 10);
            $table->string('hora_realizada', 10)->nullable();
            
            $table->foreign('idtipo_contratacao')->references('id')->on('tipo_contratacao');
            $table->foreign('idplantao_status')->references('id')->on('plantao_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plantao', function (Blueprint $table) {
            //
        });
    }
}
