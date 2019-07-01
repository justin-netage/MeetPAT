@extends('layouts.app')

@section('styles')

@endsection

@section('side-bars')
<div id="lunr"></div>

<div class="right-sidebar sidebar-in" id="right-options-sidebar">
    <h4>
        <strong class="mr-auto">Contacts</strong>
    </h4>
    <div id="contacts-num-sidebar">
        <div class="d-flex justify-content-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <br />
    <h4>
        <strong class="mr-auto">Filters</strong>
    </h4>
    <div class="scrollbar" id="style-1">
        <div class="sidebar-filters force-overflow">
            <ul id="province_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Province</span></ul>
            <ul id="municipality_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Municipalities</span></ul>
            <ul id="area_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Area</span></ul>   
            <ul id="age_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Age</span></ul>   
            <ul id="gender_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Gender</span></ul> 
            <ul id="population_group_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Population Group</span></ul>
            <ul id="generation_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Generation</span></ul>
            <ul id="citizen_vs_resident_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Citizen VS Resident</span></ul>
            <ul id="marital_status_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Marital Status</span></ul>
            <ul id="home_owner_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Home Owner</span></ul>
            <ul id="property_valuation_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Property Valuation</span></ul>
            <ul id="property_type_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Property Type</span></ul>
            <ul id="property_count_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Property Count</span></ul>
            <ul id="employer_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Employer</span></ul>
            <ul id="vehicle_owner_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Vehicle Owner</span></ul>
            <ul id="lsm_group_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">LSM Group</span></ul>
            <ul id="risk_category_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Risk Category</span></ul>
            <ul id="household_income_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Household Income</span></ul>
            <ul id="directors_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Directors</span></ul>
            <ul id="employer_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Employer</span></ul>
            <ul id="no_filters" class="list-unstyled"><span class="filter-heading">No filters have been added</span></ul>
        </div>
    </div>
    <br />
    <!-- <h4>
        <strong class="mr-auto">Potential Contacts</strong>
    </h4> -->
    <!-- <div id="potential-contacts-num-sidebar">?</div><br /> -->
    <form id="filtersForm" action="/meetpat-client/create-selected-contacts" method="post">
        @csrf
        <input type="hidden" id="user_id" name="user_id" value="{{Auth::user()->id}}">
        <input type="hidden" id="numberOfContactsId" name="number_of_contacts">
        <input type="hidden" id="provinceContactsId" name="provinceContacts[]">
        <input type="hidden" id="areaContactsId" name="areaContacts[]">
        <input type="hidden" id="AgeContactsId" name="AgeContacts[]">
        <input type="hidden" id="GenderContactsId" name="GenderContacts[]">
        <input type="hidden" id="populationContactsId" name="populationContacts[]">
        <input type="hidden" id="generationContactsId" name="generationContacts[]">
        <input type="hidden" id="citizenVsResidentsContactsId" name="citizenVsResidentsContacts[]">
        <input type="hidden" id="maritalStatusContactsId" name="maritalStatusContacts[]">
        <input type="hidden" id="homeOwnerContactsId" name="homeOwnerContacts[]">
        <input type="hidden" id="riskCategoryContactsId" name="riskCategoryContacts[]">
        <input type="hidden" id="houseHoldIncomeContactsId" name="houseHoldIncomeContacts[]">
        <input type="hidden" id="employerContactsId" name="employerContacts[]">
        <input type="hidden" id="directorsContactsId" name="directorsContacts[]">
        <input type="hidden" id="vehicleOwnerContactsId" name="vehicleOwnerContacts[]">
        <input type="hidden" id="propertyValuationContactsId" name="vehicleOwnerContacts[]">
        <input type="hidden" id="lsmGroupContactsId" name="lsmGroupContacts[]">
        <!-- <button id="audienceSubmitBtn" class="btn btn-secondary btn-block" disabled="true" type="submit" /><i class="fas fa-users"></i>&nbsp;Sync Contacts</button> -->
        <button id="sidebarSubmitBtn" type="button" class="btn btn-secondary btn-block apply-changes-button" disabled="true" type="button" /><i class="fas fa-sync-alt"></i>&nbsp;Apply Filters</button>
        <button id="resetFilterToastBtn" type="button" class="btn btn-secondary btn-block" disabled="disabled"><i class="fas fa-undo-alt"></i>&nbsp;Reset Filters</button>
        <button id="downloadSubmitBtn" class="btn btn-secondary btn-block"  disabled="true" type="button" /><i class="far fa-save"></i>&nbsp;Save Contacts</button>
    </form>    
</div>
<div class="right-sidebar-button sidebar-button-in" id="sidebar-toggle-button"><i class="fas fa-cog"></i></div>
@endsection

@section('content')


<div role="alert" aria-live="assertive" id="records-toast" aria-atomic="true" class="toast" data-autohide="false" style="z-index: 1020; position: fixed; bottom: 0; right: 0;">
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
<!-- <div role="alert" aria-live="assertive" id="reset-filters-toast" aria-atomic="true" class="toast d-sm-none d-md-block" data-autohide="false" style="z-index: 9999; position: fixed; bottom: 20; right: 0;">
  <div class="toast-body" style="font-size: 24px;">
    <div class="d-flex justify-content-center">
        <button id="resetFilterToastBtn" class="btn btn-primary btn-block btn-lg" disabled="disabled">Reset Filters</button>
    </div>
  </div>
</div> -->

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
        <div class="col-12 col-md-8 heading-row">
            <h3 class="display-4 text-center">Where? <br /><small>Location Targeting</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>
    </div><br />
    <div class="row">
        <div class="col-12 col-md-6 data-graph-container" id="province-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Province</span>
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
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>    
                <div id="provincesChart" style="width: 100%; background-color: #f7f7f7;"></div>
            </div>
        </div>
        <div class="col-12 col-md-6 data-graph-container" id="municipality-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Municipality</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form" style="overflow-y: scroll; height: 256px;">
                                <!-- Dropdown menu links -->
                                <form style="margin: 12px;" id="municipality-filter-form">
                                    <div id="municipality_filter">
                                    <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                    </div> <br/>
                                    <button name="municipality_submit" id="municipalitySubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                                </form>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="municipalityChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-md-6 data-graph-container" id="map-graph">
        <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
            <div class="media-body">
                <h3 class="mt-3"><span>Map</span></h3>
                
            </div>
        </div>
        <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
            <div id="chartdiv" style="width: 100%;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
            </div>  
        </div>
        
        </div>
        <div class="col-12 col-md-6 data-graph-container" id="area-graph">
        <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Area</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search"></i>&nbsp;<i class="fas fa-filter"></i>
                        </button>
                            <div class="dropdown-menu dropdown-menu-form" style="overflow-y: scroll; padding:16px; width: 290px; height: 256px;">
                                <!-- Dropdown menu links -->
                                <form style="margin: 8px;" id="area-filter-form">
                                <div id="hidden-area-filter-form" style="display:none;">
                                    <!-- selected areas from search -->
                                </div>
                                <div id="area_filter">
                                    <div id="lunr-search" style="display: none;">
                                        <input type="text" class="form-control mb-2" id="areaSearchInput" autocomplete="off" placeholder="search for area...">
                                        <span style="position:absolute; right: 40px; top:35px;"><i class="fas fa-search"></i></span>
                                        <ul id="lunr-results" class="list-unstyled"></ul>
                                    </div>
                                    <div class="text-center">
                                    <div class="spinner-border mb-2" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                </div>
                                <button name="area_submit" id="areaSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                                </form>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
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
        <div class="col-12 col-md-8 heading-row">
            <h3 class="display-4 text-center">Who? <br /><small>Demographic Targeting</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row">
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="age-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Age</span>
                <!-- Default dropright button -->
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
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>    
                <div id="agesChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="gender-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Gender</span>
                <!-- Default dropright button -->
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
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>    
                <div id="genderChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="population-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Population Group</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
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
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="populationGroupChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="generation-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Generation</span>
                <!-- Default dropright button -->
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
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="generationChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="c-vs-r-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Citizen VS Resident</span>
                <!-- Default dropright button -->
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
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="citizensVsResidentsChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="marital-status-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Marital Status.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Marital Status</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
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
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="maritalStatusChart" style="width: 100%;"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-2 d-flex align-items-center">

        </div>
        <div class="col-12 col-md-8 heading-row">
            <h3 class="display-4 text-center">Assets Owned? <br /><small>Home and Vehicle Data</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row">
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="home-owner-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Home Owner</span>
                <!-- Default dropright button -->
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
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="homeOwnerChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="property-valuation-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Property Valuation</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropright float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form" style="width: 228px;">
                                <!-- Dropdown menu links -->
                                <form style="margin: 8px;" id="property-valuation-filter-form">
                                    <div id="property_valuation_filter">
                                        <div class="text-center">
                                        <div class="spinner-border mb-2" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        </div>
                                    </div>
                                    <button name="property_valuation_submit" id="propertyValuationSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                                </form>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="propertyValuationChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="property-count-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/No of Homes.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Property Count</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form">
                                <!-- Dropdown menu links -->
                                <form style="margin: 12px;" id="property-count-filter-form">
                                    <div id="property_count_filter">
                                    <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                    </div> <br/>
                                    <button name="property_count_submit" id="property_countsSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                                </form>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="propertyCountChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="vehicle-owner-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Car Owner.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Vehicle Owner</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropright float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form">
                                <!-- Dropdown menu links -->
                                <form style="margin: 12px;" id="vehicle-owner-filter-form">
                                    <div id="vehicle_owner_filter">
                                    <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                    </div> <br/>
                                    <button name="vehicle_owner_submit" id="vehicle_ownersSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                                </form>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="vehicleOwnerChart" style="width: 100%;"></div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 col-md-2 d-flex align-items-center">
        </div>
        <div class="col-12 col-md-8 heading-row">
            <h3 class="display-4 text-center">Financial Wellness <br /><small>Financial Factors</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row">
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="risk-category-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Risk Category</span>
                <!-- Default dropright button -->
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
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="riskCategoryChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="lsm-group-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>LSM Group</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropright float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form">
                                <!-- Dropdown menu links -->
                                <form style="margin: 12px;" id="lsm-group-filter-form">
                                    <div id="lsm_group_filter">
                                    <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                    </div> <br/>
                                    <button name="lsm_group_submit" id="lsm_groupSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                                </form>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="lsmGroupChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="income-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Household Income</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
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
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                <div class="spinner spinner-3"></div>
            </div>
            <div id="householdIncomeChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="employer-graph">
        <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Employer</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropright float-right">
                        <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-search"></i>&nbsp;<i class="fas fa-filter"></i>
                        </button>
                            <div class="dropdown-menu dropdown-menu-form" style="overflow-y: scroll; padding:16px; width: 290px; height: 256px;">
                                <!-- Dropdown menu links -->
                                <form style="margin: 8px;" id="employer-filter-form">
                                    <div id="hidden-employer-filter-form" style="display:none;">
                                        <!-- selected employers from search -->
                                    </div>
                                    <div id="employer_filter">
                                        <div id="lunr-search-employer" style="display: none;">
                                            <input type="text" class="form-control mb-2" id="employerSearchInput" autocomplete="off" placeholder="search for employer...">
                                            <span style="position:absolute; right: 40px; top:35px;"><i class="fas fa-search"></i></span>
                                            <ul id="lunr-results-employer" class="list-unstyled"></ul>
                                        </div>
                                        <div class="text-center">
                                        <div class="spinner-border mb-2" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        </div>
                                    </div>
                                    <button name="employer_submit" id="employerSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button" disabled="true" type="button" />apply</button>
                                </form>
                            </div>
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="employerChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="directors-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('meetpat/public/images/data-icons/Popular Name Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Company Director</span>
                <!-- Default dropright button -->
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
                        </div>
                    </h3>
                </div>
            </div>
            <div class="graph-container" style="overflow-y: scroll; height: 256px; background-color: #f7f7f7;">
                <div class="spinner-block">
                    <div class="spinner spinner-3"></div>
                </div>
                <div id="directorOfBusinessChart" style="width: 100%;"></div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('scripts')


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="https://unpkg.com/lunr/lunr.js"></script>
<script type="text/javascript" src="{{asset('js/data_visualisation.js')}}"></script>
@endsection