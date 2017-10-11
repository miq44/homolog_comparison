<?php

namespace App\Core\Service;
use Illuminate\Support\Facades\DB;

class HomologComparisonService
{

    public function getAvailableOrganismName()
    {
        $query =
            "SELECT 
                    dc.id AS t_id,
                    org.id AS org_d,
                    org.organism_name AS org_name,
                    sc.id AS stress_id,
                    sc.stress_name
            FROM
                    hl_dataset_configurations dc,
                    hl_organisms org,
                    hl_stress_condition sc
            WHERE
                    dc.stress_condition_id = sc.id
                    AND 
                    dc.organism_id = org.id";

        $resultObj = DB::select(DB::raw($query));
        $resultArray = $this->convertOrganismObjIntoArray($resultObj);
        return $resultArray;

    }

    public function getExpressionValueForBothOrganism($org1, $org2){

    }

    private function convertOrganismObjIntoArray($objects){
        $array =[];
        foreach ($objects as $key=>$obj){
            $row['org_name'] = $obj->org_name;
            $row['t_id'] = $obj->t_id;
            $array[$obj->stress_name][] = $row;
        }
        return $array;
    }
}