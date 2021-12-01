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
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });


        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->nullable();
            $table->string('paterno', 100)->nullable();
            $table->string('materno', 100)->nullable();
            $table->date('fecha_cumple')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->boolean('status')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('request', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_solicitud',30);
            $table->dateTime('fecha_solicitud');
            $table->string('tipo_soli',40);
            $table->string('especificacion_soli',20);
            $table->text('motivo_solicitud');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->boolean('status');
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')->references('id')->on('employee')->onDelete('cascade');
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
        Schema::dropIfExists('request');
        Schema::dropIfExists('employee');
        Schema::dropIfExists('contact');

    }
}
