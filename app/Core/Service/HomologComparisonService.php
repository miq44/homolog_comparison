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

         $conf= $this->getDataSetConfiguration($org1,$org2);
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

    private function getDataSetConfiguration($org1,$org2){
        $orgs = "(".$org1.",".$org2.")";
        $query = "SELECT 
                        mi.sample_number AS sample_id,
                        mi.number_of_control_replicate AS no_of_control,
                        mi.number_of_condition_replicate AS no_of_condition,
                        mi.data_table_id AS t_id,
                        dc.generated_data_table_name AS t_name,
                        org.organism_name
                    FROM
                        hl_sample_meta_info mi,
                        hl_dataset_configurations dc,
                        hl_organisms org
                    WHERE
                        mi.data_table_id = dc.id
                            AND dc.organism_id = org.id 
                            AND org.id in ".$orgs;
        $resultObj = DB::select(DB::raw($query ));
        $array = $this->convertConfObjIntoArray($resultObj);

    }

    private function convertConfObjIntoArray($objects){
        $array = [];
        foreach($objects as $key=>$obj){
            $array[$obj->t_name][$obj->sample_id]['control'] = $obj->no_of_control;
            $array[$obj->t_name][$obj->sample_id]['condition'] = $obj->no_of_condition;
        }
        return $array;
    }
}