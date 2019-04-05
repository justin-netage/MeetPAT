var displayLoader = function () {
    $("#loader").css("display", "block");
};

var site_url = window.location.protocol + "//" + window.location.host;

FilePond.registerPlugin(FilePondPluginFileValidateType);
var pond = FilePond.create(document.querySelector('input[type="file"]'));
const pond_element = document.querySelector('.filepond--root');

pond_element.addEventListener('FilePond:removefile', e => {
    $.post(
        '/api/meetpat-client/large-data/delete?file_id=' + $("#fileId").val() + '&user_id=' + $("#userId").val(),
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
                url: '/api/meetpat-client/large-data/upload?user_id=' + $("#userId").val(),
                method: 'POST',
                withCredentials: false,
                headers: {},
                onerror: function(data) {
                    console.log(data);
                    $("#submit_audience").prop('disabled', true);

                },
                onload: function(data) {
                    // response 500 if file is invalid
                    data = JSON.parse(data);
                    
                    if(pond.getFile().fileExtension != 'csv') {
                        $("#no-file").show();
                        pond.removeFile();
                    } else {
                        $("#no-file").hide();
                    }

                    if(data.status != '500') {
                        $("#fileId").val(data.file_id);
                        $("#submit_audience").prop('disabled', false);
                    } else {
                        pond.removeFile();
                        $("#no-file").show();

                        if(data.error) {
                            $("#no-file").html(data.error);
                        } 

                    }
                    
                },            
            }
        }
    });

    $("form#upload-custom-audience").submit(function(e) {
    e.preventDefault();    
    var formData = new FormData(this);

    $.ajax({
        url: '/api/meetpat-client/large-data/handler',
        type: 'POST',
        data: formData,
        success: function (data) {
            $("#alert-section").empty();

            $("#alert-section").append(
            '<div class="alert alert-success alert-dismissible fade show" role="alert">'+
                '<strong>Success!</strong> Clients have been uploaded successfully.'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">&times;</span>'+
                '</button>'+
            ' </div>');
        },
        complete: function (data) {
            $("#loader").css("display", "none");
            window.location = '/meetpat-client/data-visualisation';

                    
        },
        error: function(data) {
            $("#alert-section").empty();

            $("#alert-section").append(
            '<div class="alert alert-danger alert-dismissible fade show" role="alert">'+
                '<strong>Error!</strong> Clients failed to upload.'+
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">'+
                    '<span aria-hidden="true">&times;</span>'+
                '</button>'+
            ' </div>');
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