<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class ProcessTracking extends Model
{
    //
    protected $fillable = ['job', 'job_id', 'status', 'records_result'];
}
