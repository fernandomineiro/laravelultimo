<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoContatoTable extends Migration
{
     /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'tipo_contato';

    /**
     * Run the migrations.
     * @table tipo_contato
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->char('ativo', 1);
            $table->string('tipo', 15);
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
