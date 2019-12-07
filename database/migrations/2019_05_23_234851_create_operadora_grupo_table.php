<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperadoraGrupoTable extends Migration
{
 /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'operadora_grupo';

    /**
     * Run the migrations.
     * @table operadora_grupo
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->string('descricao')->nullable();
            $table->unsignedInteger('idoperadora');
            $table->char('ativo', 1)->nullable();

            $table->index(["idoperadora"], 'fk_operadora_grupo_medico_operadora1_idx');


            $table->foreign('idoperadora', 'fk_operadora_grupo_medico_operadora1_idx')
                ->references('id')->on('operadora')
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
