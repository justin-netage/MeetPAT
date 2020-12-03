<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCustomVariable2ToAudienceFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audience_filters', function (Blueprint $table) {
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
        Schema::table('audience_filters', function (Blueprint $table) {
            //
            $table->dropColumn('custom_variable_2');
        });
    }
}
