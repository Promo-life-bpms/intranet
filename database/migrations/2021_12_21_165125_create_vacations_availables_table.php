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
        // 1 - Actual
        // 2 - Anterior
        // 3 -Expirado
        Schema::create('vacations_availables', function (Blueprint $table) {
            $table->id();
            $table->enum('period', [1, 2, 3]);
            $table->decimal('days_availables', 5, 2)->nullable()->default(0);
            $table->integer('dv')->nullable()->default(0);
            $table->integer('days_enjoyed')->default(0);
            $table->date('date_start')->nullable();
            $table->date('date_end')->nullable();
            $table->date('cutoff_date')->nullable();
            $table->integer('waiting')->nullable();
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
