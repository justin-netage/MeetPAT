<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class FbAudienceUploadQueue extends Model
{
    //
    protected $fillable = [
        'user_id',
        'custom_audience_id',
        'saved_audience_file_id',
        'status',
        'batches',
        'batches_complete',
        'total_records',
    ];
}
