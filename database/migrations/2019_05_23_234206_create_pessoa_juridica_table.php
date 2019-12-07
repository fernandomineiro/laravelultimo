<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePessoaJuridicaTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'pessoa_juridica';

    /**
     * Run the migrations.
     * @table pessoa_juridica
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('idpessoa_juridica');
            $table->unsignedInteger('idpessoa');
            $table->string('razao_social', 75);
            $table->string('nome_fantasia', 100);
            $table->string('cnpj', 20);
            $table->string('inscricao_estadual', 25)->nullable();
            $table->string('inscricao_municipal', 25)->nullable();
            $table->string('inscricao_junta', 25)->nullable();
            $table->date('data_inscricao_junta')->nullable();
            $table->string('cei', 45)->nullable();
            $table->string('cnae', 45)->nullable();
            $table->char('ativo', 1)->nullable();

            $table->index(["idpessoa"], 'fk_pessoa_juridica_pessoa1_idx');

            $table->unique(["cnpj"], 'cnpj_UNIQUE');

            $table->foreign('idpessoa', 'fk_pessoa_juridica_pessoa1_idx')
                ->references('id')->on('pessoa')
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
