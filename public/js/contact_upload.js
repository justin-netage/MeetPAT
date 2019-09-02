document.addEventListener("FilePond:loaded", e => {
    $("#upload-custom-audience").show();
    $(".spinner-loader-filepond").remove();

});
$(document).ready(function() {
    document.querySelector('#audience_name').setCustomValidity('invalid');

    var check_fields = function() {
        var file_valid = document.querySelector('input[type="file"]').checkValidity();
        var name_valid = document.querySelector('#audience_name').checkValidity();

        if(file_valid && name_valid) {
            $("#submit_audience").prop("disabled", !1);
        } else {
            $("#submit_audience").prop("disabled", !0);
        }

    }

        site_url = window.location.protocol + "//" + window.location.host;
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        var pond = FilePond.create(document.querySelector('input[type="file"]'));
        const pond_element = document.querySelector(".filepond--root");

        pond_element.addEventListener("FilePond:removefile", e => {
        $.post("/api/meetpat-client/large-data/delete?file_id=" + $("#fileId").val() + "&user_id=" + $("#userId").val(), function(e) {}).done(function(e) {
            check_fields();
        }).fail(function(e) { console.log(e)})
    }), FilePond.setOptions({
        maxFileSize: "200MB",
        required: !0,
        server: {
            url: site_url,
            process: {
                url: "/api/meetpat-client/large-data/upload?user_id=" + $("#userId").val(),
                method: "POST",
                withCredentials: !1,
                headers: {},
                onerror: function(e) {
                    console.log(e), $("#submit_audience").prop("disabled", !0)
                },
                onload: function(e) {
                    e = JSON.parse(e), "csv" != pond.getFile().fileExtension ? ($("#no-file").show(), pond.removeFile()) : $("#no-file").hide(), "500" != e.status ? ($("#fileId").val(e.file_id)) : (pond.removeFile(), $("#no-file").show(), e.error && $("#no-file").html(e.error))
                    check_fields();
                }
            }
        }
        }), $("form#upload-custom-audience").submit(function(e) {
            e.preventDefault();
            var t = new FormData(this);
            $("#submit_audience").prop('disabled', true);
            $("#submit_audience").html(
                `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    &nbsp;Submitting...`
            );
            $("#fieldsetId").prop("disabled", true);
            $.ajax({
                url: "/api/meetpat-client/large-data/handler",
                type: "POST",
                data: t,
                success: function(e) {
                    
                    if(e.status == "success")
                    {
                        $("#loader").css("display", "none")
                        $("#submit_audience").html('Done');
                        $("#alert-section").empty(), $("#alert-section").append('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success!</strong> Clients have been uploaded successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>');
                        window.location = "/meetpat-client/data-visualisation";
                        
                    } else {
                        $("#audience_name").removeClass('is-valid');
                        $("#audience_name").addClass('is-invalid');
                        document.getElementById("audience_name").setCustomValidity('invalid');

                        $("#submit_audience").prop('disabled', false);
                        $("#submit_audience").html('Submit');
                        $("#fieldsetId").prop("disabled", false);
                        $("#alert-section").empty(), $("#alert-section").append('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error!</strong> Clients failed to upload. '+e.message+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>');
                    }
                    
                },
                complete: function(e) {
                    
                    
                    
                },
                error: function(e) {
                    $("#submit_audience").prop('disabled', false);
                    $("#submit_audience").html('Submit');
                    $("#alert-section").empty(), $("#alert-section").append('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error!</strong> Clients failed to upload.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>');
                    $("#fieldsetId").prop("disabled", false);
                },
                cache: !1,
                contentType: !1,
                processData: !1
            })
        });


    
        $("#audience_name").on('change keyup select', function() {
    
            if($(this).val().match(/^[a-zA-Z 0-9]{2,}$/)) {
                $(this).addClass('is-valid');
                $(this).removeClass('is-invalid');
                this.setCustomValidity('');
            } else {
                $(this).removeClass('is-valid');
                $(this).addClass('is-invalid');
                this.setCustomValidity('invalid');
            }
            check_fields();
        });
});

