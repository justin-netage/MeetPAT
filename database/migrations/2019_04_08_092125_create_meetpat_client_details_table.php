<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetpatClientDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meetpat_client_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            // Business Company Details
            $table->string('contact_first_name');
            $table->string('contact_last_name');
            $table->string('contact_email_address');
            $table->string('business_contact_number');
            $table->string('business_registered_name');
            $table->string('business_registration_number');
            $table->string('business_vat_number');
            $table->longText('business_postal_address');
            $table->longText('business_physical_address');
            // Business "Main Contact" Details
            $table->string('client_first_name');
            $table->string('client_last_name');
            $table->string('client_contact_number');
            $table->string('client_email_address');
            $table->longText('client_postal_address');
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
        Schema::dropIfExists('meetpat_client_details');
    }
}
