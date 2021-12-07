<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('type_request');
            $table->string('payment');
            $table->date('absence');
            $table->date('admission');
            $table->string('reason');
            $table->unsignedBigInteger('direct_manager_id');
            $table->foreign('direct_manager_id')->references('id')->on('employees')->onDelete('cascade');
            $table->string('direct_manager_status');
            $table->string('human_resources_status');
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
        Schema::dropIfExists('requests');
    }
}
