<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddJobQueueIdToEnrichedDataTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enriched_data_trackings', function (Blueprint $table) {
            //
            $table->string('job_queue_id')->after('user_id');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enriched_data_trackings', function (Blueprint $table) {
            //
            $table->dropColumn('job_queue_id');
        });
    }
}
