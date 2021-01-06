@extends('layouts.app')

@section('styles')
<style>
    span.step {
    background: #cccccc;
    border-radius: 0.8em;
    -moz-border-radius: 0.8em;
    -webkit-border-radius: 0.8em;
    color: #ffffff;
    display: inline-block;
    font-weight: bold;
    line-height: 1.6em;
    margin-right: 5px;
    text-align: center;
    width: 1.6em; 
    }

    #adAccountIdHelp
    {
        cursor: pointer;
    }
</style>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Sync Facebook Account') }} &nbsp;<i class="fab fa-facebook"></i> </h1></div>
                    <div class="card-body">
                        @if(!\Auth::user()->facebook_ad_account)
                        <div class="row">
                            <div class="col-1"><span class="step">1</span></div>
                            <div class="col-11"><p>You need to login with facebook and give permission to allow the MeetPAT app to create custom audiences in you ad account.</p></div>
                        </div>
                        <a href="{{$login_url}}" class="continue-with-facebook shadow-block shadow-block mb-2">
                        <i class="fab fa-facebook"></i>&nbsp;&nbsp;Continue With Facebook
                        </a>
                        @else
                        <div class="row">
                            <div class="col-1"></div>
                            <div class="col-11"><p>By clicking the button below you will disconnect your Facebook Ad account with MeetPAT.</p></div>
                        </div>
                        <button id="deauthorizeFacebook" data-token="{{\Auth::user()->api_token}}" class="btn btn-danger btn-lg btn-block rounded-0 shadow-block shadow-block mb-3">
                            Deauthorize Facebook AD Account &nbsp;<i class="fas fa-plug"></i>
                        </button>
                        @endif
                        <hr />
                        <div class="row">
                            <div class="col-1"><span class="step">2</span></div>
                            <div class="col-11">
                                <p>MeetPAT needs your AD Account ID to create a customer list after you've authorized the app with Facebook. You will find the AD account ID on the 
                                    <a href="https://business.facebook.com/adsmanager/manage/campaigns" target="_blank">Ads Manager Campaigns Page</a> in the top left corner. click on the drop down to see the full ID.
                                </p>
                            </div>
                        </div>
                        <img id="adAccountIdHelp" title="Click to view a larger picture." data-toggle="modal" data-target="#AdAccountIdHelpModal" src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/help/ad_account_id.png')}}" height="auto" width="100%" alt="meetpat-logo">
                        <form id="upload-custom-audience" method="post" action="/facebook-account-update/add-ad-account-id" onsubmit="displayLoader();">
                        @csrf
                        <div class="form-group">
                            <label for="ad_account_id">{{__('Facebook Ad Account ID') }}</label>

                            @if(\Auth::user()->facebook_ad_account)
                            <input type="text" name="ad_account_id" id="ad_account_id" placeholder="1234567890123456" value="{{\Auth::user()->facebook_ad_account->ad_account_id}}" class="form-control{{ $errors->has('ad_account_id') ? ' is-invalid' : '' }}" aria-describedby="AdAccountIdHelpBlock" autofocus>
                            @else
                            <input type="text" name="ad_account_id" id="ad_account_id" placeholder="1234567890123456" value="{{ old('ad_account_id') }}" aria-aria-describedby="AdAccountIdHelpBlock" class="form-control{{ $errors->has('ad_account_id') ? ' is-invalid' : '' }}" aria-describedby="AdAccountIdHelpBlock" readonly>
                            <small id="AdAccountIdHelpBlock" class="form-text text-danger">
                                First authorize MeetPAT with Facebook in step one.
                            </small>
                            @endif

                            @if ($errors->has('ad_account_id'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('ad_account_id') }}</strong>
                                </span>
                            @endif
                            
                        </div>
                        <div class="form-group mb-0">
                        @if(\Auth::user()->facebook_ad_account)
                        <button type="submit" id="submit_id" class="btn btn-lg btn-primary btn-block">
                            {{ __('Submit ID') }}
                        </button>
                        @else
                        <button type="submit" id="submit_id" class="btn btn-lg btn-primary btn-block disabled" disabled>
                            {{ __('Submit ID') }}
                        </button>
                        @endif

                            
                        </div>
                    </form>
                    </div>
                </div>        
            </div>
        </div>
    </div>
</div>

@endsection

@section('modals')
<!-- Modal -->
<div class="modal fade" id="AdAccountIdHelpModal" tabindex="-1" role="dialog" aria-labelledby="AdAccountIdHelpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="AdAccountIdHelpModalLabel">How to find you Ad Account ID</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <img id="adAccountIdHelp" src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/help/ad_account_id.png')}}" height="auto" width="100%" alt="meetpat-logo">
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

    $("#submit_id").click(function() { 
        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    });

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