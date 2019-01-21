<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class FacebookJobQue extends Model
{
    //
    protected $fillable = ['user_id', 'facebook_audience_file_id', 'total_audience', 'audience_captured', 'percentage_complete', 'job_status'];

    public function custom_audience()
    {
        return $this->hasOne('MeetPAT\FacebookAudienceFile', 'facebook_audience_file_id');
    }
}
