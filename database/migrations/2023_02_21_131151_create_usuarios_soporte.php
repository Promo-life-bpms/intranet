<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsuariosSoporte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soporte_usuarios_soporte', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('id_users')->references('id')->on('users');
            // $table->foreignId('id_categorias')->references('id')->on('soporte_categorias');
            $table->foreignId('users_id')->references('id')->on('users');
            $table->foreignId('categorias_id')->references('id')->on('soporte_categorias');
            $table->timestamps();
        });
    }

    /**     
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios_soporte');
    }
}
