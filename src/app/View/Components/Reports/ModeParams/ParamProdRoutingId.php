<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Prod_routing;
use App\View\Components\Reports\ParentParamReports;

class ParamProdRoutingId extends ParentParamReports
{
    protected $referData = 'sub_project_id';
    protected $referData1 = 'checksheet_type_id';
    protected function getDataSource()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $reportName =last(explode('/',$uri));
        $ids = config("report.".$reportName)?? [];
        $prodRoutings = Prod_routing::whereNull('deleted_by')
                        ->when(count($ids), function($query) use ($ids) {
                            return $query->whereIn('id', $ids);
                        })
                        ->get();
        $result = [];
        foreach ($prodRoutings as $routing){
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