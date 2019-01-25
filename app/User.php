<?php

namespace MeetPAT;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function admin()
    {

        return $this->hasOne('\MeetPAT\Administrator');
    }

    public function client()
    {

        return $this->hasOne('\MeetPAT\MeetpatClient');
    }

    public function ad_account() 
    {
        return $this->hasOne('MeetPAT\FacebookAdAccount');
    }

    public function ad_word_account()
    {
        return $this->hasOne('MeetPAT\GoogleAdwordsAccount');
    }
}
