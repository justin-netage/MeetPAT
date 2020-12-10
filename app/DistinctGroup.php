<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class DistinctGroup extends Model
{
    //
    protected $fillable = [
        'user_id',
        'contact_category',
        'gender',
        'population_group',
        'generation',
        'marital_status',
        'directorship_status',
        'home_ownership_status',
        'primary_property_type',
        'property_count_bucket',
        'income_bucket',
        'has_residential_address',
        'province',
        'area',
        'municipality',
        'employer',
        'vehicle_ownership_status',
        'age_group',
        'citizenship_indicator',
        'custom_variable_1',
        'custom_variable_2',
        'custom_variable_3',
        'custom_variable_4'
        
    ];
}
