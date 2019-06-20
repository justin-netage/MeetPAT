@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div id="alert-section"></div>
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Upload New Audience') }} &nbsp;<i class="fab fa-facebook-square" style="color: #3b5998;"></i> </h1></div>

                <div class="card-body">
                    <h3>Add a file with your customer data</h3>
                    <div id="progress-sync"></div>
                    <form id="upload-custom-audience" enctype="multipart/form-data" onsubmit="displayLoader(); return false; this.preventDefault();" novalidate>
                        @csrf
                        <input type="hidden" name="user_id" value="<?php echo \Auth::user()->id; ?>">
                        <div class="form-group">
                            <label for="identifiers"><i class="fas fa-info-circle"></i> Use the customer identifiers</label>
                            <span id="customer-email" class="info-badge badge badge-secondary">Email Address</span>
                            <span id="customer-phone" class="info-badge badge badge-secondary">Phone Number</span>
                        </div>
                        <p><a href="https://www.facebook.com/business/help/606443329504150" style="color: green;">Learn more about how to prepare a customer file with LTV</a></p>
                        <div class="form-group">
                            <label>Original Data Source</label>
                            <select name="file_source_origin" class="form-control" id="origin-of-upload">
                                <option value="customers_and_partners">Customers and Partners</option>
                                <option value="directly_from_customers">Directly From Customers</option>
                                <option value="from_partners">From Partners</option>
                            </select>
                        </div>
                        <a href="{{Storage::disk('s3')->url('meetpat/public/sample/example_audience_file.csv')}}">download template file</a><span> ( Your file must match our template files layout )</span>
                        <div class="upload-box mb-2 text-center">
                            <input type="file" name="audience_file" class="file-input-box" id="audience_file">
                        </div>
                        <span class="invalid-feedback" id="no-file" role="alert">
                            <strong id="invalid-file">Please choose an audience file to upload</strong>
                        </span>
                        <br />
                        <div class="form-group">
                            <label for="email">{{ __('Audience Name') }}</label>

                            <input id="audience_name" type="text" placeholder="Enter your new audience name" max="50" class="form-control{{ $errors->has('audience_name') ? ' is-invalid' : '' }}" name="audience_name" value="{{ old('audience_name') }}" autofocus>

                            @if ($errors->has('audience_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('audience_name') }}</strong>
                                </span>
                            @endif
                            <span class="invalid-feedback" role="alert">
                                <strong id="invalid-audience-name">Please provide a new and unique audience name</strong>
                            </span>
                        </div>
                        <div class="form-group mb-0">
                            <button type="submit" id="create_user" class="btn btn-primary">
                                {{ __('Submit Audience') }}
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
<script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
<script type="text/javascript">
    var displayLoader = function () {
        $("#loader").css("display", "block");
    };
    var completion_percentage = "0%";

    var run_jobs = function(data_id) {

        $.ajax({
            url: '/api/meetpat-client/request-facebook-api?job_id=' + data_id,
            type: 'POST',
            // data: { job_id: data.id },
            dataType: "json",
            success: function(jobdata) {

                if(jobdata.audience_captured < jobdata.total_audience + 1) {
                    completion_percentage = jobdata.percentage_complete + "%";

                    run_jobs(jobdata.id);

                } else {
                    completion_percentage = jobdata.percentage_complete + "%";
                }
            },
            error: function(jobdata) {
                console.log("there was an error with the request.")
            },
            complete: function(jobdata) {
                //$("#progress-bar-sync").css("width", jobdata.percentage_complete + "%");
            },
            cache: false,
            contentType: false,
            procesData: false
        });   
        $("#progress-bar-sync").css("width", completion_percentage);

        if(completion_percentage == '100%') {
            $("#progress-bar-sync").html("Sync has completed successfully.");
            $("#progress-bar-sync").addClass("bg-success");
            $("#progress-bar-sync").removeClass("progress-bar-animated");
            $("#back-to-dashboard").css("display", "block");

        } else {
            $("#progress-bar-sync").html(completion_percentage);

        }
    }

    $("form#upload-custom-audience").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);

    $.ajax({
        url: '/api/meetpat-client/upload-facebook-custom-audience',
        type: 'POST',
        data: formData,
        success: function (data) {

            if (data.errors) {
                $("#alert-section").empty();

                $("#alert-section").append(
                '<div class="alert alert-danger alert-dismissible fade show" role="alert">'+
                    '<strong>Error!</strong> Please make sure that all fields are valid'+
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                        '<span aria-hidden="true">&times;</span>'+
                    '</button>'+
               ' </div>'
                )
                if(data.errors.audience_name) {
                    $("#audience_name").addClass("is-invalid");
                    $("#invalid-audience-name").empty();
                    $("#invalid-audience-name").append(data.errors.audience_name);
                }

                if(data.errors.audience_file) {
                    $("#audience_file").addClass("is-invalid");
                    $("#no-file").css("display", "block");
                    $(".upload-box").css("border-color", "#e3342f")
                    $("#invalid-file").empty();
                    $("#invalid-file").append(data.errors.audience_file);
                }
            } else {
                $("#alert-section").empty();

                $("#alert-section").append(
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">'+
                        '<strong>Success!</strong> Your file has been uploaded the sync is now in progress.'+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span>'+
                        '</button>'+
                ' </div>');
                $("#upload-custom-audience").css("display", "none");
                $("#card-title").html("Sync In Progress &nbsp;<i class='fab fa-facebook-square' style='color: #3b5998;'></i>");
                $("#progress-sync").append(
                    '<div class="progress" style="height: 32px;">' +
                        '<div id="progress-bar-sync" class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">0%</div>' +
                    '</div><br />' +
                    '<div class="form-group mb-0">' +
                    '<a href="/meetpat-client" id="back-to-dashboard" style="display: none" class="btn btn-success btn-lg btn-block">Go Back To Dashboard</a>' +
                    '</div>'
                    
                );
                run_jobs(data.id);
                
            }

        },
        complete: function (data) {
            $("#loader").css("display", "none");
            console.log(data.responseJSON);
        },
        error: function(data) {
            console.log('There was an error with the upload.');
        },
        cache: false,
        contentType: false,
        processData: false
    });

    // validate input-fields

    $("#audience_name").change(function() {
        //console.log($(this).val());
        if($(this).val() !== "") {
            $(this).removeClass("is-invalid");
        } else {
            if(!$(this).hasClass("is-invalid")) {
                $(this).addClass("is-invalid");
            }
        }
    });

    $("#audience_file").change(function() {
        //console.log($(this).get(0).files.length);
        if($(this).get(0).files.length > 0) {
            $(this).removeClass("is-invalid");
            $("#no-file").css("display", "none");
            $(".upload-box").css("border-color", "#999");
        } else {
            if(!$(this).hasClass("is-invalid")) {
                $(this).addClass("is-invalid");
            }
            
        }
    })


});

$(function () {
        $('#customer-email').popover({
            html: true,
            placement: 'top',
            trigger: 'click',
            toggle: 'popover',
            title: 'Email Address',
            content: '<ul><b>Examples:</b>' +
                        '<li>Emily@example.com</li>' +
                        '<li>John@example.com</li>' +
                        '<li>Helena@example.com</li>' +
                     '</ul>'
        });

        $("#customer-phone").popover({
            html: true,
            placement: 'top',
            trigger: 'click',
            toggle: 'popover',
            title: 'Phone number',
            content: '<ul><b>Examples:</b>' +
                        '<li>+27123456789</li>' +
                        '<li>+27604235555</li>' +
                        '<li>+27826456678</li>' +

                     '</ul>'
        });
    });


// $(document).on('click', '#back-to-dashboard', offBeforeUnload);



// function offBeforeUnload(event) {
//     $(window).off('beforeunload');
// }

// function windowBeforeUnload() {
//      return "some message";
// }

// $(window).on('beforeunload', windowBeforeUnload);
    

</script>

@endsection