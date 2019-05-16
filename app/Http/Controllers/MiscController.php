<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class MiscController extends Controller
{
    //
    public function generate_password()
    {
        //Initialize the random password
        $password = '';

        //Initialize a random desired length
        $desired_length = rand(8, 12);

        for($length = 0; $length < $desired_length; $length++) {
            //Append a random ASCII character (including symbols)
            $password .= chr(rand(32, 126));
        }
     
        return response()->json(['password' => $password]);
    }

    public function bsapi()
    {
        $curl = curl_init(env('BSAPI_URL') . '/standardEnrichment');
        $data_string = '[{"InputIdn":"","InputSurname":"Misoya","InputFirstName":"Merriam","InputPhone":"27710129360","InputEmail":"merriam.misoya@sanlam.co.za"},{"InputIdn":"","InputSurname":"","InputFirstName":"","InputPhone":"","InputEmail":""}]';
        
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
        curl_setopt($curl, CURLOPT_POSTFIELDS, 'data=' . rawurlencode($data_string));                                                                
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);      
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);                                                                
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/x-www-form-urlencoded',    
            'accountKey: ' . env('BSAPI_ACC_KEY'))                                                                       
        );                                              

        $result = curl_exec($curl);
        $info = curl_getinfo($curl);
        curl_close($curl);

        return response()->json(json_decode($result, true));        
    }

    public function bsapi_balance()
    {
        $curl = curl_init(env('BSAPI_URL') . '/balances');

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");                                                                     
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);      
        curl_setopt($curl, CURLINFO_HEADER_OUT, true);                                                                
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json',    
            'accountKey: ' . env('BSAPI_ACC_KEY')                                                                
            )                                                                       
        );                                              

        $result = curl_exec($curl);
        curl_getinfo($curl);
        curl_close($curl);
        return response()->json(json_decode($result, true)); 
    }
}
