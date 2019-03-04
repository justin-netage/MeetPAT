<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class BarkerStreetRecord extends Model
{
    //
    protected $fillable = [
        'Idn',
        'FirstName',
        'Surname',
        'MobilePhone1',
        'MobilePhone2',
        'MobilePhone3',
        'WorkPhone1',
        'WorkPhone2',
        'WorkPhone3',
        'HomePhone1',
        'HomePhone2',
        'HomePhone3',
        'AgeGroup',
        'Gender',
        'PopulationGroup',
        'DeceasedStatus',
        'MaritalStatus',
        'DirectorshipStatus',
        'HomeOwnerShipStatus',
        'income',
        'incomeBucket',
        'LSMGroup',
        'CreditRiskCategory',
        'ContactCategory',
        'HasMobilePhone',
        'HasResidentialAddress',
        'Province',
        'GreaterArea',
        'Area',
        'ResidentialAddress1Line1',
        'ResidentialAddress1Line2',
        'ResidentialAddress1Line3',
        'ResidentialAddress2Line4',
        'ResidentialAddress2PostalCode',
        'PostalAddress1Line1',
        'PostalAddress1Line2',
        'PostalAddress1Line3',
        'PostalAddress1Line4',
        'PostalAddress1PostalCode',
        'email',
        'affiliated_users'
    ];
}
