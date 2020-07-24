@extends('layouts.app')

@section('content')

<div class="container">
    <div class="col-md-6 offset-md-3">
        <div class="card">
            <div class="card-header">Account Settings</div>

            <div class="card-body">
                <ul class="list-unstyled account-settings-list">
                  
                    <h4>Account Details</h4>
                    <hr>
                    <li id="companyDetails" class="company-details">
                        <form id="saveChangesForm">
                            @csrf
                            <input type="hidden" name="user_id" value="{{\Auth::user()->id}}">                            
                            <h5>Personal Information</h5>
                            <hr>
                            <div class="form-row mb-2">
                                <div class="col">
                                    <label for="clientFirstName">First Name</label>
                                    @if($has_business_details)
                                    <input type="text" class="form-control" name="client_first_name" id="clientFirstName" placeholder="First Name" value="{{$has_business_details->client_first_name}}">

                                    @else
                                    <input type="text" class="form-control" name="client_first_name" id="clientFirstName" placeholder="First Name" value="">
                                    @endif
                                        <div class="invalid-feedback">
                                            Please enter first name.
                                        </div>    
                                </div>
                                <div class="col">
                                    <label for="clientLastName">Last Name</label>
                                    @if($has_business_details)
                                    <input type="text" class="form-control" name="client_last_name" id="clientLastName" placeholder="Last Name" value="{{$has_business_details->client_last_name}}">
                                    @else
                                    <input type="text" class="form-control" name="client_last_name" id="clientLastName" placeholder="Last Name" value="">
                                    @endif
                                        <div class="invalid-feedback">
                                            Please enter a last name.
                                        </div>                                
                                </div>
                            </div>
                            <div class="form-row mb-2">
                                <div class="col">
                                    <label for="clientEmailAddress">Email Address</label>
                                    @if($has_business_details)
                                    <input type="email" name="client_email_address" id="clientEmailAddress" class="form-control" placeholder="meet@pat.co.za" value="{{$has_business_details->client_email_address}}">

                                    @else
                                    <input type="email" name="client_email_address" id="clientEmailAddress" class="form-control" placeholder="meet@pat.co.za" value="">

                                    @endif
                                        <div class="invalid-feedback">
                                            Please choose a valid email address.
                                        </div>                                
                                </div>
                                <div class="col">
                                    <label for="clientContactNumber">Contact Number</label>
                                    @if($has_business_details)
                                    <input type="text" name="client_contact_number" pattern="^(\+27)(\s)(\d){2}(\s)(\d){3}(\s)(\d){4}$" id="clientContactNumber" class="form-control" placeholder="+27 71 123 4567" value="{{$has_business_details->client_contact_number}}">

                                    @else
                                    <input type="text" name="client_contact_number" pattern="^(\+27)(\s)(\d){2}(\s)(\d){3}(\s)(\d){4}$" id="clientContactNumber" class="form-control" placeholder="+27 71 123 4567" value="">

                                    @endif
                                        <div class="invalid-feedback">
                                            Please choose a valid contact number in the format "+27 01 234 4567" dont leave out the spaces or the "+27".
                                        </div>                                
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="clientPostalAddress">Postal Address</label>
                                    @if($has_business_details)
                                    <textarea name="client_postal_address" id="clientPostalAddress" class="form-control">{{$has_business_details->client_postal_address}}</textarea>
                                    @else
                                    <textarea name="client_postal_address" id="clientPostalAddress" class="form-control"></textarea>
                                    @endif
                                    <div class="invalid-feedback">
                                        Please enter a postal address.
                                    </div>                                
                            </div>
                            <br />
                            <h5>Business Information</h5>
                            <hr>
                            <div class="form-row mb-2">
                                <label for="businessRegisteredName">Registered Business Name</label>
                                @if($has_business_details)
                                <input type="text" name="business_registered_name" id="businessRegisteredName" placeholder="MeetPAT Perfect Audience Targeting" class="form-control" value="{{$has_business_details->business_registered_name}}">

                                @else
                                <input type="text" name="business_registered_name" id="businessRegisteredName" placeholder="MeetPAT Perfect Audience Targeting" class="form-control" value="">

                                @endif
                                    <div class="invalid-feedback">
                                        Please enter a registered business name.
                                    </div>                                
                            </div>
                            <div class="form-row mb-2">
                                <div class="col">
                                    <label for="contactFirstName">Contact First Name</label>
                                    @if($has_business_details)
                                    <input type="text" name="contact_first_name" id="contactFirstName" class="form-control" placeholder="First Name" value="{{$has_business_details->contact_first_name}}">

                                    @else
                                    <input type="text" name="contact_first_name" id="contactFirstName" class="form-control" placeholder="First Name" value="">

                                    @endif
                                        <div class="invalid-feedback">
                                            Please enter a first name.
                                        </div>                                
                                </div>
                                <div class="col">
                                    <label for="ContactLastName">Contact Last Name</label>
                                    @if($has_business_details)
                                    <input type="text" name="contact_last_name" id="contactLastName" class="form-control" placeholder="Last Name" value="{{$has_business_details->contact_last_name}}">

                                    @else
                                    <input type="text" name="contact_last_name" id="contactLastName" class="form-control" placeholder="Last Name" value="">

                                    @endif
                                        <div class="invalid-feedback">
                                            Please enter a last name.
                                        </div>                                
                                </div>
                            </div>
                            <div class="form-row mb-2">
                                <div class="col">
                                    <label for="businessEmailAddress">Business Email Address</label>
                                    @if($has_business_details)
                                    <input type="email" name="contact_email_address" id="businessEmailAddress" class="form-control" placeholder="meet@pat.co.za" value="{{$has_business_details->contact_email_address}}">

                                    @else
                                    <input type="email" name="contact_email_address" id="businessEmailAddress" class="form-control" placeholder="meet@pat.co.za" value="">

                                    @endif
                                        <div class="invalid-feedback">
                                            Please enter a valid email address.
                                        </div>                                
                                </div>
                                <div class="col">
                                    <label for="businessContactNumber">Business Contact Number</label>
                                    @if($has_business_details)
                                    <input type="text" name="business_contact_number" pattern="^(\+27)(\s)(\d){2}(\s)(\d){3}(\s)(\d){4}$" id="businessContactNumber" class="form-control" placeholder="+27 71 123 4567" value="{{$has_business_details->business_contact_number}}">

                                    @else
                                    <input type="text" name="business_contact_number" pattern="^(\+27)(\s)(\d){2}(\s)(\d){3}(\s)(\d){4}$" id="businessContactNumber" class="form-control" placeholder="+27 71 123 4567" value="">

                                    @endif
                                        <div class="invalid-feedback">
                                            Please choose a valid contact number in the format "+27 01 234 4567" dont leave out the spaces or the "+27".
                                        </div>                                
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="businessRegistrationNumber">Registration Number</label>
                                @if($has_business_details)
                                <input type="text" name="business_registration_number" id="businessRegistrationNumber" class="form-control" value="{{$has_business_details->business_registration_number}}"/>

                                @else
                                <input type="text" name="business_registration_number" id="businessRegistrationNumber" class="form-control" value="" />

                                @endif
                                    <div class="invalid-feedback">
                                        Please enter a business registration number.
                                    </div>                                
                            </div>
                            <div class="form-group">
                                <label for="businessVatNumber">VAT Number</label>
                                @if($has_business_details)
                                <input type="text" name="business_vat_number" maxlength="10" minlength="10" id="businessVatNumber" class="form-control" value="{{$has_business_details->business_vat_number}}"/>

                                @else
                                <input type="text" name="business_vat_number" maxlength="10" minlength="10" id="businessVatNumber" class="form-control" value="" />

                                @endif
                                    <div class="invalid-feedback">
                                        Please enter a valid VAT number.
                                    </div>                                
                            </div>
                            <div class="form-group">
                                <label for="businessPhysicalAddress">Physical Address</label>
                                @if($has_business_details)
                                <textarea name="business_physical_address" id="businessPhysicalAddress" class="form-control">{{$has_business_details->business_physical_address}}</textarea>

                                @else
                                <textarea name="business_physical_address" id="businessPhysicalAddress" class="form-control"></textarea>

                                @endif
                                    <div class="invalid-feedback">
                                        Please enter a physical address.
                                    </div>                                
                            </div>
                            <div class="form-group">
                                <label for="businessPostalAddress">Postal Address</label>
                                @if($has_business_details)
                                <textarea name="business_postal_address" id="businessPostalAddress" class="form-control">{{$has_business_details->business_physical_address}}</textarea>

                                @else
                                <textarea name="business_postal_address" id="businessPostalAddress" class="form-control"></textarea>

                                @endif
                                <div class="invalid-feedback">
                                    Please enter a postal address.
                                </div>                                
                            </div>
                            <div class="form-group">
                                <button type="button" id="saveChangesBtn" class="btn btn-lg btn-primary btn-block">Save Changes</button>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Toasts -->

