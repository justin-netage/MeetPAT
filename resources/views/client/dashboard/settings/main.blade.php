@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">

        <a class="card-link card-link-success" href="/meetpat-client/settings/notifications">
            <div class="card border-success">
                <div class="card-header">Notifications</div>
                <div class="card-body text-success">
                    <h5 class="card-title text-center"><i class="far fa-bell"></i></h5>
                    <p class="card-text">Change where your notifications get sent.</p>
                </div>
            </div>
        </a>
        @if(\Auth::user()->id == 101)
        <a class="card-link card-link-facebook" href="/meetpat-client/sync/facebook">
            <div class="card border-facebook">
                <div class="card-header">Synch Platform</div>
                <div class="card-body text-facebook">
                    <h5 class="card-title text-center"><i class="fab fa-facebook-f"></i></h5>
                    <p class="card-text">Synch your account with facebook to upload your custom audiences.</p>
                </div>
            </div>
        </a>
        @endif

    </div>
</div>

@endsection