<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomVariable2ToEnrichedRecordsTable extends Migration
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
            $table->json('custom_variable_2')->after('custom_variable_1')->nullable()->default("{}");
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
            $table->dropColumn('custom_variable_2');
        });
    }
}
