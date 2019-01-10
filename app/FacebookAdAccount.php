<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class FacebookAdAccount extends Model
{
    //
    protected $fillable = ['user_id', 'ad_account_id', 'access_token'];
}
