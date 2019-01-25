<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class GoogleAudienceFile extends Model
{
    //
    protected $fillable = ['user_id', 'audience_name', 'file_unique_name', 'file_source_origin'];

}
