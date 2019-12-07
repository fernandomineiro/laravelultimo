<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperadoraUnidadeFotoTable extends Migration
{
     /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'operadora_unidade_foto';

    /**
     * Run the migrations.
     * @table operadora_unidade_foto
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('legenda')->nullable();
            $table->string('arquivo')->nullable();
            $table->integer('ordem')->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('idoperadora_unidade');

            $table->index(["idoperadora_unidade"], 'fk_operadora_unidade_foto_operadora_unidade1_idx');


            $table->foreign('idoperadora_unidade', 'fk_operadora_unidade_foto_operadora_unidade1_idx')
                ->references('id')->on('operadora_unidade')
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
