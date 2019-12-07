<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstadoTable extends Migration
{
    
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
     /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'estado';

    /**
     * Run the migrations.
     * @table estado
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('idpais');
            $table->string('estado', 20);

            $table->index(["idpais"], 'fk_estado_pais1_idx');


            $table->foreign('idpais', 'fk_estado_pais1_idx')
                ->references('id')->on('pais')
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
       Schema::dropIfExists($this->tableName);
     }
}
