<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrichedRecordsAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_records_assets', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('contact_id')->on('enriched_records_contacts');
            $table->enum('HomeOwnershipStatus', ['True', 'False', 'Unknown'])->nullable();
            $table->enum('PrimaryPropertyType', ['E', 'F', 'U', 'S', 'A', 'C', 'H', 'R', 'T', '', 'Unknown'])->nullable();
            $table->string('PropertyValuation')->nullable();
            $table->string('PropertyCount')->default("0");
            $table->string('PropertyCountBucket')->nullable()->after('property_valuation_bucket')->nullable();
            $table->enum('HasResidentialAddress', ['True', 'False', 'Unknown'])->nullable();
            $table->enum('VehicleOwnershipStatus', ['True', 'False', 'Unkown'])->nullable();
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
        Schema::dropIfExists('enriched_records_assets');
    }
}
