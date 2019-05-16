<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarkerStreetEnrichedRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barker_street_enriched_records', function (Blueprint $table) {
            $table->increments('id');
            $table->string('InputIdn')->nullable();
            $table->string('InputFirstName')->nullable();
            $table->string('InputSurname')->nullable();
            $table->string('InputPhone')->nullable();
            $table->string('InputEmail')->nullable();
            $table->string('CleanPhone')->nullable();
            $table->string('RecordKey')->nullable();
            $table->string('id6')->nullable();
            $table->enum('AgeGroup', ['Twenties', 'Thirties', 'Fourties', 'Fifties', 'Sixties', 'Senventies', 'Eighty +', 'Unknown'])->nullable();
            $table->enum('Gender', ['M', 'F', 'Unknown'])->nullable();;
            $table->enum('PopulationGroup', ['B', 'W', 'C', 'A', 'Unknown'])->nullable();
            $table->enum('DeceasedStatus', ['true', 'false', 'Unknown'])->nullable();
            $table->enum('Generation', ['Baby Boomer', 'Generation X', 'Xennials', 'Millennials', 'iGen', 'Unknown'])->nullable();
            $table->enum('MaritalStatus', ['true', 'false', 'Unknown'])->nullable();
            $table->enum('DirectorshipStatus', ['true', 'false', 'Unknown'])->nullable();
            $table->enum('HomeOwnerShipStatus', ['true', 'false', 'Unknown'])->nullable();
            $table->string('PropertyValuation')->nullable();
            $table->string('PropertyCount')->default("0");
            $table->enum('incomeBucket', ['R0 - R2 500', 'R2 500 - R5 000', 'R5 000 - R10 000', 'R10 000 - R20 000', 'R20 000 - R30 000', 'R30 000 - R40 000', 'R40 000 +', 'Unknown'])->nullable();
            $table->enum('LSMGroup', ['LSM00', 'LSM01', 'LSM02', 'LSM03', 'LSM04', 'LSM05', 'LSM06', 'LSM07', 'LSM08', 'LSM09', 'LSM10', 'Unknown'])->nullable();
            $table->enum('HasResidentialAddress', ['true', 'false', 'Unknown'])->nullable();
            $table->enum('Province', ['WC', 'EC', 'NC', 'G', 'FS', 'M', 'L', 'NW', 'KN', 'Unknown'])->nullable();
            $table->string('Area')->nullable();
            $table->string('Employer')->nullable();
            $table->enum('VehicleOwnerShipStatus', ['True', 'False', 'Unkown'])->nullable();;
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
        Schema::dropIfExists('barker_street_enriched_records');
    }
}
