<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrichedRecordsMiscVariablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enriched_records_misc_variables', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger("contact_id");
            $table->foreign('contact_id')->references('contact_id')->on('enriched_records_contacts');
            $table->string("star_sign")->nullable();
            $table->string("birth_month")->nullable();
            $table->string("birth_date")->nullable();
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
        Schema::dropIfExists('enriched_records_misc_variables');
    }
}
