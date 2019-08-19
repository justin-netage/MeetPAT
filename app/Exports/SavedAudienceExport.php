<?php

namespace MeetPAT\Exports;

use MeetPAT\EnrichedRecord;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

ini_set('memory_limit', '-1');

class SavedAudienceExport implements FromQuery, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    use Exportable;

    public function __construct(array $request, int $user_id)
    {
        $this->request = $request;
        $this->user_id = $user_id;
    }

    public function headings(): array
    {
        return [
            "FirstName",
            "Middlename",
            "Surname",
            "MobilePhone",
            "Email"
        ];
    }

    public function query()
    {

        $records = EnrichedRecord::query()
        ->select(array("FirstName", "Middlename","Surname","CleanPhone", "Email1"))
        ->whereRaw("find_in_set('".$this->user_id."',affiliated_users)");

        if(array_key_exists("provinceContacts", $this->request) and $this->request["provinceContacts"][0])
            $records = $records
            ->whereIn('Province', explode(",", $this->request["provinceContacts"][0]));
        
        if(array_key_exists("municipalityContacts", $this->request) and $this->request["municipalityContacts"][0]) 
            $records = $records
            ->whereIn('Municipality', explode(",", $this->request["municipalityContacts"][0]));    
         
        if(array_key_exists("AgeContacts", $this->request) and $this->request["AgeContacts"][0])
            $records = $records
            ->whereIn('AgeGroup', explode(",", $this->request["AgeContacts"][0]));

        if(array_key_exists("GenderContacts", $this->request) and $this->request["GenderContacts"][0]) 
            $records = $records
            ->whereIn('Gender', explode(",", $this->request["GenderContacts"][0]));
            
        if(array_key_exists("populationContacts", $this->request) and $this->request["populationContacts"][0]) 
            $records = $records
            ->whereIn('PopulationGroup', explode(",", $this->request["populationContacts"][0]));
        
        if(array_key_exists("generationContacts", $this->request) and $this->request["generationContacts"][0])
            $records = $records
            ->whereIn('Generation', explode(",", $this->request["generationContacts"][0]));
        
        if(array_key_exists("maritalStatusContacts", $this->request) and $this->request["maritalStatusContacts"][0]) 
            $records = $records
            ->whereIn('MaritalStatus', explode(",", $this->request["maritalStatusContacts"][0]));
        
        if(array_key_exists("homeOwnerContacts", $this->request) and $this->request["homeOwnerContacts"][0]) 
            $records = $records
            ->whereIn('HomeOwnershipStatus', explode(",", $this->request["homeOwnerContacts"][0]));

        if(array_key_exists("propertyValuationContacts", $this->request) and $this->request["propertyValuationContacts"][0]) 
            $records = $records
            ->whereIn('PropertyValuationBucket', explode(",", $this->request["propertyValuationContacts"][0]));
        
        if(array_key_exists("propertyCountBucketContacts", $this->request) and $this->request["propertyCountBucketContacts"][0])
            $records = $records
            ->whereIn('PropertyCountBucket', explode(",", $this->request["propertyCountBucketContacts"][0]));

        if(array_key_exists("riskCategoryContacts", $this->request) and $this->request["riskCategoryContacts"][0])
            $records = $records->whereIn('CreditRiskCategory', explode(",", $this->request["riskCategoryContacts"][0]));

        if(array_key_exists("houseHoldIncomeContacts", $this->request) and $this->request["houseHoldIncomeContacts"][0]) 
            $records = $records
            ->whereIn('IncomeBucket', explode(",", $this->request["houseHoldIncomeContacts"][0]));
        
        if(array_key_exists("employerContacts", $this->request) and $this->request["employerContacts"][0])
            $records = $records
            ->whereIn('Employer', explode(",", $this->request["employerContacts"][0]));
        
        if(array_key_exists("lsmGroupContacts", $this->request) and $this->request["lsmGroupContacts"][0])
            $records = $records
            ->whereIn('LSMGroup', explode(",", $this->request["lsmGroupContacts"][0]));

        if(array_key_exists("vehicleOwnerContacts", $this->request) and $this->request["vehicleOwnerContacts"][0]) {
            $records = $records
            ->whereIn('VehicleOwnershipStatus', explode(",", $this->request["vehicleOwnerContacts"][0]));
        }

        if(array_key_exists("directorsContacts", $this->request) and $this->request["directorsContacts"][0]) {
            $records = $records
            ->whereIn('DirectorshipStatus', explode(",", $this->request["directorsContacts"][0]));
        }
    
        if(array_key_exists("areaContacts", $this->request) and $this->request["areaContacts"][0]) {
            $records = $records
            ->whereIn('Area', explode(",", $this->request["areaContacts"][0]));
        }
        
        if(array_key_exists("citizenVsResidentsContacts", $this->request) and $this->request["citizenVsResidentsContacts"][0]) {
            if(in_array("citizen", explode(",", $this->request["citizenVsResidentsContacts"][0]))) {
                $records = $records
                ->where('id6', '!=', '');
                

            } else if(in_array("resident", explode(",", $this->request["citizenVsResidentsContacts"][0]))) {
                $records = $records
                ->where('HasResidentialAddress', "true");

            }
        }

        return $records;
    }


}
