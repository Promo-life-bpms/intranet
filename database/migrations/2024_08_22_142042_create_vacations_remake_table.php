<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacationsRemakeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historical_changes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users');
            $table->string('type',50);
            $table->string('details',100);
            $table->timestamps();
        });

        Schema::create('type_request', function (Blueprint $table) {
            $table->id();
            $table->string('type',100);
            $table->string('description');
            $table->tinyInteger('max_hours_peer_day');
            $table->tinyInteger('uses_peer_month');
            $table->boolean('continuos_days');
            $table->tinyInteger('max_continuos_uses')->nullable();
            $table->tinyInteger('min_month_time')->nullable();
            $table->boolean('comprobation')->nullable();
            $table->boolean('status');
            $table->timestamps();
        });

        Schema::create('type_period', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('description');
            $table->tinyInteger('increase_per_year');
            $table->timestamps();
        });

        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('description');
            $table->tinyInteger('increase_per_year');
            $table->timestamps();
        });

        Schema::create('retained_days', function (Blueprint $table) {
            $table->id();
            $table->date('day');
            $table->foreignId('request_calendars_id')->references('id')->on('request_calendars');
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
        Schema::dropIfExists('type_period');
        Schema::dropIfExists('periods');
        Schema::dropIfExists('type_request');
        Schema::dropIfExists('historical_changes');
        Schema::dropIfExists('retained_days');
    }
}
