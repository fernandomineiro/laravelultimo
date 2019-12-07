<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContatoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'contato';

    /**
     * Run the migrations.
     * @table contato
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('idpessoa');
            $table->unsignedInteger('idtipo_contato');
            $table->char('ativo', 1)->nullable();
            $table->char('principal', 1)->nullable();
            $table->text('observacao')->nullable();
            $table->string('contato', 70)->nullable();

            $table->index(["idpessoa"], 'fk_contato_pessoa1_idx');

            $table->index(["idtipo_contato"], 'fk_contato_tipo_contato1_idx');


            $table->foreign('idpessoa', 'fk_contato_pessoa1_idx')
                ->references('id')->on('pessoa')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtipo_contato', 'fk_contato_tipo_contato1_idx')
                ->references('id')->on('tipo_contato')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }
}
