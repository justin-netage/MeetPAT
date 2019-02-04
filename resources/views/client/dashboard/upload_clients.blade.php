@extends('layouts.app')

@section('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
@endsection

@section('content')
<div id="loader"></div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div id="alert-section"></div>
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Upload New Audience') }} </h1></div>
                <div class="card-body">
                    <div id="progress-sync">
                        
                    </div>
                    <form id="upload-custom-audience" enctype="multipart/form-data" onsubmit="displayLoader(); return false; this.preventDefault();" novalidate>
                    <h3 class="mb-5">Add a file with your customer data</h3>

                        @csrf
                        <input type="hidden" name="user_id" value="{{\Auth::user()->id}}">
                        <div class="form-group row">
                        <span class="switch-label col-sm-8 col-form-label">Facebook</span>
                            <div class="col-sm-4">
                            @if($has_facebook_ad_acc)

                                <label class="switch switch_type1" role="switch">
                                <input type="checkbox" name="facebook_custom_audience" class="switch__toggle">
                                <span class="switch__label"></span>
                                </label>
                            @else
                            <a href="/meetpat-client/sync-platform" class="btn btn-secondary">Connect</a>
                            @endif
                            </div>
                        </div>
                        <div class="form-group row">
                        <span class="switch-label col-sm-8 col-form-label">Google</span>
                            <div class="col-sm-4">
                            @if($has_google_adwords_acc)
                                <label class="switch switch_type1 " role="switch">
                                    <input type="checkbox" name="google_custom_audience" class="switch__toggle">
                                    <span class="switch__label"></span>
                                </label>
                            @else
                            <a href="/meetpat-client/sync-platform" class="btn">Connect Account</a>
                            @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="identifiers"><i class="fas fa-info-circle"></i> Use the customer identifiers</label>
                            <span id="customer-email" class="info-badge badge badge-secondary">Email Address</span>
                            <span id="customer-phone" class="info-badge badge badge-secondary">Phone Number</span>
                        </div>
                        <div class="form-group">
                            <label>Original Data Source</label>
                            <select name="file_source_origin" class="form-control" id="origin-of-upload">
                                <option value="customers_and_partners">Customers and Partners</option>
                                <option value="directly_from_customers">Directly From Customers</option>
                                <option value="from_partners">From Partners</option>
                            </select>
                        </div>
                        <a href="{{Storage::disk('s3')->url('meetpat/public/sample/example_audience_file.csv')}}">download template file</a><span> ( Your file must match our template files layout )</span>
                        <input type="file" name="audience_file" class="filepond" id="audience_file">
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
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script type="text/javascript">

    var displayLoader = function () {
        $("#loader").css("display", "block");
    };
    
    var run_job = function(job_data) {
        if(job_data["platform"] == 'facebook') {
            $("#facebook_upload_status").html(
                '<div class="spinner-block">'+
                    '<div class="spinner spinner-3"></div>'+
                '</div>'
            );
            $.post(
                '/api/meetpat-client/upload-custom-audience/facebook',
                job_data,
                function(returnedData) {
                    console.log(returnedData);
                }).done(function(returnedData) {
                    //console.log(returnedData);
                    $("#facebook_upload_status").html(
                        '<i class="fas fa-check-circle"></i>'
                    );
                }).fail(function(returnedData) {
                    console.log(returnedData);
                    $("#facebook_upload_status").html(
                        '<i class="fas fa-exclamation-circle" style="color: red;"></i>'
                    );
                });

        } else {
            $("#google_upload_status").html(
                '<div class="spinner-block">'+
                    '<div class="spinner spinner-3"></div>'+
                '</div>'
            );
            $.post(
                '/api/meetpat-client/upload-custom-audience/google',
                job_data,
                function(returnedData) {
                    console.log(returnedData);
                }).done(function(returnedData) {
                    //console.log(returnedData);
                    $("#google_upload_status").html(
                        '<i class="fas fa-check-circle"></i>'
                    );
                }).fail(function(returnedData) {
                    console.log(returnedData);
                    $("#google_upload_status").html(
                        '<i class="fas fa-exclamation-circle" style="color: red;"></i>'
                    );
                });
        }
    }

    var pond = FilePond.create(document.querySelector('input[type="file"]'));
    $('input[type="file"]').attr('name', 'audience_file');

    FilePond.setOptions({
        // maximum allowed file size
        maxFileSize: '5MB',
        // crop the image to a 1:1 ratio
        //imageCropAspectRatio: '1:1',
        // resize the image
        //imageResizeTargetWidth: 200,
        // upload to this server end point
        server: '/api/meetpat-client/upload-custom-audience'
    });

    $("form#upload-custom-audience").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);

    if(pond.getFile()) {
        formData.append("audience_file", pond.getFile().file);
    }

    $.ajax({
        url: '/api/meetpat-client/upload-custom-audience',
        type: 'POST',
        data: formData,
        success: function (data) {

            if (data.errors) {
                // console.log(data.errors)
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
                    // $("#audience_file").addClass("is-invalid");
                    $("#no-file").css("display", "block");
                    $(".upload-box").css("border-color", "#e3342f")
                    $("#invalid-file").empty();
                    $("#invalid-file").append(data.errors.audience_file);
                }
            } else {
                $("#upload-custom-audience").css("display", "none");
            }

        },
        complete: function (data) {
            
            //console.log(data.responseJSON);
            $("#loader").css("display", "none");
            $("#alert-section").empty();

            if(data.responseJSON["length"] > 0) {

                $("#alert-section").append(
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">'+
                        '<strong>Success!</strong> Your file has been uploaded the sync is now in progress.'+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span>'+
                        '</button>'+
                ' </div>');
                $("#card-title").html("Sync In Progress");
                $("#progress-sync").html(
                    '<table class="table">' +
                        '<tbody id="sync-table-body">' +
                        '</tbody>' +
                    '</table>'
                    
                );           

                // Conditional dependant on job que.   
                if(data.responseJSON["length"] == 2) {
                    $("#sync-table-body").append(
                        '<tr id="facebook_upload">' +
                            '<td>Facebook</td>' +
                            '<td id="facebook_upload_status">' +
                                'pending...' +
                            '</td>'+
                        '</tr>'
                    );
                    $("#sync-table-body").append(
                        '<tr id="google_upload">' +
                            '<td>Google</td>' +
                            '<td id="google_upload_status">' +
                                'pending...' +
                            '</td>'+
                        '</tr>'
                    );   
                } else {
                    if(data.responseJSON[0]["platform"] == 'facebook') {
                        $("#sync-table-body").append(
                        '<tr id="facebook_upload">' +
                            '<td>Facebook</td>' +
                            '<td id="facebook_upload_status">' +
                                'pending...' +
                            '</td>'+
                        '</tr>'
                        );
                    } else {
                        $("#sync-table-body").append(
                        '<tr id="google_upload">' +
                            '<td>Google</td>' +
                            '<td id="google_upload_status">' +
                                'pending...' +
                            '</td>'+
                        '</tr>'
                        );   
                    }
                }
                
            } else if(data.responseJSON.errors != null) {
                console.log(data.responseJSON.errors);
            } else {
                $(".card-body").append(
                    '<div class="alert alert-success alert-dismissible fade show" role="alert">'+
                        '<strong>Success!</strong> Your audience file has been uploaded.'+
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                            '<span aria-hidden="true">&times;</span>'+
                        '</button>'+
                    '</div>' +
                    '<a href="/meetpat-client" class="btn btn-primary btn-lg btn-block">Back to Dashboard</a>'
                );   
                $("#alert-section").empty();
            }
            if(data.responseJSON["length"] == 2) {
                var run_jobs = function(callback) {
                    run_job(data.responseJSON[0]);
                    callback();
                    }
                run_jobs(function() {
                    run_job(data.responseJSON[1]);
                });
                if(data.errors == null) {
                    $(document).ajaxStop(function() {
                        $('.card-body').append(
                            '<div class="alert alert-success fade show" role="alert">'+
                            '<strong>Success!</strong> Your Account has successfully synched.'+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                '<span aria-hidden="true">&times;</span>'+
                            '</button>'+
                            '</div>' +
                            '<a href="/meetpat-client" class="btn btn-primary btn-lg btn-block">Back to Dashboard</a>');
                        $("#alert-section").empty();

                    });
                }
            } else if(data.responseJSON["length"] == 1) {
                run_job(data.responseJSON[0]);
                if(data.errors == null) {
                    $(document).ajaxStop(function() {
                        $('.card-body').append(
                            '<div class="alert alert-success fade show" role="alert">'+
                            '<strong>Success!</strong> Your Accounts have successfully synched.'+
                            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                                '<span aria-hidden="true">&times;</span>'+
                            '</button>'+
                            '</div>' +
                            '<a href="/meetpat-client" class="btn btn-primary btn-lg btn-block">Back to Dashboard</a>');
                            $("#alert-section").empty();
                    });
                }

            } else {
                console.log("No Jobs in que.");
            }
            
        },
        error: function(data) {
            //console.log(data.responseJSON);
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

    // $("input[type='file']").change(function() {
    //     //console.log($(this).get(0).files.length);
    //     if($(this).get(0).files.length > 0) {
    //         $(this).removeClass("is-invalid");
    //         $("#no-file").css("display", "none");
    //         $(".upload-box").css("border-color", "#999");
    //     } else {
    //         if(!$(this).hasClass("is-invalid")) {
    //             $(this).addClass("is-invalid");
    //         }
            
    //     }
    // })


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

