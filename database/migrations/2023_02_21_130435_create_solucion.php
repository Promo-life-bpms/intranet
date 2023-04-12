<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use League\CommonMark\Reference\Reference;

class CreateSolucion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soporte_solucion', function (Blueprint $table) {
            $table->id();
            $table->mediumText('description');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('ticket_id')->references('id')->on('soporte_tickets');
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
        Schema::dropIfExists('solucion');
    }
}
