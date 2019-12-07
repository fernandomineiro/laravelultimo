<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperadoraMedicoTable extends Migration
{
     /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'operadora_medico';

    /**
     * Run the migrations.
     * @table operadora_medico
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->dateTime('data_inclusão')->nullable();
            $table->dateTime('data_aprovacao')->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('operadora_idoperadora');
            $table->unsignedInteger('idmedico');

            $table->index(["operadora_idoperadora"], 'fk_operadora_medico_operadora1_idx');

            $table->index(["idmedico"], 'fk_operadora_medico_medico1_idx');


            $table->foreign('operadora_idoperadora', 'fk_operadora_medico_operadora1_idx')
                ->references('id')->on('operadora')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idmedico', 'fk_operadora_medico_medico1_idx')
                ->references('id')->on('medico')
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
