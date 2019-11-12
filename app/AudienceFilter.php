<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class AudienceFilter extends Model
{
    //
    protected $fillable = [
                            'user_id',
                            'type',
                            'number_of_contacts',
                            'province',
                            'age_group',
                            'gender',
                            'population_group',
                            'generation',
                            'marital_status',
                            'home_ownership_status',
                            'risk_category',
                            'income_bucket',
                            'directorship_status',
                            'citizen_vs_resident',
                            'municipality',
                            'area',
                            'vehicle_ownership_status',
                            'property_valuation_bucket',
                            'lsm_group',
                            'property_count_bucket',
                            'primary_property_type',
                            'citizenship_indicator',
                            'custom_variable_1',
                            ];
}
