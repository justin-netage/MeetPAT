<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomVariable1ToFilteredAudienceFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('filtered_audience_files', function (Blueprint $table) {
            //
            $table->string('custom_variable_1')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('filtered_audience_files', function (Blueprint $table) {
            //
            $table->dropColumn('custom_variable_1');
        });
    }
}
