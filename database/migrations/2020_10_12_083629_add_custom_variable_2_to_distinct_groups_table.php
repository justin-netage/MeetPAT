<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomVariable2ToDistinctGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('distinct_groups', function (Blueprint $table) {
            //
            $table->json('custom_variable_2')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('distinct_groups', function (Blueprint $table) {
            //
            $table->dropColumn('custom_variable_2');
        });
    }
}
