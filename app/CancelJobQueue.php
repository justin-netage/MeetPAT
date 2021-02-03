<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class CancelJobQueue extends Model
{
    //
    protected $fillable = [
        "job_id",
        "status"
    ];
}
