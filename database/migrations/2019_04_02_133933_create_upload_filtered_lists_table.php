<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadFilteredListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_filtered_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->enum('platform', ['google', 'facebook']);
            $table->enum('status', ['done', 'pending', 'started']);
            $table->integer('filtered_list_id');
            $table->string('audience_name');
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
        Schema::dropIfExists('upload_filtered_lists');
    }
}
