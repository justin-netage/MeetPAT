<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class GoogleAudienceUploadQueue extends Model
{
    //
    protected $fillable = [
        'user_id',
        'custom_audience_id',
        'saved_audience_file_id',
        'status',
        'total_records'
    ];
}
