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
            <ul id="property_count_bucket_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Property Count</span></ul>
            <ul id="primary_property_type_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Primary Property Type</span></ul>
            <ul id="vehicle_owner_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Vehicle Owner</span></ul>
            <ul id="lsm_group_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">LSM Group</span></ul>
            <ul id="risk_category_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Risk Category</span></ul>
            <ul id="household_income_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Household Income</span></ul>
            <ul id="directors_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Directors</span></ul>
            <ul id="branch_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Branches</span></ul>
            <ul id="campaign_filters" class="list-unstyled" style="display:none;"><span class="filter-heading">Campaigns</span></ul>
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
        <input type="hidden" id="user_auth_token" value="{{Auth::user()->api_token}}">
        <input type="hidden" id="numberOfContactsId" name="number_of_contacts">
        <input type="hidden" id="provinceContactsId" name="provinceContacts[]">
        <input type="hidden" id="areaContactsId" name="areaContacts[]">
        <input type="hidden" id="municipalityContactsId" name="municipalityContacts[]">
        <input type="hidden" id="AgeContactsId" name="AgeContacts[]">
        <input type="hidden" id="GenderContactsId" name="GenderContacts[]">
        <input type="hidden" id="populationContactsId" name="populationContacts[]">
        <input type="hidden" id="generationContactsId" name="generationContacts[]">
        <input type="hidden" id="citizenshipIndicatorContactsId" name="citizenshipIndicatorContacts[]">
        <input type="hidden" id="maritalStatusContactsId" name="maritalStatusContacts[]">
        <input type="hidden" id="homeOwnerContactsId" name="homeOwnerContacts[]">
        <input type="hidden" id="riskCategoryContactsId" name="riskCategoryContacts[]">
        <input type="hidden" id="houseHoldIncomeContactsId" name="houseHoldIncomeContacts[]">
        <input type="hidden" id="directorsContactsId" name="directorsContacts[]">
        <input type="hidden" id="vehicleOwnerContactsId" name="vehicleOwnerContacts[]">
        <input type="hidden" id="propertyCountBucketContactsId" name="propertyCountBucketContacts[]">
        <input type="hidden" id="primaryPropertyTypeContactsId" name="primaryPropertyTypeContacts[]">
        <input type="hidden" id="propertyValuationContactsId" name="propertyValuationContacts[]">
        <input type="hidden" id="lsmGroupContactsId" name="lsmGroupContacts[]">
        <input type="hidden" id="branchContactsId" name="branchContacts[]">
        <input type="hidden" id="campaignContactsId" name="campaignContacts[]">
        <!-- <button id="audienceSubmitBtn" class="btn btn-secondary btn-block" disabled="true" type="submit" /><i class="fas fa-users"></i>&nbsp;Sync Contacts</button> -->
        <button id="sidebarSubmitBtn" type="button" class="btn btn-secondary btn-block apply-changes-button" disabled="true" type="button" /><i class="fas fa-sync-alt"></i>&nbsp;Apply Filters</button>
        <button type="button" id="resetFilterToastBtn" class="btn btn-secondary btn-block" disabled="disabled"><i class="fas fa-undo-alt"></i>&nbsp;Reset Filters</button>
        <button class="btn btn-secondary btn-block" id="saveAudienceBtn" type="button" data-toggle="modal" data-target="#SaveAudienceModal"><i class="far fa-save"></i>&nbsp;Save Audience</button>
        <button class="btn btn-secondary btn-block" id="savedAudiencesBtn" type="button" data-toggle="modal" data-target="#SavedAudiencesModal"><i class="far fa-save"></i>&nbsp;Saved Audience Files</button>

    </form>    
</div>
<div class="reset-right-sidebar-button reset-sidebar-button-in" id="reset-toggle-button"><i class="fas fa-undo-alt"></i>&nbsp;<span>Reset</span></div>
<div class="apply-right-sidebar-button apply-sidebar-button-in" id="apply-toggle-button"><i class="fas fa-sync-alt"></i>&nbsp;<span>Apply</span></div>
<div class="right-sidebar-button sidebar-button-in" id="sidebar-toggle-button"><i class="fas fa-cog"></i></div>
@endsection

@section('content')

