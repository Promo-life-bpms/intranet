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
            $table->timestamps();
        });

        Schema::create('postulant_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('postulant_id')->references('id')->on('postulant')->onDelete('cascade');
            $table->string('place_of_birth');
            $table->date('birthdate');
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('civil_status');
            $table->integer('age');
            $table->string('address');
            $table->string('street');
            $table->string('colony');
            $table->string('delegation');
            $table->string('postal_code',10);
            $table->string('cell_phone',20);
            $table->string('home_phone',20);
            $table->string('curp',20);
            $table->string('rfc',20);
            $table->string('imss_number');
            $table->string('fiscal_postal_code');
            $table->string('position');
            $table->string('area');
            $table->decimal('salary_sd',8,2);
            $table->decimal('salary_sbc',8,2);
            $table->string('horary');
            $table->date('date_admission');
            $table->string('card_number');
            $table->string('bank_name')->nullable();
            $table->string('infonavit_credit');
            $table->string('factor_credit_number');
            $table->string('fonacot_credit');
            $table->string('discount_credit_number');
            $table->string('home_references');
            $table->string('house_characteristics');
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
