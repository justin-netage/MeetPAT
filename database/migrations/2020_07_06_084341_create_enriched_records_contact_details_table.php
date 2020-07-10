<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrichedRecordsContactDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_records_contact_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('contact_id')->on('enriched_records_contacts');
            $table->integer('id6')->nullable();
            $table->string('FirstName')->nullable();
            $table->string('Middlename')->nullable();
            $table->string('Surname')->nullable();
            $table->string('CleanPhone')->nullable();
            $table->string('Email1')->nullable();
            $table->string('Email2')->nullable();
            $table->string('Email3')->nullable();
            $table->string('MobilePhone1')->nullable();
            $table->string('MobilePhone2')->nullable();
            $table->string('MobilePhone3')->nullable();
            $table->string('WorkPhone1')->nullable();
            $table->string('WorkPhone2')->nullable();
            $table->string('WorkPhone3')->nullable();
            $table->string('HomePhone1')->nullable();
            $table->string('HomePhone2')->nullable();
            $table->string('HomePhone3')->nullable();
            $table->enum('ContactCategory', ['Very Low', 'Low', 'Medium', 'High', 'Very High', 'Unknown'])->nullable();
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
        Schema::dropIfExists('enriched_records_contact_details');
    }
}
