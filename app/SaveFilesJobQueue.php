<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class SaveFilesJobQueue extends Model
{
    //
    protected $fillable = ["user_id", "status", "saved_file_id", "saved_filters_id", "number_of_records"];
}
