<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilteredAudienceFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filtered_audience_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('file_id');
            $table->integer('file_unique_name');
            $table->string('number_of_contacts')->nullable();
            $table->string('province')->nullable();
            $table->string('age_group')->nullable();
            $table->string('gender')->nullable();
            $table->string('population_group')->nullable();
            $table->string('generation')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('home_ownership_status')->nullable();
            $table->string('risk_category')->nullable();
            $table->string('income_bucket')->nullable();
            $table->string('directorship_status')->nullable();
            $table->string('citizen_vs_resident')->nullable();
            $table->string('municipality')->nullable();
            $table->string('area')->nullable();
            $table->string('vehicle_ownership_status')->nullable();
            $table->string('property_valuation_bucket')->nullable();
            $table->string('lsm_group')->nullable();
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
        Schema::dropIfExists('filtered_audience_files');
    }
}
