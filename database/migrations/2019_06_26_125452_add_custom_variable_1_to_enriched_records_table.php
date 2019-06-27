<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomVariable1ToEnrichedRecordsTable extends Migration
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
            $table->longText('custom_variable_1')->after('affiliated_users')->nullable();
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
            $table->dropColumn('custom_variable_1');
        });
    }
}
