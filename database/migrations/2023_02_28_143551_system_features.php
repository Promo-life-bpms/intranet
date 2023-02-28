<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SystemFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('device_status', function (Blueprint $table) {
            $table->id();
            $table->string('device_status');
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('serial_number');
            $table->string('type');
            $table->string('name')->nullable();
            $table->integer('brand')->nullable();
            $table->integer('processor')->nullable();
            $table->integer('ram')->nullable();
            $table->integer('storage')->nullable();
            $table->foreign('device_status_id')->references('id')->on('device_status')->onDelete('cascade');
            $table->bigInteger('user_id')->nullable();
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
        Schema::dropIfExists('devices');
        Schema::dropIfExists('device_status');
    }
}
