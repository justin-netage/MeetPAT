<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class InformationController extends Controller
{
    //

    public function how_it_works()
    {

        return view('information.how_it_works');
    }

    public function benefits()
    {

        return view('information.benefits');
    }

    public function insights()
    {

        return view('information.insights');
    }

    public function onboarding()
    {

        return view('information.onboarding');
    }

    public function pricing()
    {

        return view('information.pricing');
    }
}
