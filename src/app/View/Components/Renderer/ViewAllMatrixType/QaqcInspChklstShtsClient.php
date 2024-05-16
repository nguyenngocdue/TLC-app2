<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\BigThink\Oracy;
use App\Models\Prod_routing;
use App\Models\Project;
use App\Models\Sub_project;
use App\Utils\Support\CurrentUser;

class QaqcInspChklstShtsClient extends QaqcInspChklstShts
{
    protected $allowCreation = false;

    private $SANDBOX_ID = 72;
    private $STW_SANDBOX_ID = 112;
    // private $STW_TOWNHOUSE_ID = 94;
    // private $STW_INSP_CHK_SHT_ID = 1007;

    private $projects, $subProjects;

    function  __construct()
    {
        $cu = CurrentUser::get();
        $this->subProjects = $cu->getSubProjectsOfProjectClient();
        $this->subProjects->prepend(Sub_project::findFromCache($this->STW_SANDBOX_ID));

        $projectIds = [];
        foreach ($this->subProjects as $subProject) $projectIds[] = $subProject->project_id;
        $projectIds = array_unique($projectIds);

        $this->projects = Project::query()
            ->whereIn('id', $projectIds)
            ->get();
        // dump($this->projects);
        parent::__construct();
    }


    private function getDefaultValues($subProjects)
    {
        $defaultProjectId = $this->SANDBOX_ID;
        $defaultSubProjectId = $this->STW_SANDBOX_ID;
        if (sizeof($subProjects) > 0) {
            $defaultSubProject = $subProjects->first();
            $defaultSubProjectId = $defaultSubProject->id;
            $defaultProjectId = $defaultSubProject->project_id;
        }

        // dump($defaultSubProject);
        return [$defaultProjectId, $defaultSubProjectId, null/* $defaultRoutingId*/];
    }

    private function getDataSource()
    {
        $projects = $this->projects;
        $subProjects = $this->subProjects;
        $subProjectIds = $subProjects->pluck('id')->toArray();

        $prodRoutings = Prod_routing::query()->get();
        $prodRoutings = $prodRoutings->filter(fn ($item) => $item->isShowOn("qaqc_insp_chklst_shts"))->values();
        Oracy::attach('getSubProjects()', $prodRoutings);
        $prodRoutings1 = [];
        foreach ($prodRoutings as $prodRouting0) {
            $getSubProjects = $prodRouting0->{"getSubProjects()"}->toArray();
            if (sizeof(array_intersect($getSubProjects, $subProjectIds)) > 0) {
                $prodRoutings1[] = $prodRouting0;
            }
        }

        return [$projects, $subProjects, $prodRoutings1];
    }

    protected function getFilterDataSource()
    {
        [$projects, $subProjects, $prodRoutings] = $this->getDataSource();
        return [
            'projects' => $projects,
            'sub_projects' => $subProjects,
            'prod_routings' => $prodRoutings,
        ];
    }

    protected function getViewportParams()
    {
        $userSettings = $this->getUserSettings();
        // dump($userSettings);
        [$project_id, $sub_project_id, $prod_routing_id] = $userSettings;
        [$defaultProject, $defaultSubProject, $defaultProdRouting] = $this->getDefaultValues($this->subProjects);
        $result =  [
            'project_id' => $project_id ?: $defaultProject,
            'sub_project_id' => $sub_project_id ?: $defaultSubProject,
            'prod_routing_id' => $prod_routing_id ?: $defaultProdRouting,
        ];

        // dump($result);
        return $result;
    }
}
