@extends('layouts.app')

@section('styles')
<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-offset-3">
            <div id="alert-section"></div>
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Upload Contacts') }} </h1></div>
                <div class="card-body">
                    <div class="d-flex justify-content-center spinner-loader-filepond">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                    <form id="upload-custom-audience" enctype="multipart/form-data" style="display:none;" novalidate>
                        <div class="alert alert-info">
                        <p>Please note that uploading large numbers of records can take some time. During this process, you will not be able to access your dashboard or upload more contacts until the process has completed.</p>
                        <p>You can navigate away from this page. An email notification will be sent to <strong>{{\Auth::user()->email}}</strong>, once the process has completed.</p>

                        </div>
                        @csrf
                        <a href="https://s3.amazonaws.com/dashboard.meetpat/public/sample/MeetPAT Template.csv">Download template file</a>
                        <fieldset id="fieldsetId">
                            <input type="hidden" name="user_id"  id="userId" value="{{\Auth::user()->id}}">
                            <input type="hidden" name="file_id" id="fileId">
                            <input type="file" name="audience_file" class="filepond" id="audience_file">
                            <div class="invalid-feedback alert alert-danger" id="no-file" role="alert">
                                <strong id="invalid-file">Please choose a valid .csv audience file to upload</strong>
                            </div>
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
<script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond/dist/filepond.js"></script>
<script type="text/javascript" src="{{asset('js/contact_upload.min.js')}}"></script>

@endsection