@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">

        <a class="card-link card-link-secondary" href="/meetpat-client/upload">
            <div class="card border-secondary">
                <div class="card-header">Upload Client Data</div>
                <div class="card-body text-secondary">
                    <h5 class="card-title text-center"><i class="fas fa-file-upload"></i></h5>
                    <p class="card-text">Upload your audience file to view in MeetPAT Dashboard or update existing audience variables.</p>
                </div>
            </div>
        </a>

        <a class="card-link card-link-primary" href="/meetpat-client/data-visualisation">
            <div class="card border-primary">
                <div class="card-header">Dashboard</div>
                <div class="card-body text-primary">
                    <h5 class="card-title text-center"><i class="far fa-chart-bar"></i></h5>
                    <p class="card-text">View your audience graphically on our Dashboard. Filter and save your target audiences.</p>
                </div>
            </div>
        </a>

        <a class="card-link card-link-success" href="/meetpat-client/settings">
            <div class="card border-success">
                <div class="card-header">Account Settings</div>
                <div class="card-body text-success">
                    <h5 class="card-title text-center"><i class="fas fa-sliders-h"></i></h5>
                    <p class="card-text">Update your profile and notification settings.</p>
                </div>
            </div>
        </a>

    </div>
</div>

@endsection