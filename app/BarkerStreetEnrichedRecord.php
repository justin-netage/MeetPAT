<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class BarkerStreetEnrichedRecord extends Model
{
    //
    protected $fillable = [
        'InputIdn',
        'InputFirstName',
        'InputSurname',
        'InputPhone',
        'InputEmail',
        'CleanPhone',
        'RecordKey',
        'id6',
        'AgeGroup',
        'Gender',
        'PopulationGroup',
        'DeceasedStatus',
        'Generation',
        'MaritalStatus',
        'DirectorshipStatus',
        'HomeOwnerShipStatus',
        'PropertyValuation',
        'PropertyCount',
        'incomeBucket',
        'LSMGroup',
        'HasResidentialAddress',
        'Province',
        'Area',
        'Employer',
        'VehicleOwnerShipStatus',
        'affiliated_users'
    ];
}
