<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class DeleteUserJobQueue extends Model
{
    //
    protected $fillable = [
        "user_id",
        "status"
    ];
}
