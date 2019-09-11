<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryPropertyTypeToFilteredAudienceFilesTable extends Migration
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
            $table->enum('primary_property_type', ['E', 'F', 'U', 'S', 'A', 'C', 'H', 'R', 'T', ''])->nullable()->after('property_count_bucket')->nullable();

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
            $table->dropColumn('primary_property_type');

        });
    }
}
