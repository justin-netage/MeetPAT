<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class FixFileJobQueue extends Model
{
    //
    protected $fillable = [ "user_id",
                            "file_uuid",
                            "status",
                            "valid_csv",
                            "over_limit",
                            "matches_template",
                            "bad_rows_count"
                        ];
}
