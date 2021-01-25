<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCreditRiskCategoryToEnrichedRecordsFinancialFactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enriched_records_financial_factors', function (Blueprint $table) {
            //
            $table->enum('CreditRiskCategory', ['VERY_LOW', 'LOW', 'MEDIUM', 'HIGH', 'VERY_HIGH', 'Unknown'])->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('enriched_records_financial_factors', function (Blueprint $table) {
            //
            $table->dropColumn('CreditRiskCategory');
        });
    }
}
