<?php

namespace App\View\Components\Reports\Modals;

use App\Models\Prod_routing;
use App\Models\Sub_project;
use App\View\Components\Reports\ParentIdParamReports;
use App\View\Components\Reports\ParentIdParamReports2;
use Illuminate\Support\Facades\DB;

class ParamProdRoutingId extends ParentIdParamReports2
{
    protected $referData = 'sub_project_id';
    protected $referData1 = 'checksheet_type_id';
    protected function getDataSource($attr_name)
    {

        $prodRoutings = Prod_routing::whereNull('deleted_by')->get();
        $result = [];
        foreach ($prodRoutings as $routing){
            $subProjectIds = $routing->getSubProjects()->pluck('id')->toArray();
            $chklstTmplIds = $routing->getChklstTmpls()->pluck('id')->toArray();
            $array = (object)[];
            $array->id = $routing->id;
            $array->name = $routing->name;
            $array->{$this->referData} = $subProjectIds;
            $array->{$this->referData1} = $chklstTmplIds;
            $result[] = $array;
        };
        return $result;
    }
}