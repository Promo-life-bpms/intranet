<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommuniquesCompanies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communique_company', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('communique_id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('communique_id')->references('id')->on('communiques')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            //$table->foreignId('communiques_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('communiques_companies');
    }
}
