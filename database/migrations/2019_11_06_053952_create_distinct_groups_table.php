<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistinctGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distinct_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->json('contact_category')->nullable();
            $table->json('gender')->nullable();
            $table->json('population_group')->nullable();
            $table->json('generation')->nullable();
            $table->json('marital_status')->nullable();
            $table->json('directorship_status')->nullable();
            $table->json('home_ownership_status')->nullable();
            $table->json('primary_property_type')->nullable();
            $table->json('property_count_bucket')->nullable();
            $table->json('lsm_group')->nullable();
            $table->json('income_bucket')->nullable();
            $table->json('province')->nullable();
            $table->json('area')->nullable();
            $table->json('municipality')->nullable();
            $table->json('employer')->nullable();
            $table->json('vehicle_ownership_status')->nullable();
            $table->json('age_group')->nullable();
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
        Schema::dropIfExists('distinct_groups');
    }
}
