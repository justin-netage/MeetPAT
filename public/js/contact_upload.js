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
                console.log(e)
            })
        }), FilePond.setOptions({
            maxFileSize: "4MB",
            required: !0,
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