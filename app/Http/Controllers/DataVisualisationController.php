<?php

namespace MeetPAT\Http\Controllers;

use Illuminate\Http\Request;

class DataVisualisationController extends Controller
{
    //

    public function index()
    {

        return view('client.data_visualisation.records');
    }

    public function large_data_upload_form()
    {

        return view('large_data_upload_form');
    }

    public function large_data_upload(Request $request)
    {
        function check_value($value)
        {
            if($value == '')
            {
                return null;
            } else {
                return $value;
            }
        }

        // validate mobile numbers

        function validate_mobile_number($number) {

            if(strlen($number) == 11 and $number[0] == '2' and $number[1] == '7') {
                
                return $number;
            } else {

                return null;

            }

        }

        // get age group ( AgeGroup )from ex. 03. Thirties 

        function get_age_group($age_group) {

            switch ($age_group) {
                case "02 Twenties":
                    return "02";
                    break;
                case "03. Thirties":
                    return "03";
                    break;
                case "04. Fourties":
                    return "04";
                    break;
                case "05. Fifties":
                    return "05";
                    break;  
                 case "06. Sixties":
                    return "05";
                    break;  
                case "07. Senventies":
                    return "05";
                    break;  
                case "08. Eighty +":
                    return "08";
                    break;    
                default:
                    return null;                                                   
            }


        }

        // get Gender F , M
        function get_gender($gender)
        {
            switch ($gender) {
                case "Male":
                    return "M";
                    break;
                case "Female":
                    return "F";
                    break;
                default:
                    return null;
            }
        }

        // get PopulationGroup

        function get_population_group($p_group) 
        {
            switch ($p_group) {
                case "Black":
                    return "B";
                    break;
                case "White":
                    return "W";
                    break;
                case "Coloured":
                    return "C";
                    break;
                case "Asian":
                    return "A";
                case "Unkown":
                    return "U";
                    break;
                default:
                    return null;
            }
        }

        // find IncomeBucket

        function find_income_bucket($income)
        {
            if($income < 2501) {
                return "R0 - R2 500";
            } else if ($income >= 2500 and $income < 5001) {
                return "R2 500 - R5 000";
            } else if ($income >= 5000 and  $income < 10001) {
                return "R5 000 - R10 000";
            } else if ($income >= 10000 and $income < 20001) {
                return "R10 000 - R20 000";
            } else if ($income >= 20000 and $income < 30001) {
                return "R20 000 - R30 000";
            } else if ($income >= 30000 and $income < 40001) {
                return "R30 000 - R40 000";
            } else {
                return "R40 000 +";
            }
        }

        // find CreditRiskCategory
        function find_category($category)
        {
            return str_replace(" ", "_", trim(explode('.', $category)[1]));
        }

        // format province
        function format_province($province)
        {
            switch ($province) {
                case "Gauteng":
                    return "G";
                    break;
                case "Eastern Cape":
                    return "EC";
                    break;
                case "Western Cape":
                    return "WC";
                    break;
                case "Northern Cape":
                    return "NC";
                case "Limpopo":
                    return "L";
                    break;
                case "Free State":
                    return "FS";
                    break;
                case "Mpumalanga":
                    return "M";
                    break;
                case "Kwazulu-Natal":
                    return "KN";
                    break;
                case "North West":
                    return "NW";
                    break;
                default:
                    return null;
            }
        }

        $actual_file = null;

        if(env('APP_ENV') == 'production') {
            $actual_file = \Storage::disk('s3')->get('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id  . ".csv");
        } else {
            $actual_file = \Storage::disk('local')->get('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id  . ".csv");
        }

        $array = array_map("str_getcsv", explode("\n", $actual_file));
        unset($array[0]);
        unset($array[sizeof($array)]);


        foreach($array as $row)
        {
            \MeetPAT\BarkerStreetRecord::create(
                [
                    'Idn' => check_value($row[0]),
                    'FirstName' => check_value($row[1]),
                    'Surname' => check_value($row[2]),
                    'MobilePhone1' => check_value(validate_mobile_number($row[3])),
                    'MobilePhone2' => check_value(validate_mobile_number($row[4])),
                    'MobilePhone3' => check_value(validate_mobile_number($row[5])),
                    'WorkPhone1' => check_value(validate_mobile_number($row[6])),
                    'WorkPhone2' => check_value(validate_mobile_number($row[7])),
                    'WorkPhone3' => check_value(validate_mobile_number($row[8])),
                    'HomePhone1' => check_value(validate_mobile_number($row[9])),
                    'HomePhone2' => check_value(validate_mobile_number($row[10])),
                    'HomePhone3' => check_value(validate_mobile_number($row[11])),
                    'AgeGroup' => check_value(get_age_group($row[12])),
                    'Gender' => check_value(get_gender($row[13])),
                    'PopulationGroup' => check_value(get_population_group($row[14])),
                    'DeceasedStatus' => check_value($row[15]),
                    'MaritalStatus' => check_value($row[16]),
                    'DirectorshipStatus' => check_value($row[17]),
                    'HomeOwnerShipStatus' => check_value($row[18]),
                    'income' => check_value($row[19]),
                    'incomeBucket' => check_value(find_income_bucket($row[20])),
                    'LSMGroup' => check_value($row[21]),
                    'CreditRiskCategory' => check_value(find_category($row[22])),
                    'ContactCategory' => check_value(find_category($row[23])),
                    'HasMobilePhone' => check_value($row[24]),
                    'HasResidentialAddress' => check_value($row[25]),
                    'Province' => check_value(format_province($row[26])),
                    'GreaterArea' => check_value($row[27]),
                    'Area' => check_value($row[28]),
                    'ResidentialAddress1Line1' => check_value($row[29]),
                    'ResidentialAddress1Line2' => check_value($row[30]),
                    'ResidentialAddress1Line3' => check_value($row[31]),
                    'ResidentialAddress2Line4' => check_value($row[32]),
                    'ResidentialAddress2PostalCode' => check_value($row[33]),
                    'PostalAddress1Line1' => check_value($row[34]),
                    'PostalAddress1Line2' => check_value($row[35]),
                    'PostalAddress1Line3' => check_value($row[36]),
                    'PostalAddress1Line4' => check_value($row[37]),
                    'PostalAddress1PostalCode' => check_value($row[38]),
                    'email' => check_value($row[39])
                ]
            );
        }

        return response()->json($array);
    }

    public function handle_upload(Request $request)
    {
        $csv_file = $request->file('audience_file');
        $fileName = uniqid();
        $path = $_FILES['audience_file']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $file_content = file_get_contents($csv_file);
           
        if($ext == 'csv') {
  

            if(env('APP_ENV') == 'production')
            {
                $directory_used = \Storage::disk('s3')->makeDirectory('client/client-records/');
                $file_uploaded = \Storage::disk('s3')->put('client/custom-audience/user_id_' . $request->user_id . '/' . $fileName  . ".csv", fopen($csv_file, 'r+'));
    
            } else {
                $directory_used = \Storage::disk('local')->makeDirectory('client/client-records/');
                $file_uploaded = \Storage::disk('local')->put('client/client-records/user_id_' . $request->user_id . '/' . $fileName  . ".csv", fopen($csv_file, 'r+'));
            }

        } else {
            return response()->json(["status" => 500, "error" => "Invalid CSV File"]);
        }

 
        
        return response()->json(["status" => 200,"file_id" => $fileName]);

    }
    public function handle_delete_upload(Request $request)
    {
        $file_exists = null;

        if(env('APP_ENV') == 'production') {
            $file_exists = \Storage::disk('s3')->exists('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        } else {
            $file_exists = \Storage::disk('local')->exists('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
        }

        if($file_exists) {
            if(env('APP_ENV') == 'production') {
                $file_exists = \Storage::disk('s3')->delete('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
            } else {
                $file_exists = \Storage::disk('local')->delete('client/client-records/user_id_' . $request->user_id . '/' . $request->file_id . '.csv');
            }
        } else {
            return response(500);
        }

        // return response('File: '. $request->file_id .' -> has been removed');
        return response(200);

    }
}
