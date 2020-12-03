<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class FilteredAudienceFile extends Model
{
    //
    protected $fillable = ['user_id'
                            ,'file_unique_name'
                            ,'file_id'
                            ,'number_of_contacts'
                            ,'province'
                            ,'age_group'
                            ,'gender'
                            ,'population_group'
                            ,'generation'
                            ,'marital_status'
                            ,'home_ownership_status'
                            ,'risk_category'
                            ,'income_bucket'
                            ,'directorship_status'
                            ,'municipality'
                            ,'area'
                            ,'vehicle_ownership_status'
                            ,'property_valuation_bucket'
                            ,'property_count_bucket'
                            ,'primary_property_type'
                            ,'lsm_group'
                            ,'citizenship_indicator'
                            ,'custom_variable_1'
                            ,'custom_variable_2'
                            
];
}
