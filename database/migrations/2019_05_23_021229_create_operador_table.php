<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperadorTable extends Migration
{
   /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'operador';

    /**
     * Run the migrations.
     * @table operador
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('ativo', 1)->nullable();
            $table->string('telefone1', 45)->nullable();
            $table->string('ramal1', 45)->nullable();
            $table->string('telefone2', 45)->nullable();
            $table->string('ramal2', 45)->nullable();
            $table->string('telefone3', 45)->nullable();
            $table->string('ramal3', 45)->nullable();
            $table->unsignedInteger('idoperadora');
            $table->unsignedInteger('idpessoa');
            $table->unsignedBigInteger('user_id');

            $table->index(["idpessoa"], 'fk_operador_pessoa1_idx');

            $table->index(["idoperadora"], 'fk_operador_operadora1_idx');

            $table->index(["user_id"], 'fk_operador_user1_idx');

            $table->foreign('idoperadora', 'fk_operador_operadora1_idx')
                ->references('id')->on('operadora')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idpessoa', 'fk_operador_pessoa1_idx')
                ->references('id')->on('pessoa')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('user_id', 'fk_operador_user1_idx')
              ->references('id')->on('users')
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
