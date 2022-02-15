<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_rejected', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('start');
            $table->date('end');
            $table->foreignId('users_id')->constrained()->onDelete('cascade');
            $table->integer('requests_id')->nullable();
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
        Schema::dropIfExists('request_days');
    }
}
