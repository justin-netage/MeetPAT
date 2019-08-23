<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnRecievedFromEnrichedDataTrackingsTable extends Migration
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
            $table->renameColumn('recieved', 'received');
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
            $table->renameColumn('received', 'recieved')->nullable();
        });
    }
}
