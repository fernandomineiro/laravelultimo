<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /*
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        
        Schema::defaultStringLength(191);
        
        Schema::create('users', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->smallInteger('status')->default('10')->nullable();
            $table->string('apelido', 45)->nullable();
            $table->string('foto')->nullable();
            $table->char('ativo', 1)->nullable();
            $table->dateTime('ultimo_login')->nullable();
            $table->unsignedInteger('idperfil')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->index(["idperfil"], 'fk_user_Perfil1_idx');

            $table->foreign('idperfil', 'fk_user_Perfil1_idx')
                ->references('id')->on('perfil')
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
        Schema::dropIfExists('users');
    }
}
