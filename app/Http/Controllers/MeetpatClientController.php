<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class MeetpatClientController extends Controller
{
    // Main Pages

    public function main()
    {

        return view('client.main');
    }

    public function sync_platform()
    {

        return view('client.dashboard.sync');
    }
}
