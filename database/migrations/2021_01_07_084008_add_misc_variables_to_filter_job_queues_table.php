<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMiscVariablesToFilterJobQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filter_job_queues', function (Blueprint $table) {
            //
            $table->string("misc_variable_star_sign")->nullable()->default("");
            $table->string("misc_variable_birth_month")->nullable()->default("");
            $table->string("misc_variable_birth_day")->nullable()->default("");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filter_job_queues', function (Blueprint $table) {
            //
            $table->dropColumn('misc_variable_star_sign');
            $table->dropColumn('misc_variable_birth_month');
            $table->dropColumn('misc_variable_birth_day');
        });
    }
}
