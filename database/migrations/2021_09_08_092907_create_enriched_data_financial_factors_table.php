<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrichedDataFinancialFactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_data_financial_factors', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('id')->on('enriched_data_contacts');
            $table->boolean('DirectorshipStatus')->nullable();
            $table->boolean('HasResidentailAddress')->nullable();
            $table->string('CreditRiskCategory', 9)->nullable();
            $table->bigInteger('Income')->nullable();
            $table->string('IncomeBucket', 17)->nullable();
            $table->string('LSMGroup', 5);
            $table->string('Employer')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enriched_data_financial_factors');
    }
}
