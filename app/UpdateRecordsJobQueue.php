<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class UpdateRecordsJobQueue extends Model
{
    //
    protected $fillable = ['user_id', 'audience_file_id', 'status', 'records'];
}