<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;">
  <!-- Position it -->
  <div style="position: fixed; bottom: 20%; right: 0;">

    <!-- Then put toasts within -->
    <div class="toast" id="success-toast" role="alert" data-delay="5000" aria-live="assertive" aria-atomic="true">
      <div class="toast-header">
      <i class="far fa-check-circle rounded mr-2 text-success"></i>
        <strong class="mr-auto">Success</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="toast-body">
        Your account details have been updated successfully!
      </div>
    </div>

</div>

@endsection

@section('scripts')

<script type="text/javascript">$(document).ready(function(){$("#saveChangesForm :input").each(function(){$(this).val()||this.setCustomValidity("Invalid")}),$("#clientFirstName, #contactFirstName, #clientLastName, #contactLastName, #businessRegisteredName").on("keyup change",function(){$(this).val().match(/^([a-zA-z ]){2,}$/)?(this.setCustomValidity(""),$(this).removeClass("is-invalid"),$(this).addClass("is-valid")):(this.setCustomValidity("Invalid Name"),$(this).removeClass("is-valid"),$(this).addClass("is-invalid"))}),$("#clientPostalAddress, #businessPostalAddress, #businessPhysicalAddress").on("keyup change",function(){$(this).val().length<2?(this.setCustomValidity("Invalid Address"),$(this).removeClass("is-valid"),$(this).addClass("is-invalid")):(this.setCustomValidity(""),$(this).removeClass("is-invalid"),$(this).addClass("is-valid"))}),$("#clientContactNumber, #businessContactNumber").on("keyup change",function(){$(this).val().match(/^(\+27)(\s)(\d){2}(\s)(\d){3}(\s)(\d){4}$/)?(this.setCustomValidity(""),$(this).removeClass("is-invalid"),$(this).addClass("is-valid")):(this.setCustomValidity("Invalid phone number"),$(this).removeClass("is-valid"),$(this).addClass("is-invalid"))}),$("#clientEmailAddress, #businessEmailAddress").on("keyup change",function(){$(this).val().match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/)?(this.setCustomValidity(""),$(this).removeClass("is-invalid"),$(this).addClass("is-valid")):(this.setCustomValidity("Invalid email address"),$(this).removeClass("is-valid"),$(this).addClass("is-invalid"))}),$("#businessRegistrationNumber").on("keyup change",function(){$(this).val().match(/^[\d]+$/)?(this.setCustomValidity(""),$(this).removeClass("is-invalid"),$(this).addClass("is-valid")):(this.setCustomValidity("Invalid business registration number"),$(this).removeClass("is-valid"),$(this).addClass("is-invalid"))}),$("#businessVatNumber").on("keyup change",function(){$(this).val().match(/^(\d){10}$/)?(this.setCustomValidity(""),$(this).removeClass("is-invalid"),$(this).addClass("is-valid")):(this.setCustomValidity("Invalid VAT number"),$(this).removeClass("is-valid"),$(this).addClass("is-invalid"))});var t=document.getElementById("facebookAccountId"),s=document.getElementById("googleAccountId");t&&$("#facebookAccountId").on("keyup change",function(){$(this).val().match(/^([\d\-])*$/)?(this.setCustomValidity(""),this.removeClass("in-invalid"),this.addClass("is-valid")):(this.setCustomValidity("Invalid"),$(this).removeClass("is-valid"),$(this).addClass("is-invalid"))}),s&&$("#googleAccountId").on("keyup change",function(){$(this).val().match(/^([\d]){3,}([\-]){1,}([\d]){3,}([\-]){1,}([\d]){3,}$/)?(this.setCustomValidity(""),$(this).removeClass("is-invalid"),$(this).addClass("is-valid")):(this.setCustomValidity("Invalid ID"),$(this).removeClass("is-valid"),$(this).addClass("is-invalid"))});var a=$("input[name=user_id]").val();$("#disconnectGoogle, #disconnectFacebook").click(function(){"facebook"==$(this).attr("data-platform")?($("#facebookSynced").html('<button type="button" id="disconnectFacebook" class="btn btn-danger btn-md" data-platform="facebook"><span class="spinner-border spinner-border-sm saving-status-loader text-light" role="status" aria-hidden="true"></span>&nbsp;disconnecting</button>'),$.post("/api/meetpat-client/settings/disconnect-platform",{user_id:a,platform:"facebook"},function(t){}).fail(function(t){$("#facebookSynced").html('<button type="button" id="disconnectFacebook" class="btn btn-danger btn-md" data-platform="facebook"><i class="fas fa-unlink"></i>&nbsp;disconnect</button>')}).done(function(t){$("#facebookSynced").html('<a href="/meetpat-client/sync/facebook" class="btn btn-light btn-md"><i class="fas fa-link"></i>&nbsp;connect</a>'),$("#facebookAccContainer").remove(),document.getElementById("facebookAccContainer")&&document.getElementById("googleAccContainer")||document.getElementById("facebookAccContainer")||document.getElementById("googleAccContainer")||$("#syncedPlatformContainer").remove()})):"google"==$(this).attr("data-platform")?($("#googleSynced").html('<button type="button" id="disconnectGoogle" class="btn btn-danger btn-md" data-platform="google"><span class="spinner-border spinner-border-sm status-loader text-light" role="status" aria-hidden="true"></span>&nbsp;disconnecting</button>'),$.post("/api/meetpat-client/settings/disconnect-platform",{user_id:a,platform:"google"},function(t){}).fail(function(t){$("#googleSynced").html('<button type="button" id="disconnectGoogle" class="btn btn-danger btn-md" data-platform="google"><i class="fas fa-unlink"></i>&nbsp;disconnect</button>')}).done(function(t){$("#googleSynced").html('<a href="/meetpat-client/sync/google" class="btn btn-light btn-md"><i class="fas fa-link"></i>&nbsp;connect</a>'),$("#googleAccContainer").remove(),document.getElementById("facebookAccContainer")&&document.getElementById("googleAccContainer")||document.getElementById("facebookAccContainer")||document.getElementById("googleAccContainer")||$("#syncedPlatformContainer").remove()})):console.error("Error: Invalid option.")}),$("#saveChangesBtn").click(function(){var e=0;$("#saveChangesForm :input").each(function(){this.checkValidity()?($(this).addClass("is-valid"),e++):$(this).addClass("is-invalid")});var i,n=this;i=t&&s?19:t?18:s?18:17;var o=!1,l=!1;if($("input[name=f_ad_account_id]").val()&&(o=$("input[name=f_ad_account_id]").val()),$("input[name=g_ad_account_id]").val()&&(l=$("input[name=g_ad_account_id]").val()),e==i){$(this).prop("disabled",!0),$(this).html('<span class="spinner-border spinner-border-sm saving-status-loader" role="status" aria-hidden="true"></span>Saving Changes');var d={user_id:a,client_first_name:$("input[name=client_first_name]").val(),client_last_name:$("input[name=client_last_name]").val(),client_email_address:$("input[name=client_email_address]").val(),client_contact_number:$("input[name=client_contact_number]").val(),client_postal_address:$("textarea[name=client_postal_address]").val(),business_registered_name:$("input[name=business_registered_name]").val(),contact_first_name:$("input[name=contact_first_name]").val(),contact_last_name:$("input[name=contact_last_name]").val(),contact_email_address:$("input[name=contact_email_address]").val(),business_contact_number:$("input[name=business_contact_number]").val(),business_registration_number:$("input[name=business_registration_number]").val(),business_vat_number:$("input[name=business_vat_number]").val(),business_physical_address:$("textarea[name=business_physical_address]").val(),business_postal_address:$("textarea[name=business_postal_address]").val(),google_acc_id:l,facebook_acc_id:o};$.post("/api/meetpat-client/settings/save-changes",d,function(t){}).done(function(t){$(n).prop("disabled",!1),$(n).html("Save Changes"),$("#success-toast").toast("show")}).fail(function(t){$(n).prop("disabled",!1),$(n).html("Save Changes"),$("input, textarea").removeClass("is-valid")})}})});</script>

@endsection