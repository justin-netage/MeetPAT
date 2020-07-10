<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrichedRecordsDemographicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_records_demographics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('contact_id')->on('enriched_records_contacts');
            $table->enum('Gender', ['M', 'F', 'Unknown'])->nullable();
            $table->enum('PopulationGroup', ['B', 'W', 'C', 'A', 'Unknown'])->nullable();
            $table->enum('DeceasedStatus', ['True', 'False', 'Unknown'])->nullable();
            $table->enum('Generation', ['Baby Boomer', 'Generation X', 'Xennials', 'Millennials', 'iGen', 'Unknown'])->nullable();
            $table->enum('MaritalStatus', ['True', 'False', 'Unknown'])->nullable();
            $table->enum('AgeGroup', ['Teenager', 'Twenties', 'Thirties', 'Fourties', 'Fifties', 'Sixties', 'Seventies', 'Eighty +', 'Unknown'])->nullable();
            $table->enum('CitizenIndicator', ['Citizen', 'Resident', '', 'Unknown'])->nullable();
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
        Schema::dropIfExists('enriched_records_demographics');
    }
}
