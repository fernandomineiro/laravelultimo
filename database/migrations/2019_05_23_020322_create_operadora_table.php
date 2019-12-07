<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOperadoraTable extends Migration
{
     /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'operadora';

    /**
     * Run the migrations.
     * @table operadora
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('logotipo')->nullable();
            $table->string('seo_url')->nullable();
            $table->unsignedInteger('idpessoa');
            $table->char('ativo', 1)->nullable();

            $table->index(["idpessoa"], 'fk_operadora_pessoa1_idx');


            $table->foreign('idpessoa', 'fk_operadora_pessoa1_idx')
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
