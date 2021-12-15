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
                            <li>PLEASE NOTE: The size limit for uploaded files is 10Mb. (Approx 100,000 Rows.) If your file is larger than this, you will need to split it into smaller files.</li>
                            <li>In order to upload, your spreadsheet must be saved as a CSV (comma delimited) file.</li>
                        </ul>
                        

                        </div>
                        @csrf
                        <a href="https://s3.amazonaws.com/dashboard.meetpat/public/sample/MeetPAT+Template.xlsx">Download template file</a>
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
                            <div class="form-group d-none">
                                <label>Original Data Source</label>
                                <select name="file_source_origin" class="form-control" id="origin-of-upload" hidden>
                                    <option value="directly_from_customers">Customers</option>
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
<script src="https://sdk.amazonaws.com/js/aws-sdk-2.875.0.min.js"></script>
<script src="{{asset('js/upload/meetpat_file_uploader.min.js')}}"></script>

@endsection