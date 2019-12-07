<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperadoraUnidadeTable extends Migration
{
     /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'operadora_unidade';

    /**
     * Run the migrations.
     * @table operadora_unidade
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('seo')->nullable();
            $table->unsignedInteger('idoperadora');
            $table->string('telefone', 45)->nullable();
            $table->string('Endereco')->nullable();
            $table->string('bairro')->nullable();
            $table->string('cep', 10)->nullable();
            $table->string('cidade', 150)->nullable();
            $table->string('UF', 2)->nullable();
            $table->string('pais', 150)->nullable();
            $table->char('ativo', 1)->nullable();

            $table->index(["idoperadora"], 'fk_operadora_unidade_operadora1_idx');


            $table->foreign('idoperadora', 'fk_operadora_unidade_operadora1_idx')
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
