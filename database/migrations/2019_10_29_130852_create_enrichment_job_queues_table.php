<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEnrichmentJobQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('enrichment_job_queues', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('uploaded_to_bsa', ["yes", "no"])->default("no");
            $table->enum('has_record_matches', ["yes", "no"])->default("no");
            $table->integer('record_job_id');
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
        Schema::dropIfExists('enrichment_job_queues');
    }
}
