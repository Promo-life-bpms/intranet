<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUserPosition extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('table_user_position', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('puesto_id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('puesto_id')->references('id')->on('positions');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('table_user_position');
    }
}
