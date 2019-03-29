@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div id="alert-section"></div>
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Upload New Audience') }} </h1></div>
                <div class="card-body">
                    <div class="alert alert-danger">
                        Your Audience File could not be found please go back to your dashboard and try again. <a href="/contact">Contact MeetPAT</a> if you need assistance.
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>

@endsection