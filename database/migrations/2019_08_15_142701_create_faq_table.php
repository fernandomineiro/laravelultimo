<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFaqTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faq', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->unsignedInteger('idmodulo')->nullable();
            $table->string('questao', 400)->nullable();
            $table->string('resposta', 2000)->nullable();
            $table->char('ativo', 1)->nullable();
            $table->integer('ordem');
            $table->timestamps();

            $table->index(["idmodulo"], 'fk_faqs_modulo1_idx');

            $table->foreign('idmodulo')
                ->references('id')
                ->on('modulo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faq');
    }
}
