@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center mb-5">
        <a class="card-link card-link-orange" href="/meetpat-client/files/uploaded-audience-files">
            <div class="card border-orange">
                <div class="card-header">Uploaded Audience Files</div>
                <div class="card-body text-orange">
                    <h5 class="card-title text-center"><i class="fas fa-cloud-upload-alt"></i></i></h5>
                    <p class="card-text">View and download your previous file uploads.</p>
                </div>
            </div>
        </a>
        <a class="card-link card-link-orange" href="/meetpat-client/files/saved-audience-files">
            <div class="card border-orange">
                <div class="card-header">Saved Audience Files</div>
                <div class="card-body text-orange">
                    <h5 class="card-title text-center"><i class="fas fa-save"></i></h5>
                    <p class="card-text">View and download your saved audiences.</p>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection