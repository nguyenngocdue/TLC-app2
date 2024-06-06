<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\Models\Prod_routing;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

trait QaqcInspChklstShtsTraits
{
    private $SANDBOX_ID = 72;
    private $STW_SANDBOX_ID = 112;
    // private $STW_TOWNHOUSE_ID = 94;
    // private $STW_INSP_CHK_SHT_ID = 1007;

    function getProjectCollectionFromSubProjects()
    {
        $projectIds = [];
        foreach ($this->subProjects as $subProject) $projectIds[] = $subProject->project_id;
        $projectIds = array_unique($projectIds);

        return Project::query()
            ->whereIn('id', $projectIds)
            ->get();
    }

    function getRoutingCollectionFromSubProjectsForClients()
    {
        $subProjectIds = $this->subProjects->pluck('id')->toArray();

        $prodRoutings = Prod_routing::query()->with("getSubProjects")->get();
        $prodRoutings = $prodRoutings->filter(fn ($item) => $item->isShowOn("qaqc_insp_chklst_shts"))->values();

        foreach ($prodRoutings as &$item) {
            $item->{"getSubProjects"} = $item->getSubProjects->pluck('id')->toArray();
        }
        // Log::info($prodRoutings);
        $prodRoutings1 = collect();
        foreach ($prodRoutings as $prodRouting0) {
            $getSubProjects = $prodRouting0->getSubProjects;
            if (sizeof(array_intersect($getSubProjects, $subProjectIds)) > 0) {
                $prodRoutings1->push($prodRouting0);
            }
        }
        return $prodRoutings1;
    }
}
