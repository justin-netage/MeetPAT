<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrichedDataContactDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_data_contact_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('id')->on('enriched_data_contacts');
            $table->bigInteger('RecordKey');
            $table->string('ClientRecordID', 13);
            $table->string('id6', 6)->nullable();
            $table->string('InputIdn', 13)->nullable();
            $table->string('InputFirstName')->nullable();
            $table->string('InputSurname')->nullable();
            $table->string('InputPhone')->nullable();
            $table->string('InputEmail')->nullable();
            $table->string('FirstName')->nullable();
            $table->string('Middlename')->nullable();
            $table->string('Surname')->nullable();
            $table->string('CleanPhone', 15)->nullable();
            $table->string('Email1')->nullable();
            $table->string('Email2')->nullable();
            $table->string('Email3')->nullable();
            $table->string('MobilePhone1', 15)->nullable();
            $table->string('MobilePhone2', 15)->nullable();
            $table->string('MobilePhone3', 15)->nullable();
            $table->string('WorkPhone1', 15)->nullable();
            $table->string('WorkPhone2', 15)->nullable();
            $table->string('WorkPhone3', 15)->nullable();
            $table->string('HomePhone1', 15)->nullable();
            $table->string('HomePhone2', 15)->nullable();
            $table->string('HomePhone3', 15)->nullable();
            $table->string('ContactCategory', 9)->nullable();
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
        Schema::dropIfExists('enriched_data_contact_details');
    }
}
