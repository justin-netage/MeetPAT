<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class FilterJobQueue extends Model
{
    //
    protected $fillable = [
                            'user_id',
                            'audience_filters_id',
                            'filter_type',
                            'status',
                            'provinces',
                            'municipalities',
                            'areas',
                            'genders',
                            'age_groups',
                            'population_groups',
                            'generations',
                            'citizens_vs_residents',
                            'marital_statuses',
                            'home_ownership_statuses',
                            'property_count_buckets',
                            'property_valuation_bucket',
                            'primary_property_types',
                            'vehicle_ownership_statuses',
                            'risk_categories',
                            'lsm_groups',
                            'income_buckets',
                            'company_directorship_status',
                            'custom_variable_1',
                            'custom_variable_2'
                            ];

}
