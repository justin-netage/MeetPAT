<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFileUniqueNameColumnFromFilteredAudienceFilesTable extends Migration
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
            $table->string('file_unique_name')->change();
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
            $table->integer('file_unique_name')->change();
        });
    }
}
