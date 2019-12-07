<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnderecoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('endereco', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('idpessoa');
            $table->unsignedInteger('idbairro');
            $table->unsignedInteger('idtipo_endereco');
            $table->char('ativo', 1)->nullable();
            $table->char('principal', 1)->nullable();
            $table->string('logradouro', 100)->nullable();
            $table->string('complemento', 45)->nullable();
            $table->string('cep', 15)->nullable();
            $table->string('numero', 5)->nullable();
            $table->timestamps();

            $table->foreign('idpessoa', 'fk_endereco_pessoa1_idx')
                ->references('id')->on('pessoa')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idbairro', 'fk_endereco_bairro1_idx')
                ->references('id')->on('bairro')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('idtipo_endereco', 'fk_endereco_tipo_endereco1_idx')
                ->references('id')->on('tipo_endereco')
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
        Schema::dropIfExists('endereco');
    }
}
