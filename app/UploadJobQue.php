<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class UploadJobQue extends Model
{
    //
    protected $fillable = ['user_id', 'unique_id', 'platform', 'status', 'file_id'];

}
