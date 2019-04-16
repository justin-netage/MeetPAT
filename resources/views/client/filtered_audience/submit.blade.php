@extends('layouts.app')

@section('content')
<?php 
    function nameFormatter($name) {

        return ucwords(str_replace("_", " ", $name));
    }

    // Shortens a number and attaches K, M, B, etc. accordingly
    function number_shorten($number, $precision = 3, $divisors = null) {

        // Setup default $divisors if not provided
        if (!isset($divisors)) {
            $divisors = array(
                pow(1000, 0) => '', // 1000^0 == 1
                pow(1000, 1) => 'K', // Thousand
                pow(1000, 2) => 'M', // Million
                pow(1000, 3) => 'B', // Billion
                pow(1000, 4) => 'T', // Trillion
                pow(1000, 5) => 'Qa', // Quadrillion
                pow(1000, 6) => 'Qi', // Quintillion
            );    
        }

        // Loop through each $divisor and find the
        // lowest amount that matches
        foreach ($divisors as $divisor => $shorthand) {
            if (abs($number) < ($divisor * 1000)) {
                // We found a match!
                break;
            }
        }

        // We found our match, or there were no matches.
        // Either way, use the last defined value for $divisor.
        return number_format($number / $divisor, $precision) . $shorthand;
    }

    function get_province_name($province_code) {

        $province_name;

        switch($province_code) {
            case "G":
                $province_name = "Gauteng";
                break;
            case "WC":
                $province_name = "Western Cape";
                break;
            case "EC":
                $province_name = "Eastern Cape";
                break;
            case "M":
                $province_name = "Mpumalanga";
                break;
            case "NW":
                $province_name = "North West";
                break;
            case "FS":
                $province_name = "Free State";
                break;
            case "L":
                $province_name = "Limpopo";
                break;
            case "KN":
                $province_name = "KwaZulu Natal";
                break;
            case "NC":
                $province_name = "Northern Cape";
                break;
            default:
                $province_name = "Unknown";
        }

        return $province_name;
    }
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    Filtered List
                </div>
                <div class="card-body">
                    <ul class="list-unstyled text-center filtered-list-items">
                        <li class="filter-count"><i class="fas fa-users"></i><br /><span>{{number_shorten($filtered_list->number_of_contacts, 1)}}</span></li>
                        @foreach($filters_array as $key=>$value)
                            @if($key == 'selected_provinces')
                            <li class="selected-filters">{{ nameFormatter($key)}}<br />
                            <span>
                                @foreach( explode(",", $value) as $province )
                                    {{get_province_name($province) . ","}}
                                @endforeach
                            </span>
                            </li>
                            @else
                            <li class="selected-filters">{{ nameFormatter($key)}}<br /><span>{{str_replace(",", ", ", $value)}}</span></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div id="alert-section"></div>
            <div class="card">
                <div class="card-header"><h1 id="card-title">{{ __('Upload New Audience') }} </h1></div>
                <div class="card-body">
                    <div id="alert-container"></div>
                    <div id="progress-sync">
                        <ul class="list-unstyled sync-list">
                            <li id="google-sync-status" style="display:none;">
                                <div class="clearfix">
                                Google
                                    <span class="float-right status-text">Pending&nbsp;</span>
                                    <span class="spinner-grow spinner-grow-sm float-right status-loader" role="status"></span>
                                </div> 
                            </li>
                            <button class="btn btn-warning float-right" style="display:none;" id="try-google-again"><i class="fas fa-redo-alt"></i>&nbsp;&nbsp;Try Again</button> 
                            <li id="facebook-sync-status" style="display:none;"> 
                                <div class="clearfix">
                                Facebook
                                    <span class="float-right status-text">Pending&nbsp;</span>
                                    <span class="spinner-grow spinner-grow-sm float-right status-loader" role="status"></span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <form id="upload-custom-audience" enctype="multipart/form-data" onsubmit="return false; this.preventDefault();" novalidate>
                        <input type="hidden" name="filtered_audience_id" id="filtered_audience_id" value="{{$filtered_list_id}}">
                        <input type="hidden" name="user_id" id="user_id" value="{{\Auth::user()->id}}">
                        @csrf
                        <div class="form-group row">
                        <span class="switch-label col-sm-8 col-form-label">Facebook</span>
                            <div class="col-sm-4 d-flex flex-column">
                            @if($has_facebook_ad_acc)

                                <label class="switch switch_type1" role="switch">
                                <input type="checkbox" name="facebook_custom_audience" id="facebook_custom_audience" class="switch__toggle">
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
                                    <input type="checkbox" name="google_custom_audience" id="google_custom_audience" class="switch__toggle">
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

                            <input id="audience_name" type="text" autocomplete="off" placeholder="Enter your new audience name" max="50" class="form-control{{ $errors->has('audience_name') ? ' is-invalid' : '' }}" name="audience_name" value="{{ old('audience_name') }}" autofocus>

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
                            <button type="button" id="submit_audience" disabled class="btn btn-primary btn-lg btn-block">
                                {{ __('Submit Audience') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-1">

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{{asset('js/submit_audience.min.js')}}"></script>

@endsection