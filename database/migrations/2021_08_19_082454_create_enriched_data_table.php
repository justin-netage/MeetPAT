<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnrichedDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('RecordKey');
            $table->string('ClientRecordID', 13);
            $table->string('id6', 6)->nullable();
            $table->string('FirstName')->nullable();
            $table->string('Middlename')->nullable();
            $table->string('Surname')->nullable();
            $table->string('CleanPhone', 15)->nullable();
            $table->string('Email1')->nullable();
            $table->string('Email2')->nullable();
            $table->string('Email3')->nullable();
            $table->string('MobilePhone1', 15)->nullable();
            $table->string('MobilePhone2', 15)->nullable();
            $table->string('MobilePhone3', 15)->nullable();
            $table->string('WorkPhone1', 15)->nullable();
            $table->string('WorkPhone2', 15)->nullable();
            $table->string('WorkPhone3', 15)->nullable();
            $table->string('HomePhone1', 15)->nullable();
            $table->string('HomePhone2', 15)->nullable();
            $table->string('HomePhone3', 15)->nullable();
            $table->string('ContactCategory', 9)->nullable();
            $table->string('Gender', 1)->nullable();
            $table->string('AgeGroup', 9)->nullable();
            $table->string('PopulationGroup', 1)->nullable();
            $table->string('CitizenshipIndicator', 8)->nullable();
            $table->boolean("DeceasedStatus")->nullable();
            $table->string("Generation")->nullable();
            $table->boolean("MaritalStatus")->nullable();
            $table->boolean("DirectorshipStatus")->nullable();
            $table->boolean("HomeOwnershipStatus")->nullable();
            $table->string("PrimaryPropertyType", 1)->nullable();
            $table->integer("PropertyValuation")->nullable();
            $table->string("PopertyValuationBucket", 23)->nullable();
            $table->smallInteger("PropertyCount")->nullable();
            $table->string("PropertyCountBucket", 6)->nullable();
            $table->integer("Income")->nullable();
            $table->string('IncomeBucket', 17)->nullable();
            $table->string('LSMGroup', 5);
            $table->boolean('HasResidentailAddress')->nullable();
            $table->string("Province", 2)->nullable();
            $table->string("Area")->nullable();
            $table->string("Municipality")->nullable();
            $table->string("Employer")->nullable();
            $table->boolean("VehicleOwnershipStatus")->nullable();
            $table->string("CreditRiskCategory", 9)->nullable();
            $table->json("custom_variable_1")->nullable()->default("{}");
            $table->json("custom_variable_2")->nullable()->default("{}");
            $table->json("custom_variable_3")->nullable()->default("{}");
            $table->json("custom_variable_4")->nullable()->default("{}");
            $table->string("InputIdn", 13)->nullable();
            $table->string("InputFirstName")->nullable();
            $table->string("InputSurname")->nullable();
            $table->string("InputPhone")->nullable();
            $table->string("InputEmail")->nullable();
            $table->longText("affiliated_users")->nullable();
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
        Schema::dropIfExists('enriched_data');
    }
}
