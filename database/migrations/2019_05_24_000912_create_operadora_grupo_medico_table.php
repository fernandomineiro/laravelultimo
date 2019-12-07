<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperadoraGrupoMedicoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'operadora_grupo_medico';

    /**
     * Run the migrations.
     * @table operadora_grupo_medico
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->dateTime('data_inclusao')->nullable();
            $table->dateTime('data_aprovacao')->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('idoperadora_grupo_medico');
            $table->unsignedInteger('idmedico');

            $table->index(["idoperadora_grupo_medico"], 'fk_operadora_grupo_medico_operadora_grupo1_idx');

            $table->index(["idmedico"], 'fk_operadora_grupo_medico_medico1_idx');


            $table->foreign('idoperadora_grupo_medico', 'fk_operadora_grupo_medico_operadora_grupo1_idx')
                ->references('id')->on('operadora_grupo')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idmedico', 'fk_operadora_grupo_medico_medico1_idx')
                ->references('id')->on('medico')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }
}
