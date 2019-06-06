<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarkerStreetFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barker_street_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_unique_name');
            $table->string('audience_file_id');
            $table->enum('job_status', ['pending', 'running', 'complete', 'error']);
            $table->integer('user_id');
            $table->integer('records_checked')->default(0);
            $table->integer('records')->default(0);
            $table->integer('records_completed')->default(0);
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
        Schema::dropIfExists('barker_street_files');
    }
}
