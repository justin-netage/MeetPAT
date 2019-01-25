@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <a href="{{$auth_uri}}" target="_blank" class="btn btn-danger btn-lg btn-block rounded-0 shadow-block shadow-block">Get Authorization Code</a>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Sync Google Ads Account') }} &nbsp;<i class="fab fa-google"></i> </h1></div>
                    <div class="card-body">
                        <div id="progress-sync"></div>
                        <!-- Make route /google/authenticate-code and add controller to save google ads account and access code -->
                        <form id="upload-custom-audience" action="/google/authenticate-code" onsubmit="displayLoader();">
                        @csrf
                        <div class="form-group">
                            <label for="auth_code">{{ __('Authorization Code') }}</label>

                            <input id="auth_code" type="text" placeholder="Enter your Authorization Code" max="1000" class="form-control{{ $errors->has('auth_code') ? ' is-invalid' : '' }}" name="auth_code" value="{{ old('auth_code') }}" autofocus>

                            @if ($errors->has('auth_code'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('auth_code') }}</strong>
                                </span>
                            @endif
                            <span class="invalid-feedback" role="alert">
                                <strong id="invalid-audience-name">Your <b>Authorization Code</b> is Invalid</strong>
                            </span>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" id="create_user" class="btn btn-primary">
                                {{ __('Submit Code') }}
                            </button>
                        </div>
                    </form>
                    </div>
                </div>        
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
    var displayLoader = function () {
            $("#loader").css("display", "block");
        };
</script>
@endsection