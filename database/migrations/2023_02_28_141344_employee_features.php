<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmployeeFeatures extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
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
            $table->decimal('salary_sd',8,2)->nullable();
            $table->decimal('salary_sbc',8,2)->nullable();
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
            $table->timestamps();
        }); 

        Schema::create('salary_history', function (Blueprint $table) {
            $table->id();
            $table->string('actual_position');
            $table->string('salary')->nullable();
            $table->foreignId('users_details_id')->references('id')->on('users_details')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('user_beneficiary', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->integer('porcentage');
            $table->foreignId('users_details_id')->references('id')->on('users_details')->onDelete('cascade');
            $table->timestamps();
        });

        
        Schema::create('users_documentation', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('description')->nullable();
            $table->string('resource');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        }); 

        Schema::create('users_down_motive', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('growth_salary',6)->nullable();
            $table->string('growth_promotion',6)->nullable();
            $table->string('growth_activity',6)->nullable();
            $table->string('climate_partnet',6)->nullable();
            $table->string('climate_manager',6)->nullable();
            $table->string('climate_boss',6)->nullable();
            $table->string('psicosocial_workloads',6)->nullable();
            $table->string('psicosocial_appreciation',6)->nullable();
            $table->string('psicosocial_violence',6)->nullable();
            $table->string('psicosocial_workday',6)->nullable();
            $table->string('demographics_distance',6)->nullable();
            $table->string('demographics_physical',6)->nullable();
            $table->string('demographics_personal',6)->nullable();
            $table->string('demographics_school',6)->nullable();
            $table->string('health_personal',6)->nullable();
            $table->string('health_familiar',6)->nullable();
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
        Schema::dropIfExists('users_details');
        Schema::dropIfExists('salary_history');
        Schema::dropIfExists('user_beneficiary');
        Schema::dropIfExists('postulant_documentation'); 
    }
}
