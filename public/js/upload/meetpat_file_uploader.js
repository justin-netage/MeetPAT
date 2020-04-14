$(document).ready(function() {
    window.addEventListener("dragover",function(e){
        e = e || event;
        e.preventDefault();
      },false);
      window.addEventListener("drop",function(e){
        e = e || event;
        e.preventDefault();
      },false);
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
                        if($("#audience_name").val()) {
                            $("#submit_audience").prop("disabled", false);
                        } else {
                            $("#submit_audience").prop("disabled", true);
                        }

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
                            $("#submit_audience").prop("disabled", true);
                            $("#fileId").val("");
                            $("#no-file, #file-warning").hide();

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
            $("#no-file, #file-warning").hide();
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
            
            if(!(file.size > 10000000)) {
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
                        $("#submit_audience").prop("disabled", true);
                        $("#fileId").val("");
                        $("#drop_zone .fileUploadBox .progress-bar").addClass('bg-danger');
                        upload.abort();
                    });
        
                    upload.on('httpUploadProgress', function (progress) {
        
                        percentage = Math.round(((progress.loaded/progress.total) * 100), 2);
                        
                        $("#drop_zone .fileUploadBox .progress-bar").width(percentage.toString() + "%");
                        $("#drop_zone .fileUploadBox .progress-bar").attr("aria-valuenow", percentage);
                        $("#drop_zone .fileUploadBox .size-progress").html(formatBytes(progress.loaded) + " of " + formatBytes(progress.total));
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
                            console.log(err, err.stack);
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
                $("#invalid-file").html('<strong><i class="fas fa-exclamation-circle"></i>&nbsp; Error!</strong> File size can\'t be greater that <strong>10MB</strong>. Current file size is <strong>' + formatBytes(file.size) + '</strong>');
            }

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
        $("#no-file, #file-warning").hide();
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
        if($(this).val().match(/^[a-zA-Z 0-9]{2,}$/) && $(this).val()) {
            if($("#fileId").val()) {
                $("#submit_audience").prop("disabled", false);
            } else {
                $("#submit_audience").prop("disabled", true);
            }
            $(this).addClass("is-valid"), $(this).removeClass("is-invalid"), this.setCustomValidity("")
        } else {
            $(this).removeClass("is-valid"), $(this).addClass("is-invalid"), this.setCustomValidity("invalid")
            $("#submit_audience").prop("disabled", true);
        } 
    });
});