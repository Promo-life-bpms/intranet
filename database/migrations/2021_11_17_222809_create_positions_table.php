<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->text('nombre');
            $table->boolean('visible');
            $table->timestamps();
        });


        Schema::create('positions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text('nombre');
            $table->boolean('visible');
            $table->unsignedBigInteger('area_id');
            $table->foreign('area_id')->references('id')->on('departments')->onDelete('cascade');
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
        Schema::dropIfExists('positions');
        Schema::dropIfExists('departments');


    }
}
