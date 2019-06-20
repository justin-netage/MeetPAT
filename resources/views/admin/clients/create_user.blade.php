@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1>{{ __('Create User') }}</h1></div>

                <div class="card-body">
                    <form method="POST" id="new-user-form" action="{{ route('create-user-save') }}" onsubmit="displayLoader();">
                        @csrf
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

                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                        </div>

                        <div class="form-group">
                            <label for="password">{{ __('Password') }}</label>

                                <div class="input-group">
                                    <input type="password" id="PasswordInput" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} PasswordInput" aria-describedby="passwordHelpBlock">
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
                                <input id="password-confirm" type="password" class="form-control{{ $errors->has('password-confirm') ? ' is-invalid' : '' }}" name="password_confirmation">
                        </div>

                        <div class="form-group mt-3">
                            <label class="container-label">Send password to user email address
                            <input type="checkbox" checked="checked" name="send_email">
                            <span class="checkmark"></span>
                            </label>
                            <label class="container-label">Give user api key
                            <input type="checkbox" name="give_api_key">
                            <span class="checkmark"></span>
                            </label>                            
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" id="create_user" class="btn btn-primary">
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