<?php

namespace App\Core\Service;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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

    public function getExpressionValueForBothOrganism($tId1, $tId2)
    {

        $config = $this->getDataSetConfiguration($tId1, $tId2);
        $tableConfig = $config['table_config'];
        $homologRelationTableName = $this->getHomologRelationshipTable($config['org_id']);
        $relationData = $this->getRelationData($homologRelationTableName, $tableConfig);
        $organismData = $this->getOrganismData($tableConfig);
        $data = $this->getData($relationData,$organismData);
        return json_encode($data) ;

//        if(is_array($columns)){
//            $firstArg=$columns[0];
//            $secondArg = $columns[1];
//        }else{
//            $firstArg = $data['columns'][0][0];
//            $secondArg = $data['columns'][1][0];
//
//        }
//        $exp = $this->getExpressionbyParameter($firstArg,$secondArg,$data['exp']);
    }

    private function getExpressionbyParameter($firstArg,$secondArg,$allExp){
        $array =[];

        foreach ($allExp as $key=>$value){
            $row=[];
            foreach($value[0] as $key2=>$value2){
                $row['gene_id_1'] = $key2;
                $row['exp_1']=$value2[$firstArg];
            }
            foreach($value[1] as $key2=>$value2){
                $row['gene_id_2'] = $key2;
                $row['exp_2']=$value2[$secondArg];
            }
            $array[] = $row;
        }
        dd($array);
    }

    private function getData($relationData,$organismData){
        $orgs =[];
        $columns = [];
        foreach ($organismData as $key=>$table){
            $flag = true ;
            foreach($table as $key2=>$row){
                foreach ($row as $key3=>$column){
                    if($key3=='Gene_Id'){
                        continue;
                    }
                   // dd($row->Gene_Id);
                    $geneId = strtoupper($row->Gene_Id);
                    $orgs[$key][$geneId][$key3] = $column;
                    if($flag){
                        $columns[$key][]=$key3;
                    }
                }
                $flag = false;
            }
        }
//       /$data['orgs'] = $orgs ;
        $data['columns'] = $columns;
        $i=0;
        $exp=[];
        foreach ($relationData as $key=>$relation){
            $geneId1 = strtoupper($relation->gene_id_1);
            $geneId2 =strtoupper($relation->gene_id_2);
            $row=[];
            if(isset($orgs[0][$geneId1]) && isset($orgs[1][$geneId2])){
                $exp['gene_id_1'][] = $geneId1;
                $exp['gene_id_2'][] = $geneId2;
                foreach ($orgs[0][$geneId1] as $key2=>$value2){
                    $exp['1_'.$key2][]=$value2;
                }
                foreach ($orgs[1][$geneId2] as $key2=>$value2){
                    $exp['2_'.$key2][]=$value2;
                }
//                $row[0][$geneId1] = $orgs[0][$geneId1] ;
//                $row[1][$geneId2] = $orgs[1][$geneId2] ;
                //dd($orgs[0][$geneId1]);
               // $exp[]=$row;
            }

        }
        $data['exp'] = $exp ;
        return $data ;
    }

    private function getOrganismData($tableConfig){
      $array = [];
      foreach($tableConfig as $key=>$value){
          if(Cache::has($key)){
              $resultObj = Cache::get($key);
          }else{
              $query = "SELECT * FROM ".$key;
              $resultObj = DB::select(DB::raw($query));
              Cache::put($key,$resultObj,10);
          }
         $array[]=$resultObj;
      }
        return $array ;
    }
    private function getRelationData($homologTableName, $expTableConfig)
    {

        if (Cache::has($homologTableName)) {
            $homologRelationshipData = Cache::get($homologTableName);
        } else {
            $homologRelationshipData = $this->getHomologRelationshipData($homologTableName);
            Cache::put($homologTableName, $homologRelationshipData, 10);
        }
        return $homologRelationshipData;
    }

    private function getHomologRelationshipData($tableName)
    {

        $query = "SELECT * FROM " . $tableName;
        $resultObj = DB::select(DB::raw($query));
        return $resultObj;
    }

    private function getHomologRelationshipTable($orgIds)
    {
        $orgIds = "(" . implode(',', $orgIds) . ")";
        $query = "SELECT 
                        mapping_table_name as t_name
                    FROM
                        hl_homolog_relation_configuration
                    WHERE
                        first_organism_id in " . $orgIds . " 
                            AND 
                        second_organism_id in " . $orgIds;
        $resultObj = DB::select(DB::raw($query));
        return $resultObj[0]->t_name;
    }

    private function convertOrganismObjIntoArray($objects)
    {
        $array = [];
        foreach ($objects as $key => $obj) {
            $row['org_name'] = $obj->org_name;
            $row['t_id'] = $obj->t_id;
            $array[$obj->stress_name][] = $row;
        }
        return $array;
    }

    private function getDataSetConfiguration($tId1, $tId2)
    {
        $tables = "(" . $tId1 . "," . $tId2 . ")";
        $query = "SELECT 
                        mi.sample_number AS sample_id,
                        mi.number_of_control_replicate AS no_of_control,
                        mi.number_of_condition_replicate AS no_of_condition,
                        mi.data_table_id AS t_id,
                        dc.generated_data_table_name AS t_name,
                        org.organism_name,
                        org.id as org_id
                    FROM
                        hl_sample_meta_info mi,
                        hl_dataset_configurations dc,
                        hl_organisms org
                    WHERE
                        mi.data_table_id = dc.id
                            AND dc.organism_id = org.id 
                            AND dc.id in " . $tables;
        $resultObj = DB::select(DB::raw($query));
        $array = $this->convertConfObjIntoArray($resultObj);
        return $array;

    }

    private function convertConfObjIntoArray($objects)
    {
        $array = [];
        $orgId = [];
        foreach ($objects as $key => $obj) {
            $array[$obj->t_name][$obj->sample_id]['control'] = $obj->no_of_control;
            $array[$obj->t_name][$obj->sample_id]['condition'] = $obj->no_of_condition;
            if (!in_array($obj->org_id, $orgId)) {
                $orgId[] = $obj->org_id;
            }

        }
        $result['table_config'] = $array;
        $result['org_id'] = $orgId;
        return $result;
    }
}