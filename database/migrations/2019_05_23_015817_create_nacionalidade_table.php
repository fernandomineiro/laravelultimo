<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNacionalidadeTable extends Migration
{
    
    public function boot()
    {
        Schema::defaultStringLength(191);
    }
     /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'nacionalidade';

    /**
     * Run the migrations.
     * @table nacionalidade
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('nacionalidade', 45);
            $table->char('ativo', 1);
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
