<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestForSystemsAndCommunicationsServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_for_systems_and_communications_services', function (Blueprint $table) {
            $table->id();
            $table->string('type_of_user');
            $table->string('name');
            $table->date('date_admission')->nullable();
            $table->string('area');
            $table->string('departament');
            $table->string('position');
            $table->string('extension');
            $table->string('immediate_boss');
            $table->string('company');
            $table->string('computer_type');
            $table->string('cell_phone');
            $table->string('#');
            $table->string('extension_number');
            $table->string('equipment_to_use');
            $table->string('accessories');
            $table->string('previous_user');
            $table->string('email');
            $table->string('signature_or_telephone_contact_numer');
            $table->string('distribution_and_forwarding');
            $table->boolean('office');
            $table->boolean('acrobat_pdf');
            $table->boolean('photoshop');
            $table->boolean('premier');
            $table->boolean('audition');
            $table->boolean('solid_works');
            $table->boolean('autocad');
            $table->boolean('odoo');
            $table->string('odoo_users');
            $table->string('work_profile_in_odoo');
            $table->string('others');
            $table->string('access_to_server_shared_folder');
            $table->string('folder_path');
            $table->string('type_of_access');
            $table->string('observations');
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
        Schema::dropIfExists('request_for_systems_and_communications_services');
    }
}
