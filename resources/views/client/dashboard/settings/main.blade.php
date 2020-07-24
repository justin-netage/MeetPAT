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
        <a class="card-link card-link-facebook" href="/meetpat-client/sync/facebook">
            <div class="card border-facebook">
                <div class="card-header">Synch With Facebook</div>
                <div class="card-body text-facebook">
                    <h5 class="card-title text-center"><i class="fab fa-facebook-f"></i></h5>
                    <p class="card-text">Synch your account with facebook to upload your customer list.</p>
                </div>
            </div>
        </a>
        <a class="card-link card-link-google" href="/meetpat-client/sync/google">
            <div class="card border-google">
                <div class="card-header">Synch With Google</div>
                <div class="card-body text-google">
                    <h5 class="card-title text-center"><i class="fab fa-google"></i></h5>
                    <p class="card-text">Synch your account with google to upload your custom audience.</p>
                </div>
            </div>
        </a>
        <!-- <a class="card-link card-link-secondary" href="/meetpat-client/settings/business-details">
            <div class="card border-secondary">
                <div class="card-header">Synch With Google</div>
                <div class="card-body text-secondary">
                    <h5 class="card-title text-center"><i class="fas fa-file-invoice"></i></h5>
                    <p class="card-text">Update business Details.</p>
                </div>
            </div>
        </a> -->
    </div>
</div>

@endsection