<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFbAudienceIdToSavedFilteredAudienceFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saved_filtered_audience_files', function (Blueprint $table) {
            //
            $table->string('fb_audience_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saved_filtered_audience_files', function (Blueprint $table) {
            //
            $table->dropColumn('fb_audience_id');
        });
    }
}
