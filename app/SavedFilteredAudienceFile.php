<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class SavedFilteredAudienceFile extends Model
{
    //
    protected $fillable = ["user_id", "file_name", "file_unique_name", "total_records"];

    public function fb_audience_upload_job() {
        return $this->hasMany('\MeetPAT\FbAudienceUploadQueue', 'saved_audience_file_id');
    }

    public function google_audience_upload_job() {
        return $this->hasMany('\MeetPAT\GoogleAudienceUploadQueue', 'saved_audience_file_id');
    }

    public function save_file_job() {
        return $this->hasOne('\MeetPAT\SaveFilesJobQueue', 'saved_file_id');
    }

    
}
