<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class ClientUploads extends Model
{
    //
    protected $fillable = ['user_id', 'uploads', 'upload_limit'];
}