<!-- <div role="alert" aria-live="assertive" id="reset-filters-toast" aria-atomic="true" class="toast" data-autohide="false" style="z-index: 9998; position: fixed; bottom: 20; right: 0;">
  <div class="toast-body" style="font-size: 24px;">
    <div class="d-flex justify-content-center">
        <button id="resetFilterToastBtn2" class="btn btn-primary btn-block btn-lg" disabled="disabled">Reset Filters</button>
    </div>
  </div>
</div> -->
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Province.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="province_submit" id="provinceSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Municipality.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Municipality</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form" style="overflow-y: scroll; height: 256px; width: 256px;">
                                <!-- Dropdown menu links -->
                                <form style="margin: 12px;" id="municipality-filter-form">
                                    <div id="municipality_filter">
                                    <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                    </div> 
                                    <button name="municipality_submit" id="municipalitySubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons/MAP.png')}}"  class="mr-3 data-icon" alt="icon">
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Greater Area.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-search"></i></div>
                                            </div>
                                            <input type="text" class="form-control" id="areaSearchInput" placeholder="search for areas">
                                        </div>
                                        <ul id="lunr-results" class="list-unstyled"></ul>
                                    </div>
                                    <div class="text-center">
                                    <div class="spinner-border mb-2" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                </div>
                                <button name="area_submit" id="areaSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons/Age.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="ages_submit" id="agesSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Gender.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="gender_submit" id="genderSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Population Group.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="population_groups_submit" id="population_groupsSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Generation.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="generations_submit" id="generationsSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Citizen Resident.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="citizen_vs_resident_submit" id="citizenVsResidentSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Marital Status.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="marital_status_submit" id="marital_statusSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Home Owner.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="home_owner_submit" id="home_ownersSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
                <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="property-count-bucket-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/No of Homes.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Property Count</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form">
                                <!-- Dropdown menu links -->
                                <form style="margin: 12px;" id="property-count-bucket-filter-form">
                                    <div id="property_count_bucket_filter">
                                    <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                    </div> 
                                    <button name="property_count_bucket_submit" id="property_count_bucketsSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
                <div id="propertyCountBucketChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container d-none" id="primary-property-type-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Property Type.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Primary Property Type</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form">
                                <!-- Dropdown menu links -->
                                <form style="margin: 12px;" id="primary-property-type-filter-form">
                                    <div id="primary_property_type_filter">
                                    <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                    </div> 
                                    <button name="primary_property_type_submit" id="primary_property_typesSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
                <div id="primaryPropertyTypeChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="vehicle-owner-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Car Owner.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Vehicle Owner</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropleft float-right">
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
                                    </div> 
                                    <button name="vehicle_owner_submit" id="vehicle_ownersSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Risk.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="risk_category_submit" id="risk_categoriesSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons/LSM Group.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="lsm_group_submit" id="lsm_groupSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Household Income.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="household_income_submit" id="household_incomesSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="directors-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons-colour/Business Director.png')}}"  class="mr-3 data-icon" alt="icon">
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
                                    </div> 
                                    <button name="directors_submit" id="directorsSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
    <div class="row" id="metrics-heading">
        <div class="col-12 col-md-2 d-flex align-items-center">
        </div>
        <div class="col-12 col-md-8 heading-row">
            <h3 class="display-4 text-center">Custom Metrics <br /><small>Custom Business Metrics</small></h3>
        </div>
        <div class="col-12 col-md-2">
        </div>        
    </div>
    <div class="row" id="metrics-graphs">
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="branch-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons/Category.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Branch</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropright float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search"></i>&nbsp;<i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form" style="overflow-y: scroll; height: 256px; width: 256px;">
                                <!-- Dropdown menu links -->
                                <form style="margin: 12px;" id="branch-filter-form">
                                    <div id="hidden-branch-filter-form" style="display:none;">
                                        <!-- selected areas from search -->
                                    </div>
                                    <div id="branch_filter">
                                    <div id="branch-lunr-search" style="display: none;">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-search"></i></div>
                                            </div>
                                            <input type="text" class="form-control mb-2" id="branchSearchInput" placeholder="search for branches">
                                        </div>    
                                        <ul id="branch-lunr-results" class="list-unstyled"></ul>
                                    </div>
                                    <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                    </div> 
                                    <button name="branch_submit" id="branchSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
                <div id="branchChart" style="width: 100%;"></div>
            </div>
        </div>
        <div class="col-12 col-lg-4 col-md-6 data-graph-container" id="campaign-graph">
            <div class="media">
            <img src="{{Storage::disk('s3')->url('dashboard.meetpat/public/images/data-icons/Campaign_Icon.png')}}"  class="mr-3 data-icon" alt="icon">
                <div class="media-body">
                    <h3 class="mt-3"><span>Campaign</span>
                <!-- Default dropright button -->
                        <div class="btn-group dropright float-right">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search"></i>&nbsp;<i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-form" style="overflow-y: scroll; height: 256px; width: 256px;">
                                <!-- Dropdown menu links -->
                                <form style="margin: 12px;" id="campaign-filter-form">
                                    <div id="hidden-campaign-filter-form" style="display:none;">
                                        <!-- selected areas from search -->
                                    </div>
                                    <div id="campaign_filter">
                                    <div id="campaign-lunr-search" style="display: none;">
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text"><i class="fas fa-search"></i></div>
                                            </div>
                                            <input type="text" class="form-control mb-2" id="campaignSearchInput" placeholder="search for campaigns">
                                        </div>    
                                        <ul id="campaign-lunr-results" class="list-unstyled"></ul>
                                    </div>
                                    <div class="text-center">
                                    <div class="spinner-border" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div>
                                    </div>
                                    </div> 
                                    <button name="campaign_submit" id="campaignSubmitBtn" class="btn btn-primary btn-sm btn-block apply-filter-button d-none" disabled="true" type="button" />apply</button>
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
                <div id="campaignChart" style="width: 100%;"></div>
            </div>
        </div>
    </div>
    
