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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->date('birthday_date')->nullable();
            $table->date('date_admission')->nullable();
            $table->boolean('status')->nullable();
            $table->foreignId('position_id')->nullable()->constrained();
            $table->foreignid('jefe_directo_id')->nullable()->references('id')->on('employees');
            $table->foreignid('user_id')->references('id')->on('users')->onDelete('cascade');
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

    }
}
