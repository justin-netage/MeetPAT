<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    //

    protected $fillable = ['user_id', 'active'];

    public function clients() {
        return $this->hasMany('\MeetPAT\MeetpatClient', 'reseller_id');
    }
}
