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
            $table->string('name');
            $table->integer('margemSuperior')->nullable();
            $table->integer('margemInferior')->nullable();
            $table->integer('margemDireita')->nullable();
            $table->integer('margemEsquerda')->nullable();
            $table->integer('tamanhoFonte')->nullable();
            $table->integer('tamanhoFonteTitulo')->nullable();
            $table->string('alinhamentoTexto')->nullable();
            $table->string('alinhamentoTitulo')->nullable();
            $table->double('espacamentoTexto')->nullable();
            $table->string('formatoTitulo')->nullable();
            $table->string('formatoTexto')->nullable();
            $table->string('pesoTitulo')->nullable();
            $table->unsignedBigInteger('templates_id');
            $table->foreign('templates_id')->references('id')->on('templates');
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