</div>

@section('modals')
<!-- Modal -->
<div class="modal fade" id="SavedAudiencesModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="SavedAudiencesModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
        <form id="savedAudiencesForm">
      <div class="modal-header">
        <h5 class="modal-title" id="SavedAudiencesModalLabel">Saved Filtered Audiences</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="alert alert-warning mb-2" role="alert">
            <strong>Warning</strong> - Deleting a file will remove it permanently.
        </div>
        <div class="alerts"></div>
        
        <div class="row" id="userSavedFiles">
        </div>

        <nav class="mt-3">
        <ul class="pagination justify-content-center" id="paginationContainer" data-current-page="1" data-number-of-pages="1">
            <li id="btn_prev_item" class="page-item"><a class="page-link" id="btn_prev" href="#">Previous</a></li>
            <li class="page-item"><a class="page-link" id="page_span" href="#">1</a></li>
            <li id="btn_next_item" class="page-item"><a class="page-link" id="btn_next" href="#">Next</a></li>
        </ul>
        </nav>
        <div class="row">
            <div class="col-12">
            <a href="/meetpat-client/files/saved-audience-files" target="_blank">Upload saved audiences to Facebook or Google custom audience lists. <i class="fas fa-external-link-alt"></i></a>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="saveFileNameEdits" class="btn btn-primary">Save changes</button>
      </div>
     </form>
    </div>
  </div>
</div>

<div class="modal fade" id="SaveAudienceModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="SaveAudienceModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <form id="saveAudienceForm">

    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="SaveAudienceLabel">Save Contacts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <div class="alert alert-warning" role="alert">
        Your filtered audiences will be saved as a CSV file.
      </div>      
      <div class="alerts"></div> 
      <div class="form-group">
          <label for="nameFile">Name File</label>
          <div class="input-group">
            <input type="text" id="nameFile" name="name_file" class="form-control" placeholder="filename">
            <div class="invalid-feedback">
            Please use letters, numbers and underscores instead of spaces.
            </div>
          </div>
      </div> 
      <div class="alert alert-primary" id="alert-eta" role="alert">
      This process could take <span id="eta_file_process"></span>. Please be patient.
      </div> 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="downloadSubmitBtn" type="button" class="btn btn-primary" role="button" aria-disabled="true">Save Contacts</button>
      </div>
    </div>
    </form>
  </div>
</div>

<div id="progress_popup" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <span>Please be patient. We are processing <span id="attributes_placeholder"></span> attributes in <span id="records_placeholder"></span> records in order to generate your report.</span> To filter the data select the <i class="fas fa-filter"></i> and click apply (<i class="fas fa-sync-alt"></i>). Click on <i class="fas fa-cog"></i> to download or save filtered results.
        <div class="progress" style="height: 3px;">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@endsection

@section('scripts')


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://unpkg.com/lunr/lunr.js"></script>
<script type="text/javascript" src="{{asset('js/data-visualization/data_visualisation_v2.js')}}"></script>
@endsection