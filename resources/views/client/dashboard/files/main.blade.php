@extends('layouts.app')

@section('content')

<div class="container" style="margin-bottom: 320px;">
    <div class="row justify-content-center mb-5">
        <a class="card-link card-link-orange" href="/meetpat-client/audiences/saved-audiences">
            <div class="card border-orange">
                <div class="card-header">Saved Audiences</div>
                <div class="card-body text-orange">
                    <h5 class="card-title text-center"><i class="fas fa-save"></i></h5>
                    <p class="card-text">View your saved audiences and upload them to Google and Facebook.</p>
                </div>
            </div>
        </a>
    </div>
</div>

@endsection