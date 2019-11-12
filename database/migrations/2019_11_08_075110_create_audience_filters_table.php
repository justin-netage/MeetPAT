<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAudienceFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audience_filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->enum('type', ['all_records', 'filter']);
            $table->json('number_of_contacts')->nullable();
            $table->json('province')->nullable();
            $table->json('age_group')->nullable();
            $table->json('gender')->nullable();
            $table->json('population_group')->nullable();
            $table->json('generation')->nullable();
            $table->json('marital_status')->nullable();
            $table->json('home_ownership_status')->nullable();
            $table->json('risk_category')->nullable();
            $table->json('income_bucket')->nullable();
            $table->json('directorship_status')->nullable();
            $table->json('citizen_vs_resident')->nullable();
            $table->json('municipality')->nullable();
            $table->json('area')->nullable();
            $table->json('vehicle_ownership_status')->nullable();
            $table->json('property_valuation_bucket')->nullable();
            $table->json('lsm_group')->nullable();
            $table->json('property_count_bucket')->nullable();
            $table->json('primary_property_type')->nullable();
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
        Schema::dropIfExists('audience_filters');
    }
}
