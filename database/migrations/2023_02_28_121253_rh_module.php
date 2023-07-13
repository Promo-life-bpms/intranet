<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Laravel\Ui\Presets\Vue;

class RhModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postulant', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname');
            $table->string('vacant');
            $table->date('birthdate');
            $table->string('nss');
            $table->string('curp');
            $table->string('full_address');
            $table->string('phone');
            $table->string('message_phone');
            $table->string('email');
            $table->string('status');
           
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();

            $table->string('civil_status')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('nacionality')->nullable();
            $table->string('id_credential')->nullable();
            $table->string('fiscal_postal_code')->nullable();
            $table->string('rfc')->nullable();

            $table->string('place_of_birth')->nullable();
            $table->string('street')->nullable();
            $table->string('colony')->nullable();
            $table->string('delegation')->nullable();
            $table->string('postal_code',10)->nullable();
            $table->string('home_phone',20)->nullable();
            $table->string('home_references')->nullable();
            $table->string('house_characteristics')->nullable();

            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->date('date_admission')->nullable();

            $table->string('card_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('infonavit_credit')->nullable();
            $table->string('factor_credit_number')->nullable();
            $table->string('fonacot_credit')->nullable();
            $table->string('discount_credit_number')->nullable();
           
            $table->string('month_salary_net')->nullable();
            $table->string('month_salary_gross')->nullable();
            $table->string('daily_salary')->nullable();
            $table->string('daily_salary_letter')->nullable();
            $table->string('position_objetive')->nullable();
            $table->string('contract_duration')->nullable();
            $table->timestamps();
        });

        Schema::create('postulant_beneficiary', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->integer('porcentage')->nullable();
            $table->string('position')->nullable();
            $table->foreignId('postulant_id')->references('id')->on('postulant')->onDelete('cascade');
            $table->timestamps();
        });
        
        Schema::create('postulant_documentation', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('description')->nullable();
            $table->string('resource');
            $table->foreignId('postulant_id')->references('id')->on('postulant')->onDelete('cascade');
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
        Schema::dropIfExists('postulant_beneficiary');
        Schema::dropIfExists('postulant_documentation');
        Schema::dropIfExists('postulant'); 
    }
}
