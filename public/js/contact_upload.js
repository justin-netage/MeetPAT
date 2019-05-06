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

    var displayLoader = function() {
            $("#loader").css("display", "block")
        },
        site_url = window.location.protocol + "//" + window.location.host;
        FilePond.registerPlugin(FilePondPluginFileValidateType);
        var pond = FilePond.create(document.querySelector('input[type="file"]'));
        const pond_element = document.querySelector(".filepond--root");
        
        pond_element.addEventListener("FilePond:removefile", e => {
        $.post("/api/meetpat-client/large-data/delete?file_id=" + $("#fileId").val() + "&user_id=" + $("#userId").val(), function(e) {}).done(function(e) {
            check_fields();
        }).fail(function(e) {})
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
            $.ajax({
                url: "/api/meetpat-client/large-data/handler",
                type: "POST",
                data: t,
                success: function(e) {
                    $("#alert-section").empty(), $("#alert-section").append('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success!</strong> Clients have been uploaded successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>')
                },
                complete: function(e) {
                    $("#loader").css("display", "none"), window.location = "/meetpat-client/data-visualisation"
                },
                error: function(e) {
                    $("#alert-section").empty(), $("#alert-section").append('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error!</strong> Clients failed to upload.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>')
                },
                cache: !1,
                contentType: !1,
                processData: !1
            })
        });
    
        $("#audience_name").on('change keyup select', function() {
    
            if($(this).val().match(/^[a-zA-Z ]{2,}$/)) {
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

