<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_types', function (Blueprint $table) {
            $table->id();
            $table->string('type', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->tinyInteger('max_hours_peer_day')->nullable();
            $table->tinyInteger('uses_peer_mont')->nullable();
            $table->boolean('continuos_days')->nullable();
            $table->tinyInteger('max_continuos_uses')->nullable();
            $table->tinyInteger('min_month_time')->nullable();
            $table->boolean('comprobation')->nullable();
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
        Schema::dropIfExists('request_types');
    }
}
