<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPropertyValuationBucketToEnrichedRecordsTable extends Migration
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
            $table->enum('PropertyValuationBucket', ['R0 - R1 000 000', 'R1 000 000 - R2 000 000', 'R2 000 000 - R4 000 000', 'R4 000 000 - R6 000 000', 'R7 000 000+'])->nullable()->after('PropertyValuation');


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
            $table->dropColumn('PropertyValuationBucket');
        });
    }
}
