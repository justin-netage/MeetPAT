<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

function generateStrongPassword($length = 9, $add_dashes = false, $available_sets = 'luds')
    {
        $sets = array();
        if(strpos($available_sets, 'l') !== false)
            $sets[] = 'abcdefghjkmnpqrstuvwxyz';
        if(strpos($available_sets, 'u') !== false)
            $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
        if(strpos($available_sets, 'd') !== false)
            $sets[] = '23456789';
        if(strpos($available_sets, 's') !== false)
            $sets[] = '!@#$%&*?';
        $all = '';
        $password = '';
        foreach($sets as $set)
        {
            $password .= $set[array_rand(str_split($set))];
            $all .= $set;
        }
        $all = str_split($all);
        for($i = 0; $i < $length - count($sets); $i++)
            $password .= $all[array_rand($all)];
        $password = str_shuffle($password);
        if(!$add_dashes)
            return $password;
        $dash_len = floor(sqrt($length));
        $dash_str = '';
        while(strlen($password) > $dash_len)
        {
            $dash_str .= substr($password, 0, $dash_len) . '-';
            $password = substr($password, $dash_len);
        }
        $dash_str .= $password;
        return $dash_str;
    }

class MiscController extends Controller
{
    //
    

    public function generate_password()
    {
             
        return response()->json(['password' => generateStrongPassword(20)]);
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

    public function test() 
    {
        $conn_id = ftp_ssl_connect('DynamicPricingFeed.rccl.com', 36360);

        // login with username and password
        $login_result = ftp_login($conn_id, env('cruises_usr'), env('cruises_pwd'));

        if (!$login_result) {
            // PHP will already have raised an E_WARNING level message in this case
            die("can't login");
        }

        echo ftp_pwd($conn_id); // /

        // close the ssl connection
        ftp_close($conn_id);

        return var_dump($login_result);
    }

}
