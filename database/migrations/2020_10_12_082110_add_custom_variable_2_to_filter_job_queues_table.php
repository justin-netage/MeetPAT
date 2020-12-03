<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomVariable2ToFilterJobQueuesTable extends Migration
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
            $table->string('custom_variable_2')->nullable()->default("");
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
            $table->dropColumn('custom_variable_2');
        });
    }
}
