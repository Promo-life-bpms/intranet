<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('soporte_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->string('data');
            $table->timestamps();
            // $table->time('priority');
            $table->foreignId('category_id')->references('id')->on('soporte_categorias');
            $table->foreignId('status_id')->references('id')->on('soporte_status');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('support_id')->references('id')->on('users');
            $table->foreignId('priority_id')->references('id')->on('soporte_tiempos');
            $table->time('special')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}

