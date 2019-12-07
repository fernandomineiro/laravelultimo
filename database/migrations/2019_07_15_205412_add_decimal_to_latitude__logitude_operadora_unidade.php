<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDecimalToLatitudeLogitudeOperadoraUnidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('operadora_unidade', function (Blueprint $table) {
            //
            $table->decimal('latitude', 20, 14)->nullable()->change();
            $table->decimal('longitude', 20, 14)->nullable()->change();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('operadora_unidade', function (Blueprint $table) {
            //
            $table->dropColumn('longitude');
            $table->dropColumn('latitude');
        });
    }
}
