@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            @if(!\Auth::user()->facebook_ad_account)
            <a href="{{$login_url}}" class="btn btn-primary btn-lg btn-block rounded-0 shadow-block shadow-block">
                Authorize Facebook AD Account &nbsp;<i class="fas fa-mouse-pointer"></i>
            </a>
            @else
            <button id="deauthorizeFacebook" data-token="{{\Auth::user()->api_token}}" class="btn btn-danger btn-lg btn-block rounded-0 shadow-block shadow-block">
                Deauthorize Facebook AD Account &nbsp;<i class="fas fa-plug"></i>
            </button>
            @endif
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Sync Facebook Account') }} &nbsp;<i class="fab fa-facebook"></i> </h1></div>
                    <div class="card-body">
                        <div id="progress-sync"></div>
                        <!-- Make route /google/authenticate-code and add controller to save google ads account and access code -->
                        <form id="upload-custom-audience" method="post" action="/facebook-account-update/add-ad-account-id" onsubmit="displayLoader();">
                        @csrf
                        <div class="form-group">
                            <label for="ad_account_id">{{__('Facebook Ad Account ID') }}</label>
                            @if(\Auth::user()->facebook_ad_account)
                            <input type="text" name="ad_account_id" id="ad_account_id" placeholder="1234567890123456" value="{{\Auth::user()->facebook_ad_account->ad_account_id}}" class="form-control{{ $errors->has('ad_account_id') ? ' is-invalid' : '' }}" autofocus>
                            @else
                            <input type="text" name="ad_account_id" id="ad_account_id" placeholder="1234567890123456" value="{{ old('ad_account_id') }}" class="form-control{{ $errors->has('ad_account_id') ? ' is-invalid' : '' }}" autofocus>
                            @endif

                            @if ($errors->has('ad_account_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ad_account_id') }}</strong>
                                </span>
                            @endif
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" id="submit_id" class="btn btn-primary">
                                {{ __('Submit ID') }}
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
    $("#deauthorizeFacebook").click(function() {
        $("#deauthorizeFacebook").prop("disabled", 1);
        $("#deauthorizeFacebook").html(
                
                "<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span>"
                +"&nbsp;&nbsp;Deauthorizing..."
            )
        $.post("/api/meetpat-client/sync/facebook/deauthorize", {"api_token": $("#deauthorizeFacebook").data("token")},function(data) {
            
            console.log(data);
        }).fail(function(error) {
            $("#deauthorizeFacebook").html("Deauthorize Facebook AD Account &nbsp;<i class=\"fas fa-plug\"></i>");
            $("#deauthorizeFacebook").prop("disabled", 0);
            console.log(error);
        }).done(function() {
            $("#deauthorizeFacebook").removeClass("btn-danger");
            $("#deauthorizeFacebook").addClass("btn-success");
            $("#deauthorizeFacebook").html("Done");

            location.reload();
        });
    });
</script>
@endsection