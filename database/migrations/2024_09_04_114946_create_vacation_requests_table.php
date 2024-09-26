<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacationRequestsTable extends Migration
{
    
    public function up()
    {
        Schema::create('vacation_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('request_type_id')->references('id')->on('request_types')->onDelete('cascade');
            $table->longText('details')->nullable();
            $table->longText('more_information')->nullable();
            $table->integer('reveal_id')->nullable();
            $table->string('file', 255)->nullable();
            $table->longText('commentary')->nullable();
            $table->foreignId('direct_manager_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('direct_manager_status')->nullable();
            $table->string('rh_status')->nullable();
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
        Schema::dropIfExists('vacation_requests');
    }
}
