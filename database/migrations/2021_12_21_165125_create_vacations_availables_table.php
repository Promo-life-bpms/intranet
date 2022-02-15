<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVacationsAvailablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacations_availables', function (Blueprint $table) {
            $table->id();
            $table->decimal('period_days', 5,2)->nullable()->default(0);
            $table->decimal('current_days',5,2)->nullable()->default(0);
            $table->decimal ('dv', 5,2)->nullable()->default(0);            
            $table->foreignId('users_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('vacations_availables');
    }
}
