@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Sync Google Account') }} &nbsp;<i class="fab fa-google"></i> </h1></div>
                    <div class="card-body">
                        <div id="progress-sync"></div>
                        <!-- Make route /google/authenticate-code and add controller to save google ads account and access code -->
                        <form id="upload-custom-audience" method="post" action="/google-authorization/authenticate-authorization-code" onsubmit="displayLoader();">
                        @csrf
                        <input type="hidden" name="user_id" value="{{\Auth::user()->id}}">
                        <div class="form-group">
                            <label for="adwords_id">{{__('Google Ads Account ID') }}</label>
                            <input type="text" name="adwords_id" id="adwords-id" placeholder="123-456-7890" value="{{ old('adwords_id') }}" class="form-control{{ $errors->has('adwords_id') ? ' is-invalid' : '' }}" autofocus>
                            @if ($errors->has('adwords_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('adwords_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="input-group mb-3">
                            <label for="auth_code" class="w-100">{{ __('Authorization Code') }}</label>

                            <input id="auth_code" name="auth_code" type="text" placeholder="Enter your Authorization Code" max="1000" class="form-control{{ $errors->has('auth_code') ? ' is-invalid' : '' }}" name="auth_code" value="{{ old('auth_code') }}">
                            <div class="input-group-append">
                                <a href="{{$auth_uri}}" target="_blank" class="btn btn-outline-danger shadow-block" type="button" id="button-addon2">Get Code</a>
                            </div>
                            @if ($errors->has('auth_code'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('auth_code') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" id="submit_id" class="btn btn-primary">
                                {{ __('Submit') }}
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