<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class SimilarAudienceCredit extends Model
{
    //
    protected $fillable = ['user_id', 'used_credits', 'credit_limit'];

}
