<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrichedRecordsFinancialFactorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_records_financial_factors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('contact_id')->on('enriched_records_contacts');
            $table->enum('DirectorshipStatus', ['True', 'False', 'Unknown'])->nullable();
            $table->string('Income')->nullable();
            $table->enum('IncomeBucket', ['R0 - R2 500', 'R2 500 - R5 000', 'R5 000 - R10 000', 'R10 000 - R20 000', 'R20 000 - R30 000', 'R30 000 - R40 000', 'R40 000 +', 'Unknown'])->nullable();
            $table->enum('LSMGroup', ['LSM00', 'LSM01', 'LSM02', 'LSM03', 'LSM04', 'LSM05', 'LSM06', 'LSM07', 'LSM08', 'LSM09', 'LSM10', 'Unknown'])->nullable();
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
        Schema::dropIfExists('enriched_records_financial_factors');
    }
}
