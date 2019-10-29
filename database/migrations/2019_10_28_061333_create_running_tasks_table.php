<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRunningTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('running_tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->enum("priority", ["low", "medium", "high"]);
            $table->enum("task", ["file_job", "enrichment_upload", "enrichement_import"]);
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
        Schema::dropIfExists('running_tasks');
    }
}
