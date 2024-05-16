<?php

namespace App\View\Components\Renderer\ViewAllMatrixType;

use App\BigThink\Oracy;
use App\Models\Sub_project;
use App\Utils\Support\CurrentUser;

class QaqcInspChklstShtsInspector extends QaqcInspChklstShts
{
    use QaqcInspChklstShtsTraits;

    protected $metaShowPrint = !true;
    protected $metaShowProgress = !true;

    protected $allowCreation = false;

    private $dataSource = null;
    private $projects, $subProjects, $prodRoutings;

    function  __construct()
    {
        $this->dataSource =  $this->getDataSource();
        parent::__construct();
    }

    private function getDataSource()
    {
        $cu = CurrentUser::get();
        $this->subProjects = $cu->getSubProjectsOfExternalInspector();
        $this->subProjects->prepend(Sub_project::findFromCache($this->STW_SANDBOX_ID));

        $this->prodRoutings = $this->getAllRoutingList();
        $this->projects = $this->getProjectCollectionFromSubProjects();
        $result = [$this->projects, $this->subProjects, $this->prodRoutings];
        // dump($result);
        return $result;
    }

    protected function getFilterDataSource()
    {
        [$projects, $subProjects, $prodRoutings] = $this->dataSource;
        return [
            'projects' => $projects,
            'sub_projects' => $subProjects,
            'prod_routings' => $prodRoutings,
        ];
    }

    protected function getUserSettings()
    {
        $userSettings = parent::getUserSettings();
        // dump($userSettings);
        [$project_id, $sub_project_id, $prod_routing_id] = $userSettings;
        [$projects, $subProjects, $prodRoutings] = $this->dataSource;
        $defaultProjects = (sizeof($projects) > 0) ? $projects->first()->id : 72;
        $defaultSubProject = (sizeof($subProjects) > 0) ? $subProjects->first()->id : 112;
        $defaultProdRouting =  94;
        $result = [
            $project_id ?: $defaultProjects,
            $sub_project_id ?: $defaultSubProject,
            $prod_routing_id ?: $defaultProdRouting,
        ];
        // dump($result);
        return $result;
    }

    protected function getViewportParams()
    {
        $userSettings = $this->getUserSettings();
        [$project_id, $sub_project_id, $prod_routing_id] = $userSettings;
        $result = [
            'project_id' => $project_id,
            'sub_project_id' => $sub_project_id,
            'prod_routing_id' => $prod_routing_id,
        ];
        // dump($result);
        return $result;
    }

    protected function getAllRoutingList()
    {
        $cu = CurrentUser::get();
        $prodRoutings = $cu->getProdRoutingsOfExternalInspector();
        Oracy::attach('getSubProjects()', $prodRoutings);
        return $prodRoutings;
    }
}
