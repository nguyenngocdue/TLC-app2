<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Prod_routing;
use App\Models\Qaqc_insp_tmpl;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DashboardProjectClientController extends DashboardController
{
    private function getDefaultValues($subProjects)
    {
        if (sizeof($subProjects) == 1) {
            $defaultSubProject = $subProjects->first()->id;
        } else {
            $defaultSubProject = null;
        }
        switch ($defaultSubProject) {
            case 107:
                return [$defaultSubProject, 94, 1007];
            default:
                return [$defaultSubProject, null, null];
        }
    }

    public function index(Request $request)
    {
        $userSettings = $this->getUserSettings();
        // dump($userSettings);
        [$sub_project_id, $prod_routing_id, $qaqc_insp_tmpl_id] = $userSettings;
        $cu = CurrentUser::get();
        $subProjects = $cu->getSubProjectsOfProjectClient();
        // dump($subProjects);

        $qaqcInspTmpls = Qaqc_insp_tmpl::query()->get();
        // dump($qaqcInspTmpls);

        $prodRoutings = Prod_routing::query()->get();
        $prodRoutings = $prodRoutings->filter(fn ($item) => $item->isShowOn("qaqc_insp_chklst_shts"))->values();
        // dump($prodRoutings);

        [$defaultSubProject, $defaultProdRouting, $defaultQaqcInspTmpl,] = $this->getDefaultValues($subProjects);

        $params = [
            'viewportParams' => [
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
        return view('dashboards.dashboard-external-inspector-and-client', $params);
    }
}
