<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class UploadFilteredList extends Model
{
    //
    protected $fillable = ['user_id', 'platform', 'status', 'filtered_list_id', 'audience_name'];
}
