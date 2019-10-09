@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">

        <a class="card-link card-link-secondary" href="/meetpat-client/upload-client-file-data">
            <div class="card border-secondary">
                <div class="card-header">Upload Audience</div>
                <div class="card-body text-secondary">
                    <h5 class="card-title text-center"><i class="fas fa-file-upload"></i></h5>
                    <p class="card-text">Upload a new audience to view in dashboard.</p>
                </div>
            </div>
        </a>

        <a class="card-link card-link-secondary" href="/meetpat-client/upload/update-custom-metrics">
            <div class="card border-secondary">
                <div class="card-header">Update Audience</div>
                <div class="card-body text-secondary">
                    <h5 class="card-title text-center"><i class="fas fa-sync-alt"></i></h5>
                    <p class="card-text">Update your current audience custom metric data.</p>
                </div>
            </div>
        </a>

    </div>
</div>

@endsection

@section('scripts')

@endsection