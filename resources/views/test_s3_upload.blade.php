@extends('layouts.app')

@section('styles')
<style>
    #drop_zone {
        width:  100%;
        height: 150px;
        margin-bottom: 25px;
        
    }

    #drop_zone .fileUploadBox {
        padding: 16px;
        height: 100%;
    }

    .no-file-dropped
    {
        border: 5px dashed grey;
        border-radius: 5px;
    }
    .no-file-dropped .fileUploadBox 
    {
        background-color: rgba(25, 25, 25, 0.1);
    }

    .fileUploadBox .file-abort .fa-times-circle, .fileUploadBox .fa-undo-alt
    {
        cursor: pointer;
    }

}
</style>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div id="alert-section"></div>
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Upload Contacts') }} </h1></div>
                <div class="card-body">
                    <form id="upload-custom-audience" enctype="multipart/form-data" novalidate>
                        <div class="alert alert-info">
                        <ul>
                            <li>Please note that uploading large numbers of records can take some time. During this process, you will not be able to access your dashboard or upload more contacts until the process has completed.</li>
                            <li>
                                In order to obtain the best results, the ideal minimum information required per contact is full name and cell phone number. The confidence level of the data for lists which contain only email addresses, or only cell numbers, will be reduced.
                            </li>
                            <li>You can navigate away from this page. An email notification will be sent to <strong>
                            @if(\Auth::user()->client_notification_detail)
                            {{Auth::user()->client_notification_detail->contact_email}}
                            @else
                            {{Auth::user()->email}}
                            @endif
                            </strong>, once the process has completed.</li>
                            <li>Column headers must remain identical to the sample sheet.</li>
                            <li>Any fields / columns for which you don't have data can be left blank.</li>
                        </ul>
                        

                        </div>
                        @csrf
                        <a href="https://s3.amazonaws.com/dashboard.meetpat/public/sample/MeetPAT Template.csv">Download template file</a>
                        <fieldset id="fieldsetId">
                            <input type="hidden" name="user_id"  id="userId" value="{{\Auth::user()->id}}">
                            <input type="hidden" name="file_id" id="fileId">
                            <input type="hidden" name="auth_token" id="authToken" value="{{\Auth::user()->api_token}}">
                            <input type="file" name="browse_file" id="browseFile" hidden>
                            <div id="drop_zone" class="no-file-dropped">
                                <div class="fileUploadBox d-flex flex-column justify-content-center"><strong class="text-center">Drag and drop your file here. <button type="button" id="browseBtn" class="btn btn-link">Browse</button></strong></div>
                            </div>
                            
                            <!-- <input type="file" name="audience_file" class="filepond" id="audience_file"> -->
                            
                            <div class="invalid-feedback alert alert-danger" id="no-file" role="alert">
                                <div id="invalid-file">Please choose a valid .csv audience file to upload</div>
                            </div>
                            <div class="invalid-feedback alert alert-warning" id="file-warning" role="alert">
                                <div id="file-warning-feedback"><strong>Warning!</strong> your file has bad rows that can not be uploaded.</div>
                            </div>
                            <br />
                            <div class="form-group">
                                <label>Original Data Source</label>
                                <select name="file_source_origin" class="form-control" id="origin-of-upload">
                                    <option value="customers_and_partners">Customers and Partners</option>
                                    <option value="directly_from_customers">Directly From Customers</option>
                                    <option value="from_partners">From Partners</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('Name Your File') }}</label>

                                <input id="audience_name" type="text" placeholder="Enter file name" max="50" class="form-control{{ $errors->has('audience_name') ? ' is-invalid' : '' }}" name="audience_name" value="{{ old('audience_name') }}" autofocus>

                                @if ($errors->has('audience_name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('audience_name') }}</strong>
                                    </span>
                                @endif
                                <span class="invalid-feedback" role="alert">
                                    <strong id="invalid-audience-name">Please provide a valid and unique file name</strong>
                                </span>
                            </div>
                        </fieldset>
                        <button type="submit" id="submit_audience" disabled class="btn btn-primary btn-lg btn-block">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<!-- TODO:  remove papaparse and perform all checks serverside with queued job -->
<script type="text/javascript" src="https://unpkg.com/papaparse@5.1.1/papaparse.min.js"></script>
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.650.0.min.js"></script>
<script>
    $(document).ready(function() {

        function formatBytes(bytes, decimals = 2) {
            if (bytes === 0) return '0 Bytes';

            const k = 1024;
            const dm = decimals < 0 ? 0 : decimals;
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];

            const i = Math.floor(Math.log(bytes) / Math.log(k));

            return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
        }

        function uniqid(length){
            var dec2hex = [];
            for (var i=0; i<=15; i++) {
                dec2hex[i] = i.toString(16);
            }

            var uuid = '';
            for (var i=1; i<=36; i++) {
                if (i===15) {
                uuid += 4;
                } else if (i===20) {
                uuid += dec2hex[(Math.random()*4|0 + 8)];
                } else {
                uuid += dec2hex[(Math.random()*16|0)];
                }
            }

            if(length) uuid = uuid.substring(0,length);
            return uuid;
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        var file_checker = function(uuid) {
            $("#drop_zone .fileUploadBox").css('background-color', "#fff");
            $("#drop_zone").removeClass("no-file-dropped");
            $("#drop_zone").html(
                "<div class=\"fileUploadBox d-flex flex-column justify-content-center\">" +
                    "<div class=\"d-flex justify-content-between\">" +
                        "<strong class=\"loading\">Checking your file</strong>" +
                        "<div class=\"spinner-border spinner-border-sm ml-auto\" role=\"status\" aria-hidden=\"true\"></div>" +
                    "</div>" +
                "</div>"
            );

            $.post("/api/meetpat-client/large-data/check-file", { user_id: $("#userId").val(), uuid: uuid, api_token: $("#authToken").val()}, (data) => {
                //console.log(data);

                const check_job = setInterval(() => {
                    $.get("/api/meetpat-client/check-file-job", { api_token: $("#authToken").val(), job_id: data.id}, (data) => {
                        
                        if(data.job.status == "error") {
                            clearInterval(check_job);
                            $("#drop_zone").html(
                                "<div class=\"fileUploadBox d-flex flex-column justify-content-center\">" +
                                    "<div class=\"d-flex justify-content-between\">" +
                                        "<strong class=\"loading\">Resetting file uploader</strong>" +
                                        "<div class=\"spinner-border spinner-border-sm ml-auto\" role=\"status\" aria-hidden=\"true\"></div>" +
                                    "</div>" +
                                "</div>"
                            ); 
                            delete_file(uuid + ".csv", 'fixed_files/');                           
                            bind_browse_btn();
                            $("#no-file").show();
                            $("#invalid-file").html('<strong><i class="fas fa-exclamation-circle"></i>&nbsp;Error!</strong> ' + data.message);

                        } else if (data.job.status == "complete") {    
                            clearInterval(check_job);

                            if(data.job.bad_rows_count) {
                                $("#file-warning").show();
                                $("#file-warning-feedback").html(
                                    "<strong><i class=\"fas fa-exclamation-triangle\"></i>&nbsp;Warning</strong>" +
                                    " Your file contains <strong>" + numberWithCommas(data.job.bad_rows_count) + "</strong> bad rows that will not be submitted." 
                                )
                            }
                            
                            $("#drop_zone").html(
                                "<div class=\"fileUploadBox d-flex flex-column justify-content-center\">" +
                                    "<div class=\"d-flex justify-content-between\">" +
                                        "<strong>Check complete</strong>" +
                                        "<div><i class=\"fas text-success fa-check-circle\"></i>&nbsp;<i class=\"fas fa-undo-alt\"></i></div>" +
                                    "</div>" +
                                "</div>"
                            );      
                                
                            $(".fa-undo-alt").unbind();
                            $(".fa-undo-alt").click(function() {
                                $("#no-file").hide();
                                $("#file-warning").hide();

                                $("#drop_zone").html(
                                    "<div class=\"fileUploadBox d-flex flex-column justify-content-center\">" +
                                        "<div class=\"d-flex justify-content-between\">" +
                                            "<strong class=\"loading\">Resetting file uploader</strong>" +
                                            "<div class=\"spinner-border spinner-border-sm ml-auto\" role=\"status\" aria-hidden=\"true\"></div>" +
                                        "</div>" +
                                    "</div>"
                                ); 
                                delete_file(uuid + ".csv", 'fixed_files/');

                            });
                        } 

                    }).fail((error) => {
                        console.log(error);
                    });


                }, 5000);

                
            }).fail((error) => {
                console.log(error);
            });
        }

        var bind_browse_btn = function() {
            $("#browseBtn").unbind();
            $("#browseFile").unbind();

            $("#browseBtn").click(function() {
                $("#browseFile").click();
            });

            $("#browseFile").on('change', function(e) {
                upload_file(e.target.files[0]);
            });
        }

        var upload_file = function(file) {

            $("#drop_zone .fileUploadBox").css('background-color', "#fff");
            $("#drop_zone").removeClass("no-file-dropped");
            $("#drop_zone").html(
                "<div class=\"fileUploadBox d-flex flex-column justify-content-center\">" +
                    "<div class=\"d-flex justify-content-between file-abort\">" +
                        "<div><i class=\"fas fa-file-csv\" style=\"font-size: 24px;\"></i><strong>&nbsp;" + file.name + "</strong></div>" +
                    "</div>" +
                    "<div class=\"progress w-100\" style=\"height: 4px;\">" +
                        "<div class=\"progress-bar\" role=\"progressbar\" style=\"width: 0%;\" aria-valuenow=\"0\" aria-valuemin=\"0\" aria-valuemax=\"100\"></div>" +
                    "</div>" +
                    "<div class=\"d-flex justify-content-between\">" +
                        "<div class=\"size-progress\">0 MB of " + formatBytes(file.size) + "</div>" +
                        "<div class=\"upload-progress\" style=\"color: #00A3D9;\">Uploading... 0%</div>" +
                    "</div>" +
                "</div>"
            );

            if(file.name.split('.')[1] == 'csv') {
                
                $.get('/api/get-aws-credentials', { api_token: $("#authToken").val() }, function(data) {

                var albumBucketName = "meetpat.fileuploads";
                var bucketRegion = "us-east-1";

                AWS.config.region = bucketRegion; // Region
                AWS.config.update({
                    accessKeyId: data["ACCESS_ID"],
                    secretAccessKey: data["SECRET_KEY"]
                });

                AWS.config.credentials.get(function() {
                    
                    var s3 = new AWS.S3({
                        apiVersion: "2006-03-01",
                        params: { Bucket: albumBucketName }
                    });
                });

                var fileName = file.name;
                //var albumPhotosKey = encodeURIComponent(albumName) + "//";
                var uuid = uniqid(13);
                var fileKey = uuid + ".csv";

                // Use S3 ManagedUpload class as it supports multipart uploads
                var upload = new AWS.S3.ManagedUpload({
                    params: {
                    Bucket: albumBucketName,
                    Key: 'new_files/' + fileKey,
                    Body: file,
                    ACL: "public-read"
                    }
                });

                $("#drop_zone .fileUploadBox .file-abort").append("<div class=\"cancelUpload\"><i class=\"text-danger fas fa-times-circle\"></i></div>");

                $(".cancelUpload").click(function() {
                    $("#drop_zone .fileUploadBox .progress-bar").addClass('bg-danger');
                    upload.abort();
                });

                upload.on('httpUploadProgress', function (progress) {

                    percentage = Math.round(((progress.loaded/progress.total) * 100), 2);
                    
                    $("#drop_zone .fileUploadBox .progress-bar").width(percentage.toString() + "%");
                    $("#drop_zone .fileUploadBox .progress-bar").attr("aria-valuenow", percentage);
                    $("#drop_zone .fileUploadBox .size-progress").html(formatBytes(progress.loaded) + " MB of " + formatBytes(progress.total) + " MB");
                    if(progress.loaded == progress.total) {
                        $("#drop_zone .fileUploadBox .upload-progress").html("99%");
                    } else {
                        $("#drop_zone .fileUploadBox .upload-progress").html("Uploading... " + percentage + "%");
                    }

                });

                var promise = upload.promise();
                promise.then(
                    function(data) {
                    
                        $("#drop_zone .fileUploadBox .progress-bar").addClass('bg-success');
                        $("#drop_zone .fileUploadBox .upload-progress").html("100%");
                        $(".cancelUpload").unbind();
                        $(".cancelUpload").click(function() {
                            $(".cancelUpload").html("<div class=\"spinner-border spinner-border-sm\" role=\"status\"><span class=\"sr-only\">Loading...</span></div>");
                            delete_file(fileKey, 'new_files/');
                        });

                        file_checker(uuid);

                        $("#fileId").val(uuid);

                    },
                    function(err) {
                        //console.log(err, err.stack);
                        $("#drop_zone .fileUploadBox .progress-bar").addClass('bg-danger');
                        $("#drop_zone").addClass("no-file-dropped");
                        $("#drop_zone").html(
                            "<div class=\"fileUploadBox d-flex flex-column justify-content-center\"><strong class=\"text-center\">Drag and drop your file here. <button type=\"button\" id=\"browseBtn\" class=\"btn btn-link\">Browse</button></strong></div>"
                        )
                        // after error
                        bind_browse_btn();
                    
                    }
                );


                }).fail(function(error) {
                    console.log(error);
                });

            } else {
                $("#drop_zone").addClass("no-file-dropped");
                $("#drop_zone").html(
                    "<div class=\"fileUploadBox d-flex flex-column justify-content-center\"><strong class=\"text-center\">Drag and drop your file here. <button type=\"button\" id=\"browseBtn\" class=\"btn btn-link\">Browse</button></strong></div>"
                )
                
                bind_browse_btn();
                $("#no-file").show();
                $("#invalid-file").html('<strong><i class="fas fa-exclamation-circle"></i>&nbsp; Error!</strong> File format is not .csv. Format is .' + file.name.split('.')[1]);
            }
           
        }

        var delete_file = function(file_key, path) {
            
            $.get('/api/get-aws-credentials', { api_token: $("#authToken").val() }, function(data) {

                var albumBucketName = "meetpat.fileuploads";
                var bucketRegion = "us-east-1";

                AWS.config.region = bucketRegion; // Region
                AWS.config.update({
                    accessKeyId: data["ACCESS_ID"],
                    secretAccessKey: data["SECRET_KEY"]
                });
            
                            
                var s3 = new AWS.S3({
                    apiVersion: "2006-03-01",
                    params: { Bucket: albumBucketName }
                });

                s3.deleteObject({
                    Bucket: albumBucketName,
                    Key: path + file_key
                    }
                , function(err, data) {
                    if (err) { 
                        //console.log(err, err.stack);
                    } else {
                        $("#drop_zone").addClass("no-file-dropped");
                        $("#drop_zone").html(
                            "<div class=\"fileUploadBox d-flex flex-column justify-content-center\"><strong class=\"text-center\">Drag and drop your file here. <button type=\"button\" id=\"browseBtn\" class=\"btn btn-link\">Browse</button></strong></div>"
                        );
                        $("#fileId").val("");
                        // after delete/ cancel
                        bind_browse_btn();
                    }                  
                });

            }).fail(function(error) {
                console.log(error);
            })
        }

        // Initial
        bind_browse_btn();

        $("#drop_zone").on('drop', function(ev) {
            $("#no-file").hide();
            $("#file-warning").hide();
            // Prevent default behavior (Prevent file from being opened)
            ev.preventDefault();

            if (ev.originalEvent.dataTransfer.items) {
                // Use DataTransferItemList interface to access the file(s)
                
                    if (ev.originalEvent.dataTransfer.items[0].kind === 'file') {
                        var file = ev.originalEvent.dataTransfer.items[0].getAsFile();
                        
                        upload_file(file);
                    }
                
            } 
        });

        $("#drop_zone").on('dragover dragenter', function(ev) {
           
            $("#drop_zone .fileUploadBox").css('background-color', "rgba(25, 25, 25, 0.3)");
            // Prevent default behavior (Prevent file from being opened)
            ev.preventDefault();
        })
        $("#drop_zone").on('dragleave dragend', function() {
            $("#drop_zone .fileUploadBox").css('background-color', "rgba(25, 25, 25, 0.1)");
        });

        $("form#upload-custom-audience").submit(function(e) {
            e.preventDefault();
            var i = new FormData(this);
            $("#submit_audience").prop("disabled", !0), $("#submit_audience").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>&nbsp;Submitting...'), $("#fieldsetId").prop("disabled", !0), $.ajax({
                url: "/api/meetpat-client/large-data/handler",
                type: "POST",
                data: i,
                success: function(e) {
                    "success" == e.status ? ($("#loader").css("display", "none"), $("#submit_audience").html("Done"), $("#alert-section").empty(), $("#alert-section").append('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success!</strong> Clients have been uploaded successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>'), window.location = "/meetpat-client/data-visualisation") : ($("#audience_name").removeClass("is-valid"), $("#audience_name").addClass("is-invalid"), document.getElementById("audience_name").setCustomValidity("invalid"), $("#submit_audience").prop("disabled", !1), $("#submit_audience").html("Submit"), $("#fieldsetId").prop("disabled", !1), $("#alert-section").empty(), $("#alert-section").append('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error!</strong> Clients failed to upload. ' + e.message + '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>'))
                },
                complete: function(e) {},
                error: function(e) {
                    $("#submit_audience").prop("disabled", !1), $("#submit_audience").html("Submit"), $("#alert-section").empty(), $("#alert-section").append('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error!</strong> Clients failed to upload.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>'), $("#fieldsetId").prop("disabled", !1)
                },
                cache: !1,
                contentType: !1,
                processData: !1
            })
        }), $("#audience_name").on("change keyup select", function() {
            $(this).val().match(/^[a-zA-Z 0-9]{2,}$/) ? ($(this).addClass("is-valid"), $(this).removeClass("is-invalid"), this.setCustomValidity("")) : ($(this).removeClass("is-valid"), $(this).addClass("is-invalid"), this.setCustomValidity("invalid")), e()
        })
    });
</script>

@endsection