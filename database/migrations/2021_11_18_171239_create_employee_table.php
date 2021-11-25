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
            $table->string('num_tel',12);
            $table->string('correo1',100)->nullable();
            $table->string('correo2',100)->nullable();
            $table->string('correo3',100)->nullable();
            $table->string('correo4',100)->nullable();
            $table->timestamps();
        });


        Schema::create('employee', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',30);
            $table->string('paterno',30);
            $table->string('materno',30);
            $table->date('fecha_cumple');
            $table->date('fecha_ingreso');
            $table->boolean('status');
            $table->unsignedBigInteger('id_contacto');
            $table->unsignedBigInteger('id_user');
            $table->foreignId('user_id')->constrained();
            $table->foreign('id_contacto')->references('id')->on('contact');
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
