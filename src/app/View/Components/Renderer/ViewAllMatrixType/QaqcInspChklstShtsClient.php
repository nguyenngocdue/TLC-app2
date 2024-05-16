<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\BigThink\Oracy;
use App\Models\Prod_routing;
use App\Models\Sub_project;
use App\Utils\Support\CurrentUser;

class QaqcInspChklstShtsClient extends QaqcInspChklstShts
{
    use QaqcInspChklstShtsTraits;
    protected $allowCreation = false;

    private $projects, $subProjects;

    function  __construct()
    {
        $cu = CurrentUser::get();
        $this->subProjects = $cu->getSubProjectsOfProjectClient();
        $this->subProjects->prepend(Sub_project::findFromCache($this->STW_SANDBOX_ID));

        $this->projects = $this->getProjectCollectionFromSubProjects();
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
        $prodRoutings = $this->getRoutingCollectionFromSubProjects();

        return [$projects, $subProjects, $prodRoutings];
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
