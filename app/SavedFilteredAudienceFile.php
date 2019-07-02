<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class SavedFilteredAudienceFile extends Model
{
    //
    protected $fillable = ["user_id", "file_name", "file_unique_name"];
}
