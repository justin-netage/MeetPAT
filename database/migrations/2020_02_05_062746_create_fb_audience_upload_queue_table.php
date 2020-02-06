<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbAudienceUploadQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_audience_upload_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('custom_audience_id');
            $table->integer('saved_audience_file_id');
            $table->enum('status', ['pending', 'processing', 'complete']);
            $table->integer('batches');
            $table->integer('batches_complete');
            $table->integer('total_records');
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
        Schema::dropIfExists('fb_audience_upload_queue');
    }
}
