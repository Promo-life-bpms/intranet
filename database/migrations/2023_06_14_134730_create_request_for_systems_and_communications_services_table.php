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
            $table->string('type_of_user')->nullable();
            $table->string('name')->nullable();
            $table->date('date_admission')->nullable();
            $table->string('area')->nullable();
            $table->string('departament')->nullable();
            $table->string('position')->nullable();
            $table->string('extension')->nullable();
            $table->string('immediate_boss')->nullable();
            $table->string('company')->nullable();
            $table->string('computer_type')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('number')->nullable();
            $table->string('extension_number')->nullable();
            $table->string('equipment_to_use')->nullable();
            $table->string('accessories')->nullable();
            $table->string('previous_user')->nullable();
            $table->string('email')->nullable();
            $table->string('signature_or_telephone_contact_numer')->nullable();
            $table->string('distribution_and_forwarding')->nullable();
            $table->boolean('office')->nullable();
            $table->boolean('acrobat_pdf')->nullable();
            $table->boolean('photoshop')->nullable();
            $table->boolean('premier')->nullable();
            $table->boolean('audition')->nullable();
            $table->boolean('solid_works')->nullable();
            $table->boolean('autocad')->nullable();
            $table->boolean('odoo')->nullable();
            $table->string('odoo_users')->nullable();
            $table->string('work_profile_in_odoo')->nullable();
            $table->string('others')->nullable();
            $table->string('access_to_server_shared_folder')->nullable();
            $table->string('folder_path')->nullable();
            $table->string('type_of_access')->nullable();
            $table->string('observations')->nullable();
            $table->string('status');
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
