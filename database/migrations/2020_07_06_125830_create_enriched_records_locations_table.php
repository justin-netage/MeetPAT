<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrichedRecordsLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_records_locations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('contact_id')->on('enriched_records_contacts');
            $table->enum('Province', ['WC', 'EC', 'NC', 'G', 'FS', 'M', 'L', 'NW', 'KN', 'Unknown'])->nullable();
            $table->string('Area')->nullable();
            $table->string('Municipality')->nullable();
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
        Schema::dropIfExists('enriched_records_locations');
    }
}
