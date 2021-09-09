<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrichedDataCustomVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_data_custom_variables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('id')->on('enriched_data_contacts');
            $table->json('custom_variable_1')->nullable()->default('{}');
            $table->json('custom_variable_2')->nullable()->default('{}');
            $table->json('custom_variable_3')->nullable()->default('{}');
            $table->json('custom_variable_4')->nullable()->default('{}');
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
        Schema::dropIfExists('enriched_data_custom_variables');
    }
}
