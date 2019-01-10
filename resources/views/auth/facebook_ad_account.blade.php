@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 shadow-block p-0">
            <img src="{{\Storage::disk('s3')->url('meetpat/public/images/auth/facebook-custom-audiences.png')}}" width="100%" height="auto"  />
            <a href="{{$login_url}}" class="btn btn-light facebook-button">
                Sign in with your <i class="fab fa-facebook-f"></i>acebook AD Account
            </a>
        </div>
    </div>
</div>




@endsection