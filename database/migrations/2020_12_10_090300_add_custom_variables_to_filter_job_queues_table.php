<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomVariablesToFilterJobQueuesTable extends Migration
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
            $table->string('custom_variable_3')->nullable()->default("");
            $table->string('custom_variable_4')->nullable()->default("");
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
            $table->dropColumn('custom_variable_3');
            $table->dropColumn('custom_variable_4');
        });
    }
}
