<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFilteredAudiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_filtered_audiences', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_id');
            $table->integer('number_of_contacts');
            $table->string('selected_provinces')->nullable();
            $table->string('selected_ages')->nullable();
            $table->string('selected_genders')->nullable();
            $table->string('selected_population_groups')->nullable();
            $table->string('selected_generations')->nullable();
            $table->string('selected_citizens_vs_residents')->nullable();
            $table->string('selected_marital_statuses')->nullable();
            $table->string('selected_home_owners')->nullable();
            $table->string('selected_risk_categories')->nullable();
            $table->string('selected_household_incomes')->nullable();
            $table->string('selected_directors')->nullable();
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
        Schema::dropIfExists('user_filtered_audiences');
    }
}
