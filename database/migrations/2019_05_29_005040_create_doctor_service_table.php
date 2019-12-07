<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDoctorServiceTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'doctor_service';

    /**
     * Run the migrations.
     * @table doctor_service
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->dateTime('data_admissao')->nullable();
            $table->dateTime('data_inativacao')->nullable();
            $table->char('ativo', 1)->nullable();
            $table->unsignedInteger('idpessoa');
            $table->unsignedBigInteger('iduser');

            $table->index(["iduser"], 'fk_doctor_service_user1_idx');

            $table->index(["idpessoa"], 'fk_doctor_service_pessoa1_idx');


            $table->foreign('idpessoa', 'fk_doctor_service_pessoa1_idx')
                ->references('id')->on('pessoa')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('iduser', 'fk_doctor_service_user1_idx')
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
