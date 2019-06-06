<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class BarkerStreetFile extends Model
{
    //
    protected $fillable = ['file_unique_name', 'audience_file_id', 'job_status', 'user_id', 'records_checked', 'records', 'records_completed'];
}
