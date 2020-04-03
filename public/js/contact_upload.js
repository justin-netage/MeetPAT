    // Check for the various File API support.
    if (window.File && window.FileReader && window.FileList && window.Blob) {
        // Great success! All the File APIs are supported.
    } else {
    alert('The File APIs are not fully supported in this browser.');
    }
    
    document.addEventListener("FilePond:loaded", e => {
        $("#upload-custom-audience").show(), $(".spinner-loader-filepond").remove()
    }), $(document).ready(function() {
        document.querySelector("#audience_name").setCustomValidity("invalid");
        var e = function() {
            var e = document.querySelector('input[type="file"]').checkValidity(),
                i = document.querySelector("#audience_name").checkValidity();
            e && i ? $("#submit_audience").prop("disabled", !1) : $("#submit_audience").prop("disabled", !0)
        };
        site_url = window.location.protocol + "//" + window.location.host, FilePond.registerPlugin(FilePondPluginFileValidateType), FilePond.registerPlugin(FilePondPluginFileValidateSize);;
        var i = FilePond.create(document.querySelector('input[type="file"]'));
        document.querySelector(".filepond--root").addEventListener("FilePond:removefile", i => {
            $.post("/api/meetpat-client/large-data/delete?file_id=" + $("#fileId").val() + "&user_id=" + $("#userId").val(), function(e) {}).done(function(i) {
                e()
            }).fail(function(e) {
                //console.log(e)
            })
        }),

        document.querySelector(".filepond--root").addEventListener("FilePond:processfilestart", i => {
            $.post("/api/meetpat-client/large-data/delete?file_id=" + $("#fileId").val() + "&user_id=" + $("#userId").val(), function(e) {}).done(function(i) {
                $(".filepond--file-action-button").prop('disabled', true);
                e()
            }).fail(function(e) {
                //console.log(e)
            })
        }),

        document.querySelector(".filepond--root").addEventListener("FilePond:processfile", i => {
            $(".filepond--file-action-button").prop('disabled', false);
        }),
        
        document.querySelector(".filepond--root").addEventListener("FilePond:processfilerevert", i => {
            $.post("/api/meetpat-client/large-data/delete?file_id=" + $("#fileId").val() + "&user_id=" + $("#userId").val(), function(e) {}).done(function(i) {
                $("#fileCheckerPlaceholder").hide();
                e()
            }).fail(function(e) {
                //console.log(e)
            })
        }),
        
        FilePond.setOptions({
            maxFileSize: "5MB",
            required: !0,
            dropValidation: true,
            instantUpload: false,
            required: true,
            dropOnPage: true,
            onaddfile: (item) => {
                $("#no-file").hide();
                i.setOptions({disabled: true});

                $("#fileCheckerPlaceholder").show();
                $("#fileCheckerPlaceholder .d-flex").html(
                    "<strong class=\"loading\">Checking your file</strong>" +
                    "<div class=\"spinner-border spinner-border-sm ml-auto\" role=\"status\" aria-hidden=\"true\"></div>"
                );
                $.get("/api/meetpat-client/large-data/uploads-available", {user_id: $("#userId").val(), api_token: $("#authToken").val()},(results) => {
                    // Helper Methods
                    function numberWithCommas(x) {
                        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    }
                    function arrays_equal(a,b) { return !!a && !!b && !(a<b || b<a); }

                    var config = {
                        delimiter: "",	// auto-detect
                        newline: "",	// auto-detect
                        quoteChar: '"',
                        escapeChar: '"',
                        header: false,
                        transformHeader: undefined,
                        dynamicTyping: false,
                        preview: 0,
                        encoding: "",
                        worker: false,
                        comments: false,
                        step: undefined,
                        complete: function(data) {
                            i.setOptions({disabled: false});
                            
                            if(i.getFile().file.name.split('.')[1] == 'csv') {

                                if(data.meta.delimiter == ';') {
                                    if(arrays_equal(data.data[0], ["FirstName", "Surname", "MobilePhone", "Email", "IDNumber", "CustomVar1"])) {
                                        uploads_left = results.upload_limit - results.uploads;
        
                                        if(uploads_left >= (data.data.length - 1)) {
                                            
                                            $("#fileCheckerPlaceholder .d-flex").html(
                                                "<strong>Check complete</strong>" +
                                                "<div class=\"ml-auto\"><i class=\"fas text-success fa-check-circle\"></i></div>"
                                            );
                                            i.processFiles(); 
                                            
                                        } else {
                                            $("#fileCheckerPlaceholder").hide();
                                            i.removeFiles();
                                            $("#no-file").show();
                                            $("#invalid-file").html('<strong>Error!</strong> Audience failed to upload. You have <strong>' + numberWithCommas(uploads_left) + '</strong> available. The file you uploaded contains ' + numberWithCommas(data.data.length - 1) + '. Contact your reseller to increase your limit.');
                                        }
                                    } else {
                                        $("#fileCheckerPlaceholder").hide();
                                        i.removeFiles();
                                        $("#no-file").show();
                                        $("#invalid-file").html('<strong>Error!</strong> File does not match template.');
                                    }
                                } else {
                                    $("#fileCheckerPlaceholder").hide();
                                    i.removeFiles();
                                    $("#no-file").show();
                                    $("#invalid-file").html('<strong>Error!</strong> Please use the semicolon (;) delimiter.');
                                }

                            } else {
                                $("#fileCheckerPlaceholder").hide();
                                i.removeFiles();
                                $("#no-file").show();
                                $("#invalid-file").html('<strong>Error!</strong> File is not a csv.');
                            }
                            
                        },
                        error: undefined,
                        download: false,
                        downloadRequestHeaders: undefined,
                        downloadRequestBody: undefined,
                        skipEmptyLines: true,
                        chunk: undefined,
                        fastMode: undefined,
                        beforeFirstChunk: undefined,
                        withCredentials: undefined,
                        transform: undefined,
                        delimitersToGuess: [',', '\t', '|', ';', Papa.RECORD_SEP, Papa.UNIT_SEP]
                    }
    
                    var parsed_file = Papa.parse(i.getFile().file,config);
                    
                    console.log(results);
                }).fail((error) => {
                    console.log(error);
                });

                
                //console.log(json);
                //console.log(file.file);
                // TODO: if file invalid do stuff... (Install https://www.papaparse.com/docs#config)

            },
            server: {
                url: site_url,
                process: {
                    url: "/api/meetpat-client/large-data/upload?user_id=" + $("#userId").val(),
                    method: "POST",
                    withCredentials: !1,
                    chunkRetryDelays: 2,
                    chunkUploads: true,
                    headers: {},
                    onerror: function(e) {
                        console.log(e), $("#submit_audience").prop("disabled", !0)
                    },
                    onload: function(t) {
                        console.log(t), t = JSON.parse(t), "csv" != i.getFile().fileExtension ? ($("#no-file").show(), i.removeFile()) : $("#no-file").hide(), "500" != t.status ? $("#fileId").val(t.file_id) : (i.removeFile(), $("#no-file").show(), t.error && $("#no-file").html(t.error)), e()
                    }
                }
            }
        }), $("form#upload-custom-audience").submit(function(e) {
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