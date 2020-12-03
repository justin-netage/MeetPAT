<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class EnrichedRecord extends Model
{
    //
    protected $fillable = [
                            'RecordKey',
                            'ClientFileName',
                            'ClientRecordID',
                            'id6',
                            'FirstName',
                            'Middlename',
                            'Surname',
                            'CleanPhone',
                            'Email1',
                            'Email2',
                            'Email3',
                            'MobilePhone1',
                            'MobilePhone2',
                            'MobilePhone3',
                            'WorkPhone1',
                            'WorkPhone2',
                            'WorkPhone3',
                            'HomePhone1',
                            'HomePhone2',
                            'HomePhone3',
                            'ContactCategory',
                            'AgeGroup',
                            'Gender',
                            'PopulationGroup',
                            'DeceasedStatus',
                            'Generation',
                            'MaritalStatus',
                            'DirectorshipStatus',
                            'HomeOwnershipStatus',
                            'PrimaryPropertyType',
                            'PropertyValuation',
                            'PropertyValuationBucket',
                            'PropertyCount',
                            'PropertyCountBucket',
                            'Income',
                            'CreditRiskCategory',
                            'IncomeBucket',
                            'LSMGroup',
                            'HasResidentialAddress',
                            'Province',
                            'Area',
                            'Municipality',
                            'Employer',
                            'VehicleOwnershipStatus',
                            'InputIdn',
                            'InputFirstName',
                            'InputSurname',
                            'InputPhone',
                            'InputEmail',
                            'CitizenshipIndicator',
                            'affiliated_users',
                            'custom_variable_1',
                            'custom_variable_2'
    ];
}
