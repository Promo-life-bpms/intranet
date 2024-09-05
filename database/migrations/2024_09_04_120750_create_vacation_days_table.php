<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacationDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacation_days', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->time('start')->nullable();
            $table->time('end')->nullable();
            $table->foreignId('vacation_request_id')->references('id')->on('vacation_requests')->onDelete('cascade');
            $table->boolean('status');
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
        Schema::dropIfExists('vacation_days');
    }
}
