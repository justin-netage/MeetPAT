<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResellerIdToMeetpatClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meetpat_clients', function (Blueprint $table) {
            //
            $table->integer('reseller_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meetpat_clients', function (Blueprint $table) {
            //
            $table->dropColumn('reseller_id');
        });
    }
}
