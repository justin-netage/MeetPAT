<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaveFilesJobQueueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('save_files_job_queue', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->enum('status', ['processing', 'error', 'complete', 'pending']);
            $table->string('saved_file_id');
            $table->string('saved_filters_id');
            $table->string('number_of_records');
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
        Schema::dropIfExists('save_files_job_queue');
    }
}
