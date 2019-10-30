@extends('layouts.app')

@section('content')

@if(\Auth::user()->client)
<div class="container">
    <div class="row justify-content-center mb-5">

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

        <a class="card-link card-link-orange" href="/meetpat-client/files">
            <div class="card border-orange">
                <div class="card-header">Files</div>
                <div class="card-body text-orange">
                    <h5 class="card-title text-center"><i class="fas fa-folder"></i></h5>
                    <p class="card-text">View and download your saved audiences and previous file uploads.</p>
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
@elseif(\Auth::user()->admin)
<div class="container">
    @if(\MeetPAT\ThirdPartyService::find(1)->status == 'offline')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="alert alert-warning" role="alert">
                <p><i class="fas fa-exclamation-triangle"></i> BSA's SFTP Server is currently offline.</p>
            </div>
        </div>
    </div>
    @endif
    <div class="row justify-content-center mb-5">

        <a class="card-link card-link-secondary" href="/meetpat-admin/clients">
            <div class="card border-secondary">
                <div class="card-header">Clients</div>
                <div class="card-body text-secondary">
                    <h5 class="card-title text-center"><i class="fas fa-users"></i></i></h5>
                    <p class="card-text">View clients and change their configurations.</p>
                </div>
            </div>
        </a>

        <a class="card-link card-link-primary" href="/meetpat-admin/enriched-data-tracking">
            <div class="card border-primary">
                <div class="card-header">Enriched Data Tracking</div>
                <div class="card-body text-primary">
                    <h5 class="card-title text-center"><i class="fas fa-chart-line"></i></h5>
                    <p class="card-text">Enriched data tracking monthly and daily diplayed graphically.</p>
                </div>
            </div>
        </a>

    </div>
</div>
@else

None
@endif

@endsection