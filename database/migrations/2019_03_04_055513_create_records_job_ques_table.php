<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecordsJobQuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('records_job_ques', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('audience_file_id');
            $table->integer('user_id');
            $table->enum('status', ['pending','running','done'])->default('pending');
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
        Schema::dropIfExists('records_job_ques');
    }
}
