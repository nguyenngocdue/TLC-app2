<?php

namespace App\View\Components\Renderer\ViewAllMatrixFilterDataSource;

use App\Models\Prod_routing;
use App\Models\Sub_project;

trait TraitFilterDataSourceForInternal
{
    use TraitFilterDataSourceCommon;

    protected function enrichRouting($prodRoutings)
    {
        //Remove routings that are not allow to show on the screen
        $prodRoutings = $prodRoutings->filter(fn($item) => $item->isShowOn($this->type))->values();

        //Enrich for listeners sub projects -> routing
        foreach ($prodRoutings as &$item) {
            $item->{"getSubProjects"} = $item->getSubProjects->pluck('id')->toArray();
        }

        return $prodRoutings;
    }

    protected function getRoutingListForFilter()
    {
        if (!$this->prodRoutingDatasource) {
            $subProjectDatasource = $this->getSubProjectListForFilter();
            $routingIds = [];
            foreach ($subProjectDatasource as $subProject) {
                $routingIds[] = $subProject->getProdRoutingsOfSubProject->pluck('id')->toArray();
            }
            $routingIds = array_unique(array_merge(...$routingIds));
            $prodRoutings = Prod_routing::query()
                ->whereIn('id', $routingIds)
                ->with([
                    "getSubProjects",
                    "getScreensShowMeOn",
                ])
                ->get();

            $prodRoutings = $this->enrichRouting($prodRoutings);
            $this->prodRoutingDatasource = $prodRoutings;
        } else {
            // echo "CACHE HIT - getRoutingListForFilter";
        }
        return $this->prodRoutingDatasource;
    }

    protected function getSubProjectListForFilter()
    {
        if (!$this->subProjectDatasource) {
            $this->subProjectDatasource = Sub_project::all();
        } else {
            // echo "CACHE HIT - getSubProjectListForFilter";
        }
        return $this->subProjectDatasource;
    }
}
