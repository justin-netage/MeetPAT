@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Sync Google Account') }} &nbsp;<i class="fab fa-google"></i> </h1></div>
                    <div id="alert-section"></div>
                    <div class="card-body">
                        <form id="upload-custom-audience" onsubmit="this.preventDefault();" novalidate>
                        @csrf
                        <input type="hidden" name="user_id" id="user_id" value="{{\Auth::user()->id}}">
                        <div class="form-group">
                            <label for="adwords_id">{{__('Google Ads Account ID') }}</label>
                            <input type="text" name="adwords_id" id="adwords-id" placeholder="123-456-7890" autocomplete="off" value="{{ old('adwords_id') }}" class="form-control{{ $errors->has('adwords_id') ? ' is-invalid' : '' }}" autofocus>
                            <span class="invalid-feedback" role="alert">
                                <strong>Your Google Ads Account ID is required and must be in the correct format</strong>
                            </span>
                        </div>
                        <div class="input-group mb-3">
                            <label for="auth_code" class="w-100">{{ __('Authorization Code') }}</label>

                            <input id="auth-code" name="auth_code" type="text" placeholder="Enter your Authorization Code" max="1000" autocomplete="off" class="form-control{{ $errors->has('auth_code') ? ' is-invalid' : '' }}" name="auth_code" value="{{ old('auth_code') }}">
                            <div class="input-group-append">
                                <a href="{{$auth_uri}}" target="_blank" class="btn btn-outline-danger shadow-block" id="button-addon2">Get Code</a>
                            </div>
                            <span class="invalid-feedback" role="alert">
                                <strong>Please get your authorization code from Google by clicking "Get Code. Then copy and paste it."</strong>
                            </span>
                        </div>
                        <div class="form-group mb-0">
                            <button type="button" id="submit_id" disabled="disabled" class="btn btn-primary">
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
    $(document).ready(function() {
        var checkForm = function() {
            
            if($('#adwords-id').is(':invalid') || $('#auth-code').is(':invalid')) {
                $("#submit_id").prop('disabled', true);
            } else if($('#adwords-id').is(':valid') && $('#auth-code').is(':valid')) {
                $("#submit_id").prop('disabled', false);
            } else {
                $("#submit_id").prop('disabled', false);
            }

        }
        document.getElementById('adwords-id').setCustomValidity('Invalid');
        document.getElementById('auth-code').setCustomValidity('Invalid');

        $("#submit_id").on('click', function() {
            $("#submit_id").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;&nbsp;Submitting...')
            $.post('/api/google-authorization/authenticate-authorization-code',
             {adwords_id: $('#adwords-id').val(), auth_code: $('#auth-code').val(), user_id: $('#user_id').val()},
             
             function( data ) {

            }).fail(function( error ) {
                $('#alert-section').html(
                    `<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> ${error.responseJSON.message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>`
                );
                $("#submit_id").html('Submit');
                console.log( error );
            }).done(function( data ) {
                $('#alert-section').html(
                    `<div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> ${data.message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    </div>`
                );
                $("#submit_id").html('Submit');
                console.log( data );
            });
        });

        $("#adwords-id").on('keyup change', function() {

            if($(this).val().match(/^([\d]){3,}([\-]){1,}([\d]){3,}([\-]){1,}([\d]){3,}$/)) {
                this.setCustomValidity('');
                $(this).addClass('is-valid');
                $(this).removeClass('is-invalid');
            } else {
                this.setCustomValidity('Invalid');
                $(this).removeClass('is-valid');
                $(this).addClass('is-invalid');
            }
            checkForm();

        });

        $("#auth-code").on('keyup change', function() {

            if($("#auth-code").val().length > 0) {
                this.setCustomValidity('');
                $(this).addClass('is-valid');
                $(this).removeClass('is-invalid');
            } else {
                this.setCustomValidity('Invalid');
                $(this).removeClass('is-valid');
                $(this).addClass('is-invalid');
            }
            checkForm();

        });


    });
</script>
@endsection