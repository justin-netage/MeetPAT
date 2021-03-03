<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function test() {

        libxml_disable_entity_loader(false);
        $opts = array(
            'ssl' => array(
                'ciphers' => 'RC4-SHA',
                'verify_peer' => false,
                'verify_peer_name' => false
            )
        );
        $soapParameters = Array('login' => "ANDREW", 'password' => "TEST") ;

        $soapclient = new \SoapClient('http://102.128.137.125:8090/wsdl/IIntegWebService', $soapParameters);
        
        $params = array("Accountcode" => "00001", "Title" => "MR", "Initials" => "JN", "Surname" => "Nobody", "ContactName" => "John", "IDNumber" => "", "DateOfBirth" => "1960-01-01", "ResidentialAddressLine1" => "1 Test Drive", "ResidentialAddressLine2" => "", "ResidentialSuburb" => "Testing", "ResidentialTown" => "Test", "ResidentialPostalCode" => "1111", "PostalAddressLine1" => "1 Test Drive", "PostalAddressLine2" => "ResidentialSuburb", "PostalSuburb" => "Testing", "PostalTown" => "Test", "PostalCode" => "1111", "MobileNo" => "07123456789", "EmailAddress" => "test@netage.co.za", "UserField1" =>  "", "LoyaltyPoints" => "", "LoyaltyPointsValue" => "", "LoyaltyDiscount" => "", "LoyaltyID" => "");
        $response = $soapclient->AddCustomer($params);
        return var_dump($response);    
    }
}
