<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCidadeTable extends Migration
{
    
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
      /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'cidade';

    /**
     * Run the migrations.
     * @table cidade
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('idestado');
            $table->string('cidade', 25);

            $table->index(["idestado"], 'fk_cidade_estado1_idx');


            $table->foreign('idestado', 'fk_cidade_estado1_idx')
                ->references('id')->on('estado')
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
