<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadJobQuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_job_ques', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('unique_id');
            $table->enum('platform', ['google', 'facebook']);
            $table->enum('status', ['done', 'pending', 'started']);
            $table->integer('file_id');
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
        Schema::dropIfExists('upload_job_ques');
    }
}
