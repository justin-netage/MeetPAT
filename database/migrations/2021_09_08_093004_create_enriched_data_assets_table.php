<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrichedDataAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_data_assets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('contact_id');
            $table->foreign('contact_id')->references('id')->on('enriched_data_contacts');
            $table->boolean('HomeOwnershipStatus')->nullable();
            $table->boolean('VehicleOwnershipStatus')->nullable();
            $table->string('PrimaryPropertyType', 1)->nullable();
            $table->bigInteger('PropertyValuation')->nullable();
            $table->string('PropertyValuationBucket', 23)->nullable();
            $table->smallInteger('PropertyCount')->nullable();
            $table->string('PropertyCountBucket', 6)->nullable();
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
        Schema::dropIfExists('enriched_data_assets');
    }
}
