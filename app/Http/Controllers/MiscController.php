<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller
{
    //
    public function generate_password()
    {
        //Initialize the random password
        $password = '';

        //Initialize a random desired length
        $desired_length = rand(8, 12);

        for($length = 0; $length < $desired_length; $length++) {
            //Append a random ASCII character (including symbols)
            $password .= chr(rand(32, 126));
        }
     
        return response()->json(['password' => $password]);
    }
}
