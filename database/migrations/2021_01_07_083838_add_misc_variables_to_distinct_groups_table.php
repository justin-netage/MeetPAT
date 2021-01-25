<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMiscVariablesToDistinctGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distinct_groups', function (Blueprint $table) {
            //
            $table->json('misc_variable_star_sign')->nullable();
            $table->json('misc_variable_birth_month')->nullable();
            $table->json('misc_variable_birth_day')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distinct_groups', function (Blueprint $table) {
            //
            $table->dropColumn('misc_variable_star_sign');
            $table->dropColumn('misc_variable_birth_month');
            $table->dropColumn('misc_variable_birth_day');
        });
    }
}
