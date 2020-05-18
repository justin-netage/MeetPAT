<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class RecordsJobQue extends Model
{
    //
    protected $fillable = ['audience_file_id', 'user_id', 'status', 'records', 'records_completed'];

    public function audience_file()
    {
        return $this->belongsTo('\MeetPAT\AudienceFile', 'audience_file_id');
    }

    public function process_tracking()
    {
        return $this->hasMany('\MeetPAT\ProcessTracking', 'job_id');
    }

    public function user()
    {
        return $this->belongsTo('\MeetPAT\User');
    }
}
