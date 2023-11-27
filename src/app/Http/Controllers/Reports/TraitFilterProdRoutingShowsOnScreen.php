<?php

namespace App\Http\Controllers\Reports;

use App\Models\Prod_routing;

trait TraitFilterProdRoutingShowsOnScreen
{
    public function filterProdRoutingShowOnScreen($dataSource, $id){
        $result = [];
        foreach ($dataSource as $item){
            $idRouting = $item->prod_routing_id;
            $idShowMeOn = Prod_routing::find($idRouting)->getScreensShowMeOn()->pluck('id')->toArray()[0];
            if ($idShowMeOn == $id) $result[] = $item; 
        }
        return $result;
    }

}
