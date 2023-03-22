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
            $table->string('mail')->nullable();
            $table->string('phone')->nullable();
            $table->string('cv')->nullable();
            $table->string('status');
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('department_id')->nullable();
            $table->dateTime('interview_date')->nullable();
            $table->timestamps();
        });

        Schema::create('postulant_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postulant_id')->references('id')->on('postulant')->onDelete('cascade');
            $table->string('place_of_birth')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('civil_status')->nullable();
            $table->integer('age')->nullable();
            $table->string('address')->nullable();
            $table->string('street')->nullable();
            $table->string('colony')->nullable();
            $table->string('delegation')->nullable();
            $table->string('postal_code',10)->nullable();
            $table->string('cell_phone',20)->nullable();
            $table->string('home_phone',20)->nullable();
            $table->string('curp',20)->nullable();
            $table->string('rfc',20)->nullable();
            $table->string('imss_number')->nullable();
            $table->string('fiscal_postal_code')->nullable();
            $table->string('position')->nullable();
            $table->string('area')->nullable();
            $table->string('horary')->nullable();
            $table->date('date_admission')->nullable();
            $table->string('card_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('infonavit_credit')->nullable();
            $table->string('factor_credit_number')->nullable();
            $table->string('fonacot_credit')->nullable();
            $table->string('discount_credit_number')->nullable();
            $table->string('home_references')->nullable();
            $table->string('house_characteristics')->nullable();
            $table->string('nacionality')->nullable();
            $table->string('id_credential')->nullable();
            $table->string('gender')->nullable();
            $table->string('month_salary_net')->nullable();
            $table->string('month_salary_gross')->nullable();
            $table->string('daily_salary')->nullable();
            $table->string('daily_salary_letter')->nullable();
            $table->string('position_objetive')->nullable();
            $table->timestamps();
        }); 

        Schema::create('postulant_beneficiary', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->integer('porcentage');
            $table->foreignId('postulant_details_id')->references('id')->on('postulant_details')->onDelete('cascade');
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
        Schema::dropIfExists('postulant_details');
        Schema::dropIfExists('postulant_beneficiary');
        Schema::dropIfExists('postulant_documentation');
        Schema::dropIfExists('postulant'); 
    }
}
