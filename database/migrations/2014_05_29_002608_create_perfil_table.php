<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerfilTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'perfil';

    /**
     * Run the migrations.
     * @table Perfil
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nome', 45)->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('idmodulo');

            $table->index(["idmodulo"], 'fk_Perfil_Modulo1_idx');


            $table->foreign('idmodulo', 'fk_Perfil_Modulo1_idx')
                ->references('id')->on('modulo')
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
