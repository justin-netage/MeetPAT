<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class FacebookJobQue extends Model
{
    //
    protected $fillable = ['user_id', 'total_audience', 'audience_captured', 'percentage_complete', 'job_status'];
}
