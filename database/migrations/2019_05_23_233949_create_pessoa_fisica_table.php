<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoaFisicaTable extends Migration
{
     /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'pessoa_fisica';

    /**
     * Run the migrations.
     * @table pessoa_fisica
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idpessoa_fisica');
            $table->unsignedInteger('idpessoa');
            $table->unsignedInteger('idestado_civil');
            $table->string('nome', 75);
            $table->char('ativo', 1);
            $table->string('cpf', 15);
            $table->string('rg', 45)->nullable();
            $table->date('data_expedicao_rg')->nullable();
            $table->string('orgao_emissor_rg', 15)->nullable();
            $table->string('cnh', 15)->nullable();
            $table->string('categoria_cnh', 5)->nullable();
            $table->date('validade_cnh')->nullable();
            $table->char('sexo', 1);
            $table->string('titulo_eleitoral', 15)->nullable();
            $table->string('zona_titulo_eleitoral', 5)->nullable();
            $table->string('secao_titulo_eleitoral', 5)->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('pai', 100)->nullable();
            $table->string('mae', 100)->nullable();
            $table->string('naturalidade')->nullable();
            $table->string('naturalidade_uf', 2)->nullable();
            $table->unsignedInteger('idnacionalidade');


            $table->index(["idpessoa"], 'fk_pessoa_fisica_pessoa1_idx');

            $table->index(["idestado_civil"], 'fk_pessoa_fisica_estado_civil1_idx');

            $table->unique(["cpf"], 'cpf_UNIQUE');


            $table->foreign('idpessoa', 'fk_pessoa_fisica_pessoa1_idx')
                ->references('id')->on('pessoa')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idestado_civil', 'fk_pessoa_fisica_estado_civil1_idx')
                ->references('id')->on('estado_civil')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idnacionalidade', 'fk_pessoa_nacionalidade1_idx')
                ->references('id')->on('nacionalidade')
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
