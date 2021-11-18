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
            $table->string('nombre_soli',30);
            $table->dateTime('fecha_soli');
            $table->string('tipo_soli',30);
            $table->date('dias_soli',30);
            $table->string('especificacion_soli',20);
            $table->text('motivo_soli');
            $table->boolean('status');
            $table->string('id_empleado',10);
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
