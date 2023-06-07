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

        $prodRoutings = Prod_routing::whereNull('deleted_by')->pluck('name', 'id')->toArray();
        $result = [];
        foreach ($prodRoutings as $key => $value){
            $prdRoutings = Prod_routing::find($key)->getSubProjects()->pluck('id')->toArray();
            $chklstTmpls = Prod_routing::find($key)->getChklstTmpls()->pluck('id')->toArray();
            $array = (object)[];
            $array->id = $key;
            $array->name = $value;
            $array->{$this->referData} = $prdRoutings;
            $array->{$this->referData1} = $chklstTmpls;
            $result[] = $array;
        };
        // dump($result);
        return $result;
    }
}