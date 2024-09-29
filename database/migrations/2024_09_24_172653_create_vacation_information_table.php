<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacationInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacation_information', function (Blueprint $table) {
            $table->id();
            $table->integer('total_days');
            $table->foreignId('id_vacations_availables')->references('id')->on('vacations_available_per_users')->onDelete('cascade');
            $table->foreignId('id_vacation_request')->references('id')->on('vacation_requests')->onDelete('cascade');
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
        Schema::dropIfExists('vacation_information');
    }
}
