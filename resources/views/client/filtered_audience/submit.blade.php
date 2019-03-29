@extends('layouts.app')

@section('content')

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

                        @csrf
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