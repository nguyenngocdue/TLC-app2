<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Prod_routing;
use App\Models\Qaqc_insp_tmpl;
use App\Models\Sub_project;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DashboardProjectClientController extends DashboardController
{
    private $SANDBOX_ID = 72;
    private $STW_SANDBOX_ID = 112;
    private $STW_TOWNHOUSE_ID = 94;
    private $STW_INSP_CHK_SHT_ID = 1007;

    private function getDefaultValues($subProjects)
    {
        $defaultSubProject = null;
        if (sizeof($subProjects) == 1) $defaultSubProject = $subProjects->first()->id;

        switch ($defaultSubProject) {
            case $this->STW_SANDBOX_ID:
                return [$this->SANDBOX_ID, $defaultSubProject, $this->STW_TOWNHOUSE_ID, $this->STW_INSP_CHK_SHT_ID];
            default:
                return [$this->SANDBOX_ID, $defaultSubProject, null, null];
        }
    }

    private function getDataSource()
    {
        $cu = CurrentUser::get();
        $subProjects = $cu->getSubProjectsOfProjectClient();
        $subProjects->push(Sub_project::findFromCache($this->STW_SANDBOX_ID));
        // dump($subProjects);

        $qaqcInspTmpls = Qaqc_insp_tmpl::query()->get();
        // dump($qaqcInspTmpls);

        $prodRoutings = Prod_routing::query()->get();
        $prodRoutings = $prodRoutings->filter(fn ($item) => $item->isShowOn("qaqc_insp_chklst_shts"))->values();
        return [$subProjects, $qaqcInspTmpls, $prodRoutings];
    }

    public function index(Request $request)
    {
        [$subProjects, $qaqcInspTmpls, $prodRoutings] = $this->getDataSource();

        $userSettings = $this->getUserSettings();
        [$project_id, $sub_project_id, $prod_routing_id, $qaqc_insp_tmpl_id] = $userSettings;
        [$defaultProject, $defaultSubProject, $defaultProdRouting, $defaultQaqcInspTmpl,] = $this->getDefaultValues($subProjects);

        $params = [
            'viewportParams' => [
                'project_id' => $project_id ?: $defaultProject,
                'sub_project_id' => $sub_project_id ?: $defaultSubProject,
                'qaqc_insp_tmpl_id' => $qaqc_insp_tmpl_id ?: $defaultQaqcInspTmpl,
                'prod_routing_id' => $prod_routing_id ?: $defaultProdRouting,
            ],
            'dataSource' => [
                'sub_projects' => $subProjects,
                'qaqc_insp_tmpls' => $qaqcInspTmpls,
                'prod_routings' => $prodRoutings,
            ],
        ];

        // echo "Project Client";
        // dump($params);
        // dd();
        return view('dashboards.dashboard-external-inspector-and-client', $params);
    }
}
