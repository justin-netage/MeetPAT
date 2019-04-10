<?php

namespace MeetPAT;

use Illuminate\Database\Eloquent\Model;

class MeetpatClientDetail extends Model
{
    //
    protected $fillable = [ 
                            'user_id',
                            'contact_first_name',
                            'contact_last_name',
                            'contact_email_address',
                            'business_contact_number',
                            'business_registered_name',
                            'business_registration_number',
                            'business_vat_number',
                            'business_postal_address',
                            'business_physical_address',
                            'client_first_name',
                            'client_last_name',
                            'client_contact_number',
                            'client_email_address',
                            'client_postal_address'
                        ];
}
