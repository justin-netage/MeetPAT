<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrichedRecordsCustomVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_records_custom_variables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('contact_id')->on('enriched_records_contacts');
            $table->json('custom_variable_1')->nullable();
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
        Schema::dropIfExists('enriched_records_custom_variables');
    }
}
