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
                <div class="card-header"><h1 id="card-title">{{ __('Upload Contacts') }} </h1></div>
                <div class="card-body">
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
var displayLoader=function(){$("#loader").css("display","block")},site_url=window.location.protocol+"//"+window.location.host;FilePond.registerPlugin(FilePondPluginFileValidateType);var pond=FilePond.create(document.querySelector('input[type="file"]'));const pond_element=document.querySelector(".filepond--root");pond_element.addEventListener("FilePond:removefile",e=>{$.post("/api/meetpat-client/large-data/delete?file_id="+$("#fileId").val()+"&user_id="+$("#userId").val(),function(e){}).done(function(e){$("#submit_audience").prop("disabled",!0)}).fail(function(e){})}),FilePond.setOptions({maxFileSize:"200MB",required:!0,server:{url:site_url,process:{url:"/api/meetpat-client/large-data/upload?user_id="+$("#userId").val(),method:"POST",withCredentials:!1,headers:{},onerror:function(e){console.log(e),$("#submit_audience").prop("disabled",!0)},onload:function(e){e=JSON.parse(e),"csv"!=pond.getFile().fileExtension?($("#no-file").show(),pond.removeFile()):$("#no-file").hide(),"500"!=e.status?($("#fileId").val(e.file_id),$("#submit_audience").prop("disabled",!1)):(pond.removeFile(),$("#no-file").show(),e.error&&$("#no-file").html(e.error))}}}}),$("form#upload-custom-audience").submit(function(e){e.preventDefault();var t=new FormData(this);$.ajax({url:"/api/meetpat-client/large-data/handler",type:"POST",data:t,success:function(e){$("#alert-section").empty(),$("#alert-section").append('<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>Success!</strong> Clients have been uploaded successfully.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>')},complete:function(e){$("#loader").css("display","none"),window.location="/meetpat-client/data-visualisation"},error:function(e){$("#alert-section").empty(),$("#alert-section").append('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Error!</strong> Clients failed to upload.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> </div>')},cache:!1,contentType:!1,processData:!1}),$("#audience_name").change(function(){""!==$(this).val()?$(this).removeClass("is-invalid"):$(this).hasClass("is-invalid")||$(this).addClass("is-invalid")})});
</script>

@endsection