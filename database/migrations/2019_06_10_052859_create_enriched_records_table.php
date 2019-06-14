<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrichedRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('RecordKey')->nullable();
            $table->string('ClientFileName')->nullable();
            $table->string('ClientRecordID')->nullable();
            $table->string('id6')->nullable();
            $table->string('FirstName')->nullable();
            $table->string('Middlename')->nullable();
            $table->string('Surname')->nullable();
            $table->string('CleanPhone')->nullable();
            $table->string('Email1')->nullable();
            $table->string('Email2')->nullable();
            $table->string('Email3')->nullable();
            $table->string('MobilePhone1')->nullable();
            $table->string('MobilePhone2')->nullable();
            $table->string('MobilePhone3')->nullable();
            $table->string('WorkPhone1')->nullable();
            $table->string('WorkPhone2')->nullable();
            $table->string('WorkPhone3')->nullable();
            $table->string('HomePhone1')->nullable();
            $table->string('HomePhone2')->nullable();
            $table->string('HomePhone3')->nullable();
            $table->enum('ContactCategory', ['Very Low', 'Low', 'Medium', 'High', 'Very High', 'Unkown'])->nullable();
            $table->enum('AgeGroup', ['Twenties', 'Thirties', 'Fourties', 'Fifties', 'Sixties', 'Senventies', 'Eighty +', 'Unknown'])->nullable();
            $table->enum('Gender', ['M', 'F', 'Unknown'])->nullable();;
            $table->enum('PopulationGroup', ['B', 'W', 'C', 'A', 'Unknown'])->nullable();
            $table->enum('DeceasedStatus', ['True', 'False', 'Unknown'])->nullable();
            $table->enum('Generation', ['Baby Boomer', 'Generation X', 'Xennials', 'Millennials', 'iGen', 'Unknown'])->nullable();
            $table->enum('MaritalStatus', ['True', 'False', 'Unknown'])->nullable();
            $table->enum('DirectorshipStatus', ['True', 'False', 'Unknown'])->nullable();
            $table->enum('HomeOwnershipStatus', ['True', 'False', 'Unknown'])->nullable();
            $table->string('PrimaryPropertyType')->nullable();
            $table->string('PropertyValuation')->nullable();
            $table->string('PropertyCount')->default("0");
            $table->string('Income')->nullable();
            $table->enum('IncomeBucket', ['R0 - R2 500', 'R2 500 - R5 000', 'R5 000 - R10 000', 'R10 000 - R20 000', 'R20 000 - R30 000', 'R30 000 - R40 000', 'R40 000 +', 'Unknown'])->nullable();
            $table->enum('LSMGroup', ['LSM00', 'LSM01', 'LSM02', 'LSM03', 'LSM04', 'LSM05', 'LSM06', 'LSM07', 'LSM08', 'LSM09', 'LSM10', 'Unknown'])->nullable();
            $table->enum('HasResidentialAddress', ['True', 'False', 'Unknown'])->nullable();
            $table->enum('Province', ['WC', 'EC', 'NC', 'G', 'FS', 'M', 'L', 'NW', 'KN', 'Unknown'])->nullable();
            $table->string('Area')->nullable();
            $table->string('Municipality')->nullable();
            $table->string('Employer')->nullable();
            $table->enum('VehicleOwnershipStatus', ['True', 'False', 'Unkown'])->nullable();
            $table->string('InputIdn')->nullable();
            $table->string('InputFirstName')->nullable();
            $table->string('InputSurname')->nullable();
            $table->string('InputPhone')->nullable();
            $table->string('InputEmail')->nullable();
            $table->string('affiliated_users')->nullable(); // comma separated string.
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
        Schema::dropIfExists('enriched_records');
    }
}