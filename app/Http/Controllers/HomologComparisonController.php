<?php

namespace App\Http\Controllers;

use App\Core\Service\HomologComparisonService;
use Illuminate\Http\Request;
use Session;


class HomologComparisonController extends Controller
{
    protected $homologCompariosnService;

    public function __construct(HomologComparisonService $service) {
        $this->homologCompariosnService = $service;
    }

    public function getHomePage(){
        $data = $this->homologCompariosnService->getAvailableOrganismName();
        return view('layout.home',compact('data'));
    }

    public function postOrganismNames(Request $request){
        $firstOrganismTableId = $request->org_t_id_1;
        $secondOrganismTableId = $request->org_t_id_2;
        $data = $this->homologCompariosnService->getExpressionValueForBothOrganism($firstOrganismTableId,$secondOrganismTableId);
        Session::put('exp_data',$data);
        return redirect()->back()->withInput();
    }
}
