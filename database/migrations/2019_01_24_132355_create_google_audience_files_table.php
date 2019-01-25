<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoogleAudienceFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_audience_files', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('audience_name');
            $table->string('file_unique_name');
            $table->enum('file_source_origin', ['customers_and_partners', 'directly_from_customers', 'from_partners']);
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
        Schema::dropIfExists('google_audience_files');
    }
}
