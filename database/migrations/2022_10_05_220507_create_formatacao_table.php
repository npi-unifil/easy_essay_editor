<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('formatacao', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('templates_id');
            $table->foreign('templates_id')->references('id')->on('templates');
            $table->string('name');
            $table->integer('margem-superior')->nullable();
            $table->integer('margem-inferior')->nullable();
            $table->integer('margem-direita')->nullable();
            $table->integer('margem-esquerda')->nullable();
            $table->string('fonte')->nullable();
            $table->string('cor')->nullable();
            $table->integer('tamanho-fonte')->nullable();
            $table->integer('tamanho-fonte-titulo')->nullable();
            $table->string('alinhamento-texto')->nullable();
            $table->string('alinhamento-titulo')->nullable();
            $table->double('espacamento-texto')->nullable();
            $table->double('espacamento-citacao')->nullable();
            $table->double('espacamento-paragrafo')->nullable();
            $table->double('recuo-texto')->nullable();
            $table->string('formato-titulo')->nullable();
            $table->string('formato-texto')->nullable();
            $table->string('peso-texto')->nullable();
            $table->string('peso-titulo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('formatacao');
    }
};
