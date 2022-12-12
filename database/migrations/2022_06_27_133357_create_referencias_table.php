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
        Schema::create('referencias', function (Blueprint $table) {
            $table->id();
            $table->text('nome_autor')->nullable();
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->string('edicao')->nullable();
            $table->string('local')->nullable();
            $table->string('editora')->nullable();
            $table->string('ano')->nullable();
            $table->string('pagina')->nullable();
            $table->string('nomeDoSite')->nullable();
            $table->string('site')->nullable();
            $table->date('acessado')->nullable();
            $table->unsignedBigInteger('document_id');
            $table->foreign('document_id')->references('id')->on('documents');
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
        Schema::dropIfExists('referencias');
    }
};
