@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<div id="loader" style="display:none;"></div>
<div id="alert-section"></div>
<form id="credentials">
    <input type="hidden" id="user_id" name="user_id" value="{{Auth::user()->id}}">
    <input type="hidden" id="file_id" name="file_id" value="{{$file_id}}">
</form>

<div class="container">
    <div class="row">
        <div class="col-12 col-md-2 d-flex align-items-center">
            <div id="number_of_records">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-8">
            <h3 class="display-3 text-center">Where? <br /><small>Location Targeting</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-4">
            <h3 >Province</h3>
            <hr>
            <div class="graph-container" class="graph-container" style="overflow-y: scroll; height: 368px;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>    
                <div id="provincesChart" style="height:500px; width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Municipality</h3>
            <hr>
            <div class="graph-container" class="graph-container" style="overflow-y: scroll; height: 368px;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="municipalityChart" style="height: 100%; min-height: 2000px; width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Map</h3>
            <hr>
            <div id="chartdiv" style="width: 100%; height: 368px; border: 2px solid #6C757D; border-radius: 5px;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
            </div>      
        </div>
    </div>
    <!-- <div class="row p-5">
        <div class="col-12 col-md-6">
            <h3 >Areas</h3>
            <hr>
            <div class="graph-container" class="graph-container" style="overflow-y: scroll; height: 500px;">
                <div id="areasChart" style="height: 20000px; min-height: 10000; width: 100%;"></div>
            </div> 
        </div>
    </div> -->
    <div class="row">
        <div class="col-12 col-md-2 d-flex align-items-center">

        </div>
        <div class="col-12 col-md-8">
            <h3 class="display-3 text-center">Who? <br /><small>Demographic Targeting</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row">
        <div class="col-12 col-md-4">
            <h3 >Age</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/adult-2028245_640.png')}}" background-color: #3490DC;" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="agesChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Gender</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/man-2933984_640.jpg')}}"  class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="genderChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Polulation Group</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/african-american-3671900_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="populationGroupChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Generation</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/entrepreneur-2934861_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="generationChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Citizen VS Resident</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/south-africa-653005_640.png')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="citizensVsResidentsChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Marital Status</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/sunset-698501_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="maritalStatusChart" style="width: 100%; height: 200px;"></div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-2 d-flex align-items-center">

        </div>
        <div class="col-12 col-md-8">
            <h3 class="display-3 text-center">Assests Owned? <br /><small>Home and Vehicle Data</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row">
        <div class="col-12 col-md-4">
            <h3 >Home Owner</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/architecture-1836070_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="homeOwnerChart" style="width: 100%; height: 200px;"></div>

        </div>
        <div class="col-12 col-md-4">
            <h3 >Number of Homes Owned</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/graphic-1020366_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Average Home Value</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/graphic-1020366_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>

        </div>
        <div class="col-12 col-md-4">
            <h3 >Car Owner</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/graphic-1020366_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-2 d-flex align-items-center">
        </div>
        <div class="col-12 col-md-8">
            <h3 class="display-3 text-center">Financial Wellness <br /><small>Financial Factors</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row">
        <div class="col-12 col-md-4">
            <h3 >Blacklisted</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/graphic-1020366_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Risk Category</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/risk-1945683_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="riskCategoryChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4">
            <h3 >Household Income</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/coins-1726618_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>

        </div>
        <div class="col-12 col-md-4">
            <h3 >Director of a Business</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/analytics-2697949_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>

        </div>
    </div>
</div>



@endsection

@section('scripts')

<!-- <script src="{{asset('bower_components/chart.js/dist/Chart.min.js')}}"></script> -->
<!-- Resources -->
<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/maps.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/geodata/southAfricaLow.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<script type="text/javascript" src="{{asset('js/data_visualisation.js')}}"></script>
@endsection