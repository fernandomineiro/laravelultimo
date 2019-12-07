<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBairroTable extends Migration
{
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
     /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'bairro';

    /**
     * Run the migrations.
     * @table bairro
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('idcidade');
            $table->string('bairro', 25);

            $table->index(["idcidade"], 'fk_bairro_cidade1_idx');


            $table->foreign('idcidade', 'fk_bairro_cidade1_idx')
                ->references('id')->on('cidade')
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
