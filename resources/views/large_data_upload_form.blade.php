@extends('layouts.app')

@section('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
@endsection

@section('content')
<div id="loader"></div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col">
        <div id="alert-section"></div>
            <form id="upload-custom-audience" enctype="multipart/form-data" onsubmit="displayLoader();" novalidate>
                @csrf
                <input type="hidden" name="user_id"  id="userId" value="{{\Auth::user()->id}}">
                <input type="hidden" name="file_id" id="fileId">
                <input type="file" name="audience_file" class="filepond" id="audience_file">
                <span class="invalid-feedback" id="no-file" role="alert">
                    <strong id="invalid-file">Please choose a valid .csv audience file to upload</strong>
                </span>
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
                <button type="submit" id="submit_audience" disabled class="btn btn-primary btn-lg btn-block">Submit</button>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script type="text/javascript">

    var displayLoader = function () {
        $("#loader").css("display", "block");
    };

    var site_url = window.location.protocol + "//" + window.location.host;

    FilePond.registerPlugin(FilePondPluginFileValidateType);
    var pond = FilePond.create(document.querySelector('input[type="file"]'));
    const pond_element = document.querySelector('.filepond--root');

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
                console.log(data.responseJSON);
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
                console.log(data.responseJSON);
                        
            },
            error: function(data) {
                console.log(data.responseJSON);
                console.log(data);
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


</script>

@endsection