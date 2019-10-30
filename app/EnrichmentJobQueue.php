<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class EnrichmentJobQueue extends Model
{
    //
    protected $fillable = ['uploaded_to_bsa', 'has_record_matches', 'record_job_id'];
}
