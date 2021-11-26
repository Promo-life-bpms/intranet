<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('contact', function (Blueprint $table) {
            $table->id();
            $table->string('num_tel', 12)->nullable();
            $table->string('correo1', 100)->nullable();
            $table->string('correo2', 100)->nullable();
            $table->string('correo3', 100)->nullable();
            $table->string('correo4', 100)->nullable();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });


        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->nullable();
            $table->string('paterno', 100)->nullable();
            $table->string('materno', 100)->nullable();
            $table->date('fecha_cumple')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->boolean('status')->nullable();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('employee');
        Schema::dropIfExists('contact');
    }
}
