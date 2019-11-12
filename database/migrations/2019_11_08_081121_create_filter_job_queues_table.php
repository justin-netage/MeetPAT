<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilterJobQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filter_job_queues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->enum('filter_type', ['all_records', 'filter']);
            $table->integer('audience_filters_id')->nullable();
            $table->enum('status', ['processing','complete']);
            $table->string('provinces')->nullable()->default("");
            $table->string('municipalities')->nullable()->default("");
            $table->string('areas')->nullable()->default("");
            $table->string('genders')->nullable()->default("");
            $table->string('population_groups')->nullable()->default("");
            $table->string('age_groups')->nullable()->default("");
            $table->string('generations')->nullable()->default("");
            $table->string('citizens_vs_residents')->nullable()->default("");
            $table->string('marital_statuses')->nullable()->default("");
            $table->string('home_ownership_statuses')->nullable()->default("");
            $table->string('property_count_buckets')->nullable()->default("");
            $table->string('property_valuation_buckets')->nullable()->default("");
            $table->string('vehicle_ownership_statuses')->nullable()->default("");
            $table->string('primary_property_types')->nullable()->default("");
            $table->string('risk_categories')->nullable()->default("");
            $table->string('lsm_groups')->nullable()->default("");
            $table->string('income_buckets')->nullable()->default("");
            $table->string('company_directorship_status')->nullable()->default("");
            $table->string('custom_variable_1')->nullable()->default("");
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
        Schema::dropIfExists('filter_job_queues');
    }
}
