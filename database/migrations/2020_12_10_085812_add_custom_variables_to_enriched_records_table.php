<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomVariablesToEnrichedRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enriched_records', function (Blueprint $table) {
            //
            $table->json('custom_variable_3')->after('custom_variable_2')->nullable()->default("{}");
            $table->json('custom_variable_4')->after('custom_variable_2')->nullable()->default("{}");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enriched_records', function (Blueprint $table) {
            //
            $table->dropColumn('custom_variable_3');
            $table->dropColumn('custom_variable_4');
        });
    }
}
