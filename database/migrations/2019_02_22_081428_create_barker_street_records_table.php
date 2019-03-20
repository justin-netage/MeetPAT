<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarkerStreetRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barker_street_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('Idn')->nullable();
            $table->string('FirstName')->nullable();
            $table->string('Surname')->nullable();
            $table->string('MobilePhone1')->nullable();
            $table->string('MobilePhone2')->nullable();
            $table->string('MobilePhone3')->nullable();
            $table->string('WorkPhone1')->nullable();
            $table->string('WorkPhone2')->nullable();
            $table->string('WorkPhone3')->nullable();
            $table->string('HomePhone1')->nullable();
            $table->string('HomePhone2')->nullable();
            $table->string('HomePhone3')->nullable();
            $table->enum('AgeGroup', ['Twenties', 'Thirties', 'Fourties', 'Fifties', 'Sixties', 'Senventies', 'Eighty +', 'Unknown'])->nullable();
            $table->enum('GenerationGroup', ['Baby Boomer', 'Generation X', 'Xennials', 'Millennials', 'iGen', 'Unknown'])->nullable();
            $table->enum('Gender', ['M', 'F', 'Unknown'])->nullable();
            $table->enum('PopulationGroup', ['B', 'W', 'C', 'A', 'Unknown'])->nullable();
            $table->enum('DeceasedStatus', ['true', 'false', 'Unknown'])->nullable();
            $table->enum('MaritalStatus', ['true', 'false', 'Unknown'])->nullable();
            $table->enum('DirectorshipStatus', ['true', 'false', 'Unknown'])->nullable();
            $table->enum('HomeOwnerShipStatus', ['true', 'false', 'Unknown'])->nullable();
            $table->integer('income')->nullable();
            $table->enum('incomeBucket', ['R0 - R2 500', 'R2 500 - R5 000', 'R5 000 - R10 000', 'R10 000 - R20 000', 'R20 000 - R30 000', 'R30 000 - R40 000', 'R40 000 +', 'Unknown'])->nullable();
            $table->enum('LSMGroup', ['LSM00', 'LSM01', 'LSM02', 'LSM03', 'LSM04', 'LSM05', 'LSM06', 'LSM07', 'LSM08', 'LSM09', 'LSM10', 'Unknown'])->nullable();
            $table->enum('CreditRiskCategory', ['VERY_LOW', 'LOW', 'MEDIUM', 'HIGH', 'VERY_HIGH', 'Unknown'])->nullable();
            $table->enum('ContactCategory', ['VERY_LOW', 'LOW', 'MEDIUM', 'HIGH', 'VERY_HIGH', 'Unknown'])->nullable();
            $table->enum('HasMobilePhone', ['true', 'false', 'Unknown'])->nullable();
            $table->enum('HasResidentialAddress', ['true', 'false', 'Unknown'])->nullable();
            $table->enum('Province', ['WC', 'EC', 'NC', 'G', 'FS', 'M', 'L', 'NW', 'KN', 'Unknown'])->nullable();
            $table->string('GreaterArea')->nullable();
            $table->string('Area')->nullable();
            $table->string('ResidentialAddress1Line1')->nullable();
            $table->string('ResidentialAddress1Line2')->nullable();
            $table->string('ResidentialAddress1Line3')->nullable();
            $table->string('ResidentialAddress2Line4')->nullable();
            $table->string('ResidentialAddress2PostalCode')->nullable();
            $table->string('PostalAddress1Line1')->nullable();
            $table->string('PostalAddress1Line2')->nullable();
            $table->string('PostalAddress1Line3')->nullable();
            $table->string('PostalAddress1Line4')->nullable();
            $table->string('PostalAddress1PostalCode')->nullable();
            $table->string('email')->nullable();
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
        Schema::dropIfExists('barker_street_records');
    }
}
