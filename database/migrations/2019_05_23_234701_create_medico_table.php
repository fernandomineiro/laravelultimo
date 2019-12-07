<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicoTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'medico';

    /**
     * Run the migrations.
     * @table medico
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('crm', 20)->nullable()->unique();
            $table->string('crm_uf', 2)->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('idpessoa');

            $table->index(["idpessoa"], 'fk_medico_pessoa1_idx');


            $table->foreign('idpessoa', 'fk_medico_pessoa1_idx')
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
