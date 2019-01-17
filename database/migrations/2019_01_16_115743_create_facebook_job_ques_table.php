<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFacebookJobQuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facebook_job_ques', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('total_audience');
            $table->integer('audience_captured');
            $table->integer('percentage_complete');
            $table->enum('job_status', ['busy', 'done', 'ready']);
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
        Schema::dropIfExists('facebook_job_ques');
    }
}
