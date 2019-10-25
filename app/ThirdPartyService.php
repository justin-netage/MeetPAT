<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class ThirdPartyService extends Model
{
    //
    protected $fillable = ['service_name', 'description', 'status'];
}
