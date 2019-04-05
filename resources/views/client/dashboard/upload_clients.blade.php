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
                <div class="card-header"><h1 id="card-title">{{ __('Upload New Audience') }} </h1></div>
                <div class="card-body">
                    <div id="progress-sync">
                        
                    </div>
                    <form id="upload-custom-audience" enctype="multipart/form-data" onsubmit="displayLoader(); return false; this.preventDefault();" novalidate>
                    <h3 class="mb-5">Add a file with your customer data</h3>

                        @csrf
                        <input type="hidden" name="user_id"  id="userId" value="{{\Auth::user()->id}}">
                        <input type="hidden" name="file_id" id="fileId">
                        <div class="form-group row">
                        <span class="switch-label col-sm-8 col-form-label">Facebook</span>
                            <div class="col-sm-4 d-flex flex-column">
                            @if($has_facebook_ad_acc)

                                <label class="switch switch_type1" role="switch">
                                <input type="checkbox" name="facebook_custom_audience" class="switch__toggle">
                                <span class="switch__label"></span>
                                </label>
                            @else
                            <a href="/meetpat-client/sync-platform" class="btn mt-auto">Connect</a>
                            @endif
                            </div>
                        </div>
                        <div class="form-group row">
                        <span class="switch-label col-sm-8 col-form-label">Google</span>
                            <div class="col-sm-4 d-flex flex-column">
                            @if($has_google_adwords_acc)
                                <label class="switch switch_type1 " role="switch">
                                    <input type="checkbox" name="google_custom_audience" class="switch__toggle">
                                    <span class="switch__label"></span>
                                </label>
                            @else
                            <a href="/meetpat-client/sync-platform" class="btn mt-auto">Connect</a>
                            @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="identifiers"><i class="fas fa-info-circle"></i> Use the customer identifiers</label>
                            <span id="customer-email" class="info-badge badge badge-secondary">Email Address</span>
                            <span id="customer-phone" class="info-badge badge badge-secondary">Phone Number</span>
                        </div>
                        <div class="form-group">
                            <label>Original Data Source</label>
                            <select name="file_source_origin" class="form-control" id="origin-of-upload">
                                <option value="customers_and_partners">Customers and Partners</option>
                                <option value="directly_from_customers">Directly From Customers</option>
                                <option value="from_partners">From Partners</option>
                            </select>
                        </div>
                        <a href="{{Storage::disk('s3')->url('meetpat/public/sample/example_audience_file.csv')}}">download template file</a><span> ( Your file must match our template files layout )</span>
                        <input type="file" name="audience_file" class="filepond" id="audience_file">
                        <span class="invalid-feedback" id="no-file" role="alert">
                            <strong id="invalid-file">Please choose a valid .csv audience file to upload</strong>
                        </span>
                        <br />
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
                        <div class="form-group mb-0">
                            <button type="submit" id="submit_audience" disabled class="btn btn-primary btn-lg btn-block">
                                {{ __('Submit Audience') }}
                            </button>
                        </div>
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
<script type="text/javascript" src="{{ asset('js/upload_handler.min.js') }}" defer></script>


@endsection

