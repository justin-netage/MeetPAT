<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class UpdateAudienceFile extends Model
{
    //
    protected $fillable = ['user_id', 'audience_name', 'file_unique_name'];

    public function job() 
    {

        return $this->hasOne('\MeetPAT\UpdateRecordsJobQueue', 'audience_file_id', 'id');
    }
}
