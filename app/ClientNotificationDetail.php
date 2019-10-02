<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class ClientNotificationDetail extends Model
{
    //
    protected $fillable = ['contact_first_name','contact_last_name','contact_email', 'user_id'];
}
