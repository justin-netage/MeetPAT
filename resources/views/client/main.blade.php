@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">

        <a class="card-link card-link-primary" href="/meetpat-client/data-visualisation">
            <div class="card border-primary mb-5">
                <div class="card-header">Records</div>
                <div class="card-body text-primary">
                    <h5 class="card-title text-center"><i class="far fa-chart-bar"></i></h5>
                    <p class="card-text">View your audience targets visually on our Records Dashboard</p>
                </div>
            </div>
        </a>

        <a class="card-link card-link-secondary" href="#">
            <div class="card border-secondary mb-5">
                <div class="card-header">Tutorials</div>
                <div class="card-body text-secondary">
                    <h5 class="card-title text-center"><i class="fas fa-graduation-cap"></i></h5>
                    <p class="card-text">Learn more about using MeetPAT's Audience Targeting.</p>
                </div>
            </div>
        </a>

        <a class="card-link card-link-success" href="#">
            <div class="card border-success mb-5">
                <div class="card-header">Account Settings</div>
                <div class="card-body text-success">
                    <h5 class="card-title text-center"><i class="fas fa-sliders-h"></i></h5>
                    <p class="card-text">Update your account settings, e.g company details, Google & Facebook Ad Account credentials...</p>
                </div>
            </div>
        </a>

    </div>
</div>

@endsection