$(document).ready(function() {

    // Submission Button
    var submissionButton = $("#submit_audience");
    // Submit Form Elements
    var submitAudienceForm_el = $("#upload-custom-audience");
    var platform_google = $("#google_custom_audience");
    var platform_facebook = $("#facebook_custom_audience");
    // Synch display
    var google_sync_status = $("#google-sync-status");
    var facebook_sync_status = $("#facebook-sync-status");
    // Audience Name 
    var audience_name = $("#audience_name")
    // Filtered list Details
    var filtered_audience_id = $("#filtered_audience_id").val();
    var user_id = $("#user_id").val();
    // Functions
    var format_audience_name = function(name) {
        var new_date = new Date();
        var timestamp = new_date.getTime();

        name = name.replace(/ /g, '_') + '_' + timestamp;

        return name;
    }
    var validate_upload_form = function(facebook_upload_check, google_upload_check, audience_name) {
        var form_valid = false;

        if(audience_name.val() && audience_name.val().match(/^[a-zA-Z0-9 \_]*$/)   && (facebook_upload_check.is(":checked") || google_upload_check.is(":checked")))
        {
            form_valid = true;
        }

        if(form_valid)
        {
            $("#submit_audience").prop("disabled", false);
        } else {
            $("#submit_audience").prop("disabled", true);
        }
    }
    $("#facebook_custom_audience").change(function() {
        validate_upload_form($("#google_custom_audience"), $("#facebook_custom_audience"), $("#audience_name"));
    });
    $("#google_custom_audience").change(function() {
        validate_upload_form($("#google_custom_audience"), $("#facebook_custom_audience"), $("#audience_name"));
    });
    $("#audience_name").keyup(function() {
        validate_upload_form($("#google_custom_audience"), $("#facebook_custom_audience"), $("#audience_name"));
    });

    // run job que in sync.
    var run_pending_job = function(platform) {

        if(platform == 'google') {

            google_sync_status.show();
            $("#google-sync-status .status-text").html('Syncing&nbsp;');
            $.post('/api/meetpat-client/submit-audience/run-job-google', {user_id: user_id, filtered_audience_id: filtered_audience_id}, function() {
            }).fail(function(error) {
                $("#google-sync-status .status-text").addClass("text-danger");
                $("#google-sync-status .status-text").removeClass("text-primary");
                $("#google-sync-status .status-text").html('error&nbsp;<i class="far fa-times-circle"></i>');
                $("#google-sync-status .status-loader").hide();
                if(error.responseJSON.message.includes("AuthenticationError")) {
                    $("#alert-container").html(
                        '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                        '<strong>Error!</strong> An Authentication error has occured. Please make sure that your Adwords Account ID id correct.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                          '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                      '</div>'
                    );
                } else if(error.responseJSON.message.includes("QuotaCheckError")) {
                    $("#alert-container").html(
                        '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                        '<strong>Error!</strong> Adwords resonded with a Quota Check Error please be sure that you are nor exceeding your account limitations.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                        '</div>'                        
                    );
                } else if(error.responseJSON.message.includes("CollectionSizeError")) {
                    $("#alert-container").html(
                        '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                        '<strong>Error!</strong> Google Adwoeds responsed with error. There are too few customers in your list.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                          '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                      '</div>'
                    );
                } else {
                    $("#alert-container").html(
                        '<div class="alert alert-warning alert-dismissible fade show" role="alert">' +
                        '<strong>Error!</strong> Something went wrong please contact MeetPAT for assistance.' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                          '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                      '</div>'
                    );
                }
                $("#try-google-again").show();

                console.log(error);
            }).done(function(data) {
                // console.log(data);
                $("#google-sync-status .status-text").addClass("text-success");
                $("#google-sync-status .status-text").html('complete&nbsp;<i class="fas fa-check-square"></i>');
                $("#google-sync-status .status-loader").hide();
            }).always(function() {
                $("#back_to_dashboard").prop("disabled", false);
            });

        } else if ('platform' == 'facebook'){
 
            facebook_sync_status.show();            
            $("#facebook-sync-status .status-text").html('Syncing&nbsp;');

            $.post('/api/meetpat-client/submit-audience/run-job-facebook', {user_id: user_id, filtered_audience_id: filtered_audience_id}, function() {
                $("#facebook-sync-status .status-text").html('Syncing&nbsp;');
            }).fail(function(error) {
                $("#facebook-sync-status .status-text").addClass("text-danger");
                $("#facebook-sync-status .status-text").removeClass("text-primary");

                $("#facebook-sync-status .status-text").html('error&nbsp;<i class="far fa-times-circle"></i>');
                $("#facebook-sync-status .status-loader").hide();
                // console.log(error);
            }).done(function(data) {
                // console.log(data);
                $("#facebook-sync-status .status-text").addClass("text-success");
                $("#facebook-sync-status .status-text").html('complete&nbsp;<i class="fas fa-check-square"></i>');
                $("#facebook-sync-status .status-loader").hide();
            }).always(function() {
                $("#back_to_dashboard").prop("disabled", false);
            });
        } else {
            console.log('Error: Platform does not exist.');
        }
    }

    $("#try-google-again").on('click', function() {
        $("#try-google-again").hide();
        $("#google-sync-status .status-text").removeClass("text-danger");
        $("#google-sync-status .status-text").addClass("text-primary");
        $("#google-sync-status .status-loader").show();
        $("#back_to_dashboard").prop("disabled", true);
        run_pending_job('google');
    });
    // Submit audience to "Job Que"
    submissionButton.click(function() {
    $("#submit_audience").prop("disabled", true);
    $("#submit_audience").html(' <span class="spinner-border spinner-border-sm" style="margin-bottom: 4px;" role="status" aria-hidden="true"></span>&nbsp;Submitting Audience');
    // Platform 
    platform_google = $("#google_custom_audience");
    platform_facebook = $("#facebook_custom_audience");
    // Audience Name 
    audience_name = $("#audience_name").val();    
    

        if(platform_google.is(":checked")) {
            $.post('/api/meetpat-client/submit-audience/add-to-que', { user_id: user_id, filtered_audience_id: filtered_audience_id, platform: 'google', audience_name: format_audience_name(audience_name) }, function(  ) {
                $("#back_to_dashboard").prop("disabled", true);

            }).fail(function(error) {
                // console.log(error);
            }).done(function(data) {
                // console.log(data);
                run_pending_job('google');
                $("#submit_audience").html('Done');
                if(platform_facebook.is(":checked")) {
                    $.post('/api/meetpat-client/submit-audience/add-to-que', { user_id: user_id, filtered_audience_id: filtered_audience_id, platform: 'facebook', audience_name: format_audience_name(audience_name) }, function(  ) {

                    }).fail(function(error) {
                        // console.log(error);
                    }).done(function(data) {
                        run_pending_job('facebook');
                        // console.log(data);
                    }).always(function() {
                        $("#back_to_dashboard").prop("disabled", false);

                    });
                }

                submitAudienceForm_el.hide();
                $("#back_to_dashboard").removeClass("d-none");

            });
        } else if(platform_facebook.is(":checked")) {
            $("#back_to_dashboard").prop("disabled", true);

            $.post('/api/meetpat-client/submit-audience/add-to-que', { user_id: user_id, filtered_audience_id: filtered_audience_id, platform: 'facebook', audience_name: format_audience_name(audience_name) }, function(  ) {

            }).fail(function(error) {
                // console.log(error);
            }).done(function(data) {
                // console.log(data);
                run_pending_job('facebook');
                submitAudienceForm_el.hide();
                $("#back_to_dashboard").removeClass("d-none");
                

            }).always(function() {
                $("#back_to_dashboard").prop("disabled", false);
            });
        } else {
            console.log("error: no platform has been selected.");
        }


    });

});