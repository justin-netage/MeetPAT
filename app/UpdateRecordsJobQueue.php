<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class UpdateRecordsJobQueue extends Model
{
    //
    protected $fillable = ['user_id', 'audience_file_id', 'status', 'records'];

    public function audience_file()
    {
        return $this->belongsTo('\MeetPAT\UpdateAudienceFile', 'audience_file_id');
    }
}
