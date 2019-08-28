@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
    <div class="col-12 col-md-8 heading-row">
            <h3 class="display-4">Enriched Data Tracking</h3>
        </div>
    </div>
    <form class="form-inline">
    <label class="sr-only" for="yearSelect">Year</label>
    <select class="custom-select my-1 mr-sm-2" id="yearSelect">
        @foreach($years as $year)
            @if($year->year == date("Y"))
            <option value="{{$year->year}}" selected>{{$year->year}}</option>
            @else
            <option value="{{$year->year}}">{{$year->year}}</option>
            @endif
        @endforeach
    </select>

    <label class="sr-only" for="monthSelect">Month</label>
    <select class="custom-select my-1 mr-sm-2" id="monthSelect">
        @foreach($months as $month)
            @if($month->month == date("n"))
            <option value="{{$month->month}}" selected>{{$month->name}}</option>
            @else
            <option value="{{$month->month}}">{{$month->name}}</option>
            @endif
        @endforeach
    </select>

    <button type="button" id="graph-filter-button" class="btn btn-primary">Apply</button>
    </form>
    <br />
    <div class="row align-items-center mb-5" id="chart-row" style="height: 276px; width: 100%; background-color: #f7f7f7;">
        <div class="col-12 data-graph-container" id="chart-container-monthly">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <div class="row align-items-center" id="chart-row" style="height: 276px; width: 100%; background-color: #f7f7f7;">
        <div class="col-12 data-graph-container" id="chart-container-day">
            <div class="d-flex justify-content-center">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="{{asset('js/enriched_data_tracking.js')}}"></script>
@endsection