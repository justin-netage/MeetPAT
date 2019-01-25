<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class GoogleAdwordsAccount extends Model
{
    //
    protected $fillable = ['user_id', 'ad_account_id', 'access_token'];

}
