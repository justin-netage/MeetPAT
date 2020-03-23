<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class SavedFilteredAudienceFile extends Model
{
    //
    protected $fillable = ["user_id", "file_name", "file_unique_name"];

    public function fb_audience_upload_job() {
        return $this->hasMany('\MeetPAT\FbAudienceUploadQueue', 'saved_audience_file_id');
    }

    public function google_audience_upload_job() {
        return $this->hasMany('\MeetPAT\GoogleAudienceUploadQueue', 'saved_audience_file_id');
    }

    
}
