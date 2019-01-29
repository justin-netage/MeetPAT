@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-6 p-0 ">
            <img src="{{\Storage::disk('s3')->url('meetpat/public/images/auth/facebook-custom-audiences-upload.png')}}" height="auto" width="100%" class="shadow-block" />
            @if($has_facebook_ad_account)
            <a href="/meetpat-client/update/facebook" class="btn btn-primary btn-lg btn-block rounded-0 shadow-block">Facebook Ad Account</a>
            @else
            <a href="/register-facebook-ad-account" class="btn btn-primary btn-lg btn-block rounded-0 shadow-block">
                Register with your Facebook AD Account
            </a>
            @endif
        </div>
        <div class="col-md-6 p-0">
            <img src="{{\Storage::disk('s3')->url('meetpat/public/images/auth/google-similar-audiences.jpg')}}" height="auto" width="100%" class="shadow-block" />
            @if($has_google_ad_account)
            <a href="/meetpat-client/update/google" class="btn btn-danger btn-lg btn-block rounded-0 shadow-block shadow-block">Google Adwords Account</a>
            @else
            <a href="/register-google-ad-account" class="btn btn-danger btn-lg btn-block rounded-0 shadow-block">Register with you Google Adwords account</a>
            @endif
        </div>
    </div>
</div>

@endsection