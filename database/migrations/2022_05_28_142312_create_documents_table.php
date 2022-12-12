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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('nomeAutor');
            $table->string('nome');
            $table->string('orientador');
            $table->string('cidade');
            $table->integer('ano');
            $table->string('curso');
            $table->text('banca')->nullable();
            $table->string('dedicatoria')->nullable();
            $table->string('agradecimentos')->nullable();
            $table->string('epigrafe')->nullable();
            $table->string('apendice')->nullable();
            $table->string('anexo')->nullable();
            $table->unsignedBigInteger('users_id');
            $table->foreign('users_id')->references('id')->on('users');
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
        Schema::dropIfExists('documents');
    }
};
