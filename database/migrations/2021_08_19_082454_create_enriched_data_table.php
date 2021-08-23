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
            $table->char('ClientRecordID', 13);
            $table->char('id6', 6)->nullable();
            $table->string('FirstName')->nullable();
            $table->string('Middlename')->nullable();
            $table->string('Surname')->nullable();
            $table->char('CleanPhone', 15)->nullable();
            $table->string('Email1')->nullable();
            $table->string('Email2')->nullable();
            $table->string('Email3')->nullable();
            $table->char('MobilePhone1', 15)->nullable();
            $table->char('MobilePhone2', 15)->nullable();
            $table->char('MobilePhone3', 15)->nullable();
            $table->char('WorkPhone1', 15)->nullable();
            $table->char('WorkPhone2', 15)->nullable();
            $table->char('WorkPhone3', 15)->nullable();
            $table->char('HomePhone1', 15)->nullable();
            $table->char('HomePhone2', 15)->nullable();
            $table->char('HomePhone3', 15)->nullable();
            $table->char('ContactCategory', 9)->nullable();
            $table->char('Gender', 1)->nullable();
            $table->char('AgeGroup', 9)->nullable();
            $table->char('PopulationGroup', 1)->nullable();
            $table->char('CitizenshipIndicator', 8)->nullable();
            $table->boolean("DeceasedStatus")->nullable();
            $table->string("Generation")->nullable();
            $table->boolean("MaritalStatus")->nullable();
            $table->boolean("DirectorshipStatus")->nullable();
            $table->boolean("HomeOwnershipStatus")->nullable();
            $table->char("PrimaryPropertyType", 1)->nullable();
            $table->integer("PropertyValuation")->nullable();
            $table->char("PopertyValuationBucket", 23)->nullable();
            $table->smallInteger("PropertyCount")->nullable();
            $table->char("PropertyCountBucket", 6)->nullable();
            $table->integer("Income")->nullable();
            $table->char('IncomeBucket', 17)->nullable();
            $table->char('LSMGroup', 5);
            $table->boolean('HasResidentailAddress')->nullable();
            $table->char("Province", 2)->nullable();
            $table->string("Area")->nullable();
            $table->string("Municipality")->nullable();
            $table->string("Employer")->nullable();
            $table->boolean("VehicleOwnershipStatus")->nullable();
            $table->char("CreditRiskCategory", 9)->nullable();
            $table->json("custom_varialbe_1")->nullable()->default("{}");
            $table->json("custom_varialbe_2")->nullable()->default("{}");
            $table->json("custom_varialbe_3")->nullable()->default("{}");
            $table->json("custom_varialbe_4")->nullable()->default("{}");
            $table->char("InputIdn", 13)->nullable();
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
