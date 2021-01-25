<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class CancelledJob extends Model
{
    //
    protected $fillable = [
        'job_id',
        'admin_id'
    ];
}
