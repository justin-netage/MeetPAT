var displayLoader = function () {
    $("#loader").css("display", "block");
};

var site_url = window.location.protocol + "//" + window.location.host;

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
FilePond.registerPlugin(FilePondPluginFileValidateType);
var pond = FilePond.create(document.querySelector('input[type="file"]'));
// $('input[type="file"]').attr('name', 'audience_file');
const pond_element = document.querySelector('.filepond--root');
pond_element.addEventListener('FilePond:removefile', e => {
    $.post(
        '/api/delete-file?file_id=' + $("#fileId").val() + '&user_id=' + $("#userId").val(),
        function(returnedData) {
            //console.log(returnedData);
        }).done(function(returnedData) {
            $("#submit_audience").prop('disabled', true);
        }).fail(function(returnedData) {
            //console.log(returnedData);
        });
    });

FilePond.setOptions({
    // maximum allowed file size
    maxFileSize: '200MB',
    required: true,
    // crop the image to a 1:1 ratio
    //imageCropAspectRatio: '1:1',
    // resize the image
    //imageResizeTargetWidth: 200,
    // upload to this server end point
    server: {
        url: site_url,
        process: {
            url: '/api/upload-file?user_id=' + $("#userId").val(),
            method: 'POST',
            withCredentials: false,
            headers: {},
            onerror: function(data) {
                console.log(data);
                $("#submit_audience").prop('disabled', true);

            },
            onload: function(data) {
                // response 500 if file is invalid
                //console.log(data);
                if(data != '500') {
                    $("#fileId").val(data);
                    $("#submit_audience").prop('disabled', false);
                } else {
                    $("#no-file").show();
                }

                if(pond.getFile().fileExtension != 'csv') {
                    $("#no-file").show();
                    pond.removeFile();
                } else {
                    $("#no-file").hide();
                }
                
            },            
        }
    }
});

$("form#upload-custom-audience").submit(function(e) {
e.preventDefault();    
var formData = new FormData(this);

// if(pond.getFile()) {
//     formData.append("audience_file", pond.getFile().file);
// }

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
        
        console.log(data.responseJSON);
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
                        '<a href="/meetpat-client" class="btn btn-primary btn-lg btn-block">Back to Dashboard</a>');
                    $("#alert-section").empty();

                });
            }
        } else if(data.responseJSON["length"] == 1) {
            run_job(data.responseJSON[0]);
            if(data.errors == null) {
                $(document).ajaxStop(function() {
                    $('.card-body').append(
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

// $(window).on('beforeunload', windowBeforeUnload);W