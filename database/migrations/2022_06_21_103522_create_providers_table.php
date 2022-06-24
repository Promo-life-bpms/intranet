<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('service')->nullable();
            $table->string('type')->nullable();
            $table->string('name_contact')->nullable();
            $table->string('position')->nullable();
            $table->string('tel_office')->nullable();
            $table->string('tel_cel')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('web_page')->nullable();
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
        Schema::dropIfExists('providers');
    }
}
