<?php

namespace App\Http\Controllers;

use App\Core\Service\HomologComparisonService;
use Illuminate\Http\Request;


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
        $firstOrganism = $request->org_1;
        $secondOrganism = $request->org_2;
        $data = $this->homologCompariosnService->getExpressionValueForBothOrganism($firstOrganism,$secondOrganism);
    }
}
