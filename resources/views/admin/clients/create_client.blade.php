@extends('layouts.app')

@section('content')
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
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1>{{ __('Add Client') }}</h1></div>

                <div class="card-body">
                    <form method="POST" id="new-user-form" autocomplete="off" action="{{ route($route) }}" onsubmit="displayLoader();">
                        @csrf
                        <input type="hidden" name="reseller_id" value="{{\Auth::user()->id}}">
                        <div class="form-group">
                            <label for="email">{{ __('First Name') }}</label>

                                <input id="email" type="text" class="form-control{{ $errors->has('firstname') ? ' is-invalid' : '' }}" name="firstname" value="{{ old('firstname') }}" autofocus>

                                @if ($errors->has('firstname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('Last Name') }}</label>

                                <input id="email" type="text" class="form-control{{ $errors->has('lastname') ? ' is-invalid' : '' }}" name="lastname" value="{{ old('lastname') }}">

                                @if ($errors->has('lastname'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="form-group">
                            <label for="email">{{ __('E-Mail Address') }}</label>

                                <input id="email" type="email" autocomplete="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="form-group">
                            <label for="business_name">{{ __('Business Name') }}</label>

                                <input id="business_name" type="text" class="form-control{{ $errors->has('business_name') ? ' is-invalid' : '' }}" name="business_name" value="{{ old('business_name') }}">

                                @if ($errors->has('business_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('business_name') }}</strong>
                                    </span>
                                @endif
                        </div>
                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>

                                <div class="input-group">
                                    <input type="password" autocomplete="new-password" id="PasswordInput" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} PasswordInput" aria-describedby="passwordHelpBlock">
                                    <div class="input-group-append">
                                        <button id="GeneratePassword" class="btn btn-outline-secondary view-password GeneratePassword" type="button" onclick="generatePassword(this);" data-toggle="tooltip" data-placement="top" title="generate password"><i class="fas fa-random"></i></button>
                                    </div>
                                    <div class="input-group-append">
                                        <button id="ViewPassword" class="btn btn-outline-secondary view-password ViewPassword" onclick="viewPassword(this);" type="button" data-toggle="tooltip" data-placement="top" title="view password"><i class="fas fa-eye"></i></button>
                                    </div>
                                    <small id="passwordHelpBlock" class="form-text text-muted">
                                        Your password must be 8-20 characters long, contain letters, at least one number and symbol.
                                    </small>
                                    @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                                </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" autocomplete="new-password" class="form-control{{ $errors->has('password-confirm') ? ' is-invalid' : '' }}" name="password_confirmation">
                        </div>

                        <div class="form-group mt-3">
                            <label class="container-label">Send password to user email address
                            <input type="checkbox" name="send_email">
                            <span class="checkmark"></span>
                            </label>
                            <label class="container-label">Give user api key
                            <input type="checkbox" checked="checked" name="give_api_key">
                            <span class="checkmark"></span>
                            </label>                            
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" id="create_user" class="btn btn-block btn-primary">
                                {{ __('Create New User') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>

    function displayLoader() {
        $("#loader").css("display", "block");
    }
    // FeatureMethods

    function viewPassword(current_el) {

        var password_el = $('#PasswordInput');
        var password_icon_el = $('i', '#ViewPassword');

        if(password_el.attr('type') == 'password')
        {
            password_el.attr({type: 'text'});
            password_icon_el.removeClass();
            password_icon_el.addClass('fas fa-eye-slash');

        } else {
            password_el.attr({type: 'password'});
            password_icon_el.removeClass();
            password_icon_el.addClass('fas fa-eye');
        }

}

function generatePassword(current_el) {

    const Http = new XMLHttpRequest();
    const url = '/api/meetpat-admin/generate-password';
    Http.overrideMimeType("application/json");    
    Http.open("GET", url, true);
    Http.send();
    Http.onload = function() {
    var jsonResponse = JSON.parse(Http.responseText);
    $('#PasswordInput').val(jsonResponse["password"]);
    $('#password-confirm').val(jsonResponse["password"]);
    
    //console.log(jsonResponse["password"]);
    }
};
</script>

@endsection