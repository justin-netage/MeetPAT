<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class EnrichedDataTracking extends Model
{
    //
    protected $fillable = ['user_id', 'sent', 'received', 'created_at'];
}
