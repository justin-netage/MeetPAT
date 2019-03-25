@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<div role="alert" aria-live="assertive" id="records-toast" aria-atomic="true" class="toast" data-autohide="false" style="z-index: 9999; position: fixed; bottom: 0; right: 0;">
  <div class="toast-header">
    <strong class="mr-auto">Contacts</strong>
  </div>
  <div class="toast-body" style="font-size: 24px;">
    <div class="d-flex justify-content-center">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
  </div>
</div>
<div role="alert" aria-live="assertive" id="reset-filters-toast" aria-atomic="true" class="toast d-sm-none d-md-block" data-autohide="false" style="z-index: 9999; position: fixed; bottom: 20; right: 0;">
  <div class="toast-body" style="font-size: 24px;">
    <div class="d-flex justify-content-center">
        <button id="resetFilterToastBtn" class="btn btn-primary btn-block btn-lg" disabled="disabled">Reset Filters</button>
    </div>
  </div>
</div>

<div id="loader" style="display:none;"></div>
<div id="alert-section"></div>
<form id="credentials">
    <input type="hidden" id="user_id" name="user_id" value="{{Auth::user()->id}}">
</form>

<div class="container">
    <div class="row">
        <div class="col-12 col-md-2 d-flex align-items-center" id="contacts-number">
        <div role="alert" aria-live="assertive" id="records-main-toast" aria-atomic="true" class="toast" data-autohide="false">
        <div class="toast-header">
            <strong class="mr-auto">Contacts</strong>
        </div>
        <div class="toast-body" style="font-size: 24px;">
            <div class="d-flex justify-content-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        </div>
        </div>
        <div class="col-12 col-md-8">
            <h3 class="display-4 text-center">Where? <br /><small>Location Targeting</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>
    </div><br />
    <div class="row">
        <div class="col-12 col-md-6" id="province-graph">
            <h3>Province
                <!-- Default dropright button -->
                <div class="btn-group dropright float-right">
                    <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                       <i class="fas fa-filter"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-form">
                        <!-- Dropdown menu links -->
                        <form style="margin: 12px;" id="province-filter-form">
                            <div id="province_filter">
                            <div class="text-center">
                            <div class="spinner-border" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            </div>
                            </div> <br/>
                            <button name="province_submit" id="provinceSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                        </form>
                    </div>
                </div>
            </h3>
            <hr>
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>    
                <div id="provincesChart" style="height:250px; width: 100%;"></div>
        </div>
        <div class="col-12 col-md-6" id="municipality-graph">
            <h3>Municipality</h3>
            <hr>
            <div class="graph-container" class="graph-container" style="overflow-y: scroll; height: 250px;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="municipalityChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-md-6" id="map-graph">
            <h3>Map</h3>
            <hr>
            <div id="chartdiv" style="width: 100%; height: 250px;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
            </div>      
        </div>
        <div class="col-12 col-md-6" id="area-graph">
            <h3>Areas</h3>
            <hr>
            <div class="graph-container" class="graph-container" style="overflow-y: scroll; height: 250px;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="areasChart" style="width: 100%;"></div>
            </div> 
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-2 d-flex align-items-center">

        </div>
        <div class="col-12 col-md-8">
            <h3 class="display-4 text-center">Who? <br /><small>Demographic Targeting</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row">
        <div class="col-12 col-md-4" id="age-graph">
            <h3>Age
            <div class="btn-group dropright float-right">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-filter"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-form">
                <!-- Dropdown menu links -->
                <form style="margin: 12px;" id="age-filter-form">
                    <div id="age_filter">
                    <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    </div>
                    </div> <br/>
                    <button name="ages_submit" id="agesSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                </form>
            </div>
        </div>
            </h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/adult-2028245_640.png')}}" background-color: #3490DC;" class="img-fluid"/>
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="agesChart" style="width: 100%; height: 250px;"></div>
        </div>
        <div class="col-12 col-md-4" id="gender-graph">
            <h3>Gender
                <div class="btn-group dropright float-right">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                   <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-form">
                    <!-- Dropdown menu links -->
                    <form style="margin: 12px;" id="gender-filter-form">
                        <div id="gender_filter">
                        <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                        </div> <br/>
                        <button name="gender_submit" id="genderSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                    </form>
                </div>
            </h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/man-2933984_640.jpg')}}"  class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="genderChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4" id="population-graph">
            <h3>Polulation Group
            <div class="btn-group dropright float-right">
            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
               <i class="fas fa-filter"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-form">
                <!-- Dropdown menu links -->
                <form style="margin: 12px;" id="population-group-filter-form">
                    <div id="population_group_filter">
                    <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    </div>
                    </div> <br/>
                    <button name="population_groups_submit" id="population_groupsSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                </form>
            </div>
            </h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/african-american-3671900_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="populationGroupChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4" id="generation-graph">
            <h3>Generation
                <div class="btn-group dropright float-right">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-form">
                    <!-- Dropdown menu links -->
                    <form style="margin: 12px;" id="generation-filter-form">
                        <div id="generation_filter">
                        <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                        </div> <br/>
                        <button name="generations_submit" id="generationsSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                    </form>
                </div>
            </h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/entrepreneur-2934861_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="generationChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4" id="c-vs-r-graph">
            <h3>Citizen VS Resident
                <div class="btn-group dropright float-right">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-form">
                    <!-- Dropdown menu links -->
                    <form style="margin: 12px;" id="citizen-vs-resident-filter-form">
                        <div id="citizen_vs_resident_filter">
                        <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                        </div> <br/>
                        <button name="citizen_vs_resident_submit" id="citizenVsResidentSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                    </form>
                </div>
            </h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/south-africa-653005_640.png')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="citizensVsResidentsChart" style="width: 100%; height: 200px;"></div>
        </div>
        <div class="col-12 col-md-4" id="marital-status-graph">
            <h3>Marital Status
                <div class="btn-group dropright float-right">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-form">
                    <!-- Dropdown menu links -->
                    <form style="margin: 12px;" id="marital-status-filter-form">
                        <div id="marital_status_filter">
                        <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                        </div> <br/>
                        <button name="marital_status_submit" id="marital_statusSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                    </form>
                </div>            
            </h3>
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
            <h3 class="display-4 text-center">Assets Owned? <br /><small>Home and Vehicle Data</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row">
        <div class="col-12 col-md-4" id="home-owner-graph">
            <h3>Home Owner
            <div class="btn-group dropright float-right">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-form">
                    <!-- Dropdown menu links -->
                    <form style="margin: 12px;" id="home-owner-filter-form">
                        <div id="home_owner_filter">
                        <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                        </div> <br/>
                        <button name="home_owner_submit" id="home_ownersSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                    </form>
                </div> 
            </h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/architecture-1836070_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="homeOwnerChart" style="width: 100%; height: 200px;"></div>
        </div>
        <!-- <div class="col-12 col-md-4">
            <h3>Number of Homes Owned</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/graphic-1020366_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <h3>Average Home Value</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/graphic-1020366_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>

        </div> -->
        <!-- <div class="col-12 col-md-4">
            <h3>Car Owner</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/graphic-1020366_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
        </div> -->
    </div>
    <div class="row">
        <div class="col-12 col-md-2 d-flex align-items-center">
        </div>
        <div class="col-12 col-md-8">
            <h3 class="display-4 text-center">Financial Wellness <br /><small>Financial Factors</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row">
        <!-- <div class="col-12 col-md-4">
            <h3>Blacklisted</h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/graphic-1020366_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
        </div> -->
        <div class="col-12 col-md-4" id="risk-category-graph">
            <h3>Risk Category
            <div class="btn-group dropright float-right">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-form">
                    <!-- Dropdown menu links -->
                    <form style="margin: 12px;" id="risk-categories-filter-form">
                        <div id="risk_category_filter">
                        <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                        </div> <br/>
                        <button name="risk_category_submit" id="risk_categoriesSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                    </form>
                </div>
            </h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/risk-1945683_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="riskCategoryChart" style="width: 100%; height: 300px;"></div>
        </div>
        <div class="col-12 col-md-4" id="income-graph">
            <h3>Household Income
                <div class="btn-group dropright float-right">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-form" style="width: 200px;">
                    <!-- Dropdown menu links -->
                    <form style="margin: 12px;" id="household-income-filter-form">
                        <div id="household_income_filter">
                        <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                        </div> <br/>
                        <button name="household_income_submit" id="household_incomesSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                    </form>
                </div>
            </h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/coins-1726618_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
                <div id="householdIncomeChart" style="width: 100%px; height: 250px;"></div>
        </div>
        <div class="col-12 col-md-4" id="directors-graph">
            <h3>Director of a Business
            <div class="btn-group dropright float-right">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-filter"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-form">
                    <!-- Dropdown menu links -->
                    <form style="margin: 12px;" id="directors-filter-form">
                        <div id="directors_filter">
                        <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        </div>
                        </div> <br/>
                        <button name="directors_submit" id="directorsSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                    </form>
                </div>                
            </h3>
            <hr>
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-visualisation-images/analytics-2697949_640.jpg')}}" class="img-fluid"/>
            <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="directorOfBusinessChart" style="width: 100%; height: 250px;"></div>
        </div>
    </div>
</div>



@endsection

@section('scripts')

<!-- <script src="{{asset('bower_components/chart.js/dist/Chart.min.js')}}"></script> -->
<!-- Resources -->
<!-- <script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/maps.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/geodata/southAfricaLow.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script> -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="{{asset('js/data_visualisation.js')}}"></script>
@endsection