<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrichedDataDemographicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_data_demographics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('id')->on('enriched_data_contacts');
            $table->string('Gender', 1)->nullable();
            $table->string('AgeGroup', 9)->nullable();
            $table->string('PopulationGroup', 1)->nullable();
            $table->string('CitizenshipIndicator', 8)->nullable();
            $table->boolean('DeceasedStatus')->nullable();
            $table->string('Generation')->nullable();
            $table->boolean('MaritalStatus')->nullable();
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
        Schema::dropIfExists('enriched_data_demographics');
    }
}
