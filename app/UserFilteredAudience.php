<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class UserFilteredAudience extends Model
{
    //
    protected $fillable = ['user_id', 'number_of_contacts', 'selected_provinces', 'selected_areas', 'selected_ages', 'selected_genders', 'selected_population_groups', 'selected_generations', 'selected_citizens_vs_residents', 'selected_marital_statuses', 'selected_home_owners', 'selected_risk_categories', 'selected_household_incomes', 'selected_directors'];

}
