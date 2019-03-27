@extends('layouts.app')

@section('side-bars')
<div class="right-sidebar" id="right-options-sidebar">
    <h4>
        <strong class="mr-auto">Contacts</strong>
    </h4>
    <div id="records-toast">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" style="color: #fff;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</div>
<div class="right-sidebar-button" id="sidebar-toggle-button"><i class="fas fa-cog"></i></div>
@endsection

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

        <a class="card-link card-link-secondary" href="/meetpat-client/upload-client-file-data">
            <div class="card border-secondary mb-5">
                <div class="card-header">Upload Client Data</div>
                <div class="card-body text-secondary">
                    <h5 class="card-title text-center"><i class="fas fa-file-upload"></i></h5>
                    <p class="card-text">Upload your audience file to view in MeetPAT Audience Records.</p>
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

@section('scripts')
<script>
    
    $(document).ready(function() {
        /** Sidebar toggling. */

        $('#sidebar-toggle-button').click(function() {
            $('#right-options-sidebar').toggleClass("sidebar-in");
            $('#sidebar-toggle-button').toggleClass("sidebar-button-in");
        });
    });
</script>
@endsection