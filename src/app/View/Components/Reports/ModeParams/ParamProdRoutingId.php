<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Prod_routing;
use App\Utils\Support\ParameterReport;
use App\View\Components\Reports\ParentParamReports;

class ParamProdRoutingId extends ParentParamReports
{
    protected $referData = 'sub_project_id';
    protected $referData1 = 'checksheet_type_id';
    protected function getDataSource()
    {
        $configData = ParameterReport::getConfigByName('prod_routing_id');
        $targetIds = ParameterReport::getTargetIds($configData);
        $prodRoutings = ParameterReport::getDBParameter($targetIds, 'Prod_routing');
        $result = [];

        
        foreach ($prodRoutings as $routing){
            // dd($routing->getScreensShowMeOn()->toArray(), $this);
            if(empty($routing->getScreensShowMeOn()->toArray())) continue;
            
            $subProjectIds = $routing->getSubProjects()->pluck('id')->toArray();
            $chklstTmplIds = $routing->getChklstTmpls()->pluck('id')->toArray();
            $array = (object)[];
            $array->id = $routing->id;
            if($routing->name === '-- available') continue;
            $array->name = $routing->name;
            $array->{$this->referData} = $subProjectIds;
            $array->{$this->referData1} = $chklstTmplIds;
            $result[] = $array;
        };
        usort($result, function ($a, $b) { return strcmp($a->name, $b->name); });
        return $result;
    }
}