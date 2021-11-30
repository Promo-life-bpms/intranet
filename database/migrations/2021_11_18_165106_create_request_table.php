<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
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
            $table->unsignedBigInteger('id_empleado');
           // $table->foreign('id_empleado')->references('id')->on('employee')->onDelete('cascade');
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
    }
}
