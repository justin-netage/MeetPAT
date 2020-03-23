<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleAudienceUploadQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_audience_upload_queues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('custom_audience_id');
            $table->integer('saved_audience_file_id');
            $table->enum('status', ['pending', 'processing', 'complete']);
            $table->integer('total_records')->nullable();
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
        Schema::dropIfExists('google_audience_upload_queues');
    }
}
