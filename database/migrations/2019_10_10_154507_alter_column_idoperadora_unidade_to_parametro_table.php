<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnIdoperadoraUnidadeToParametroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('parametro', function (Blueprint $table) {
            $table->unsignedInteger('idoperadora_unidade')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('parametro', function (Blueprint $table) {
            $table->unsignedInteger('idoperadora_unidade');
        });
    }
}
