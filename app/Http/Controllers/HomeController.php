<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storage = \Storage::disk('s3')->putFile('reamdme', new File('../readme.md'));

        return response(var_dump($storage));

        //return view('home');
    }
}
