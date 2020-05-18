<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecordsResultToProcessTrackingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('process_trackings', function (Blueprint $table) {
            //
            $table->integer('records_result')->nullable()->after('job_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('process_trackings', function (Blueprint $table) {
            //
            $table->dropColumn('records_result');
        });
    }
}
