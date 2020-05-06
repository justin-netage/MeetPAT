@extends('layouts.app')

@section('content')

<!-- User token -->
<form id="user_token_form">
    <input type="hidden" id="ApiToken" name="api_token" value="{{$user_api_token}}">
    <input type="hidden" id="UserId" name="user_id" value="{{$user_id}}">
</form>
<!-- End -->

<div class="container" style="margin-bottom: 254px;">

    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div id="alert-section"></div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div class="card">
                <div class="card-header"><h1>{{ __('Notification Settings') }}</h1></div>

                <div class="card-body">
                    <form id="notificationsForm" autocomplete="off" onsubmit="return false;" novalidate="">
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <strong>Info</strong> — Notications include file upload progress updates and general notifications.
                                </div>
                            </div>
                            <div class="col-12" id="alertSectionNotifications"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputFirstName">First Name</label>
                                @if($user_notification_detail and $user_notification_detail->contact_first_name)
                                <input type="text" name="first_name" class="form-control" id="inputFirstName" value="{{$user_notification_detail->contact_first_name}}"/>
                                @else
                                <input type="text" name="first_name" class="form-control" id="inputFirstName"/>
                                @endif
                                <div class="invalid-feedback">
                                    This field is required.
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputFirstName">Last Name</label>
                                @if($user_notification_detail and $user_notification_detail->contact_last_name)
                                <input type="text" name="last_name" class="form-control" value="{{$user_notification_detail->contact_last_name}}" id="inputLastName"/>
                                @else
                                <input type="text" name="last_name" class="form-control" id="inputLastName"/>
                                @endif
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputEmailAddress">Email Address</label>
                                @if($user_notification_detail and $user_notification_detail->contact_email)
                                <input type="email" name="email_address" class="form-control" value="{{$user_notification_detail->contact_email}}" id="inputEmailAddress" placeholder="{{$user_notification_detail->contact_email}}" />
                                @else
                                <input type="email" name="email_address" class="form-control" id="inputEmailAddress" placeholder="{{\Auth::user()->email}}" />
                                @endif
                                <div class="invalid-feedback">
                                    This field is required.
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <button class="btn btn-success btn-block disabled" id="saveNotificationSettings" type="button" disabled><strong>Save Settings</strong></button>
                            </div>
                        </div>
                    </form>
                <div>
            </div>
        <div>
    </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">

    var auth_token = $("#ApiToken").val();
    var users_id = $("#UserId").val();

    var checkFormValidity = function() {

        if($("#inputEmailAddress").val().length == 0) {
            document.getElementById("inputEmailAddress").setCustomValidity("invalid");
        }

        if($("#inputFirstName").val().length == 0) {
            document.getElementById("inputFirstName").setCustomValidity("invalid");
        }

        var invalid_inputs = [];
        $("#notificationsForm :input").each(function() {
            if(!this.checkValidity()) {
                invalid_inputs.push(this);
            }
        });

        if(invalid_inputs.length) {
            $("#saveNotificationSettings").addClass("disabled");
            $("#saveNotificationSettings").prop("disabled", 1);
        } else {
            $("#saveNotificationSettings").removeClass("disabled");
            $("#saveNotificationSettings").prop("disabled", 0);
        }

    }

    $(document).ready(function() {
        checkFormValidity();
        // Check valid names
        $("#inputFirstName, #inputLastName").on("keyup change", function() {
            if($(this).val().length < 2 || !$(this).val().match(/^[A-Z]([a-zA-Z- ])*[a-zA-Z]$/g)) {
                $(this).removeClass('is-valid');
                $(this).addClass('is-invalid');
                this.setCustomValidity("invalid"); 
            } else {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
                this.setCustomValidity("");
            }

            checkFormValidity();

        });

        $("#inputLastName").on("keyup change", function() {
            if($(this).val().length == 0) {
                $(this).removeClass('is-invalid');
                this.setCustomValidity(""); 
            } else if($(this).val().length < 2 || !$(this).val().match(/^[A-Z]([a-zA-Z- ])*[a-zA-Z]$/g)) {
                $(this).removeClass('is-valid');
                $(this).addClass('is-invalid');
                this.setCustomValidity("invalid"); 
            } else {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
                this.setCustomValidity("");
            }

            checkFormValidity();

        });

        $("#inputEmailAddress").on("keyup change", function() {
            if(!$(this).val().match(/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,})$/g)) {
                $(this).removeClass('is-valid');
                $(this).addClass('is-invalid');
                this.setCustomValidity("invalid");
            } else {
                $(this).removeClass('is-invalid');
                $(this).addClass('is-valid');
                this.setCustomValidity("");
            }

            checkFormValidity();
        });

        $("#saveNotificationSettings").click(function() {
            $(this).prop("disabled", 1);
            $(this).html(`
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                <strong>&nbsp;Saving Settings...</strong>
            `);
            $.ajax({
                url: '/api/meetpat-client/settings/update',
                method: 'POST',
                data: {api_token: auth_token, user_id: users_id, first_name: $("#inputFirstName").val(), last_name: $("#inputLastName").val(), email_address: $("#inputEmailAddress").val()},
                success: function(data) {
                    $("#saveNotificationSettings").prop("disabled", 0);
                    $("#saveNotificationSettings").html('<strong>Save Settings</strong>');

                    $("#alert-section").html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> Notication settings have been updated.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                    
                },
                error: function(error) {
                    $("#saveNotificationSettings").prop("disabled", 0);
                    $("#saveNotificationSettings").html('<strong>Save Settings</strong>');
                    $("#alert-section").html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> An error occured while updating notification settings. Please contact MeetPAT for assistance if the problem persists.
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    `);
                    
                } 
            });
        });
    });
</script>

@endsection