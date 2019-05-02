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

    public function client_uploads()
    {
        return $this->hasOne('\MeetPAT\ClientUploads', 'user_id');
    }

    public function client_details()
    {
        return $this->hasOne('\MeetPAT\MeetpatClientDetail', 'user_id');
    }

    public function facebook_ad_account() 
    {
        return $this->hasOne('\MeetPAT\FacebookAdAccount', 'user_id');
    }

    public function google_ad_account()
    {
        return $this->hasOne('\MeetPAT\GoogleAdwordsAccount', 'user_id');
    }
}
