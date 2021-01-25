<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomVariablesToEnrichedRecordsCustomVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enriched_records_custom_variables', function (Blueprint $table) {
            //
            $table->json('custom_variable_2')->nullable();
            $table->json('custom_variable_3')->nullable();
            $table->json('custom_variable_4')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enriched_records_custom_variables', function (Blueprint $table) {
            //
            $table->dropColumn('custom_variable_2');
            $table->dropColumn('custom_variable_3');
            $table->dropColumn('custom_variable_4');
        });
    }
}
