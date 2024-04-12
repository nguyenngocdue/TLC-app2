<?php

namespace App\View\Components\Reports\ModeParams;

use App\Models\Prod_routing_link;
use App\View\Components\Reports\ParentParamReports;

class ParamProdRoutingLinkId extends ParentParamReports
{
    protected $referData = 'prod_routing_id';
    protected $referData1 = 'prod_discipline_id';
    protected function getDataSource()
    {
        $hasListenTo = $this->hasListenTo();
        // dump($hasListenTo);
        $prodRoutingLinks = Prod_routing_link::whereNull('deleted_by')->get();
        $result = [];
        foreach ($prodRoutingLinks as $routing) {
            $prodRoutingIds = $routing->getProdRoutings()->get()->pluck('id')->toArray();
            $disciplineIds = $routing->getDiscipline()->pluck('id')->toArray();
            $array = (object)[];
            if ($routing->name === '-- available') continue;
            $array->id = $routing->id;
            $array->name = $routing->name;
            if ($hasListenTo) {
                $array->{$this->referData} = $prodRoutingIds;
                $array->{$this->referData1} = $disciplineIds;
            }
            $result[] = $array;
        };
        usort($result, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });
        // dump($result);
        return $result;
    }
}
