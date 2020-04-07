<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFixFileJobQueuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fix_file_job_queues', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('file_uuid');
            $table->enum('status', ['complete', 'pending', 'processing', 'error'])->default('pending');
            $table->boolean('valid_csv')->default(0);
            $table->boolean('over_limit')->default(0);
            $table->boolean('matches_template')->default(0);
            $table->integer('bad_rows_count')->default(0);
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
        Schema::dropIfExists('fix_file_job_queues');
    }
}
