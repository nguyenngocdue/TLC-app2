<?php

namespace App\Http\Controllers\Reports;

use App\Models\Prod_routing;

trait TraitFilterProdRoutingShowsOnScreen
{
    public function filterProdRoutingShowOnScreen($dataSource, $id)
    {
        $result = [];
        foreach ($dataSource as $item) {
            $idRouting = $item->prod_routing_id;
            $arr = Prod_routing::find($idRouting)->getScreensShowMeOn()->pluck('id')->toArray();
            $idShowMeOn = reset($arr);
            if ($idShowMeOn == $id) $result[] = $item;
        }
        return $result;
    }

    public function filterProdRoutingByTypeID($typeId)
    {
        $prodRoutings = Prod_routing::all()->pluck('id')->toArray();
        $ids = [];
        foreach ($prodRoutings as $id) {
            $arr = Prod_routing::find($id)->getScreensShowMeOn()->pluck('id')->toArray();
            $idShowMeOn = reset($arr);
            if ($idShowMeOn == $typeId) $ids[] = $id;
        }
        return $ids;
    }
}
