<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Prod_routing;
use App\Models\Qaqc_insp_tmpl;
use App\Utils\Support\CurrentUser;
use Illuminate\Http\Request;

class DashboardProjectClientController extends DashboardController
{
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

        $params = [
            'viewportParams' => [
                'sub_project_id' => $sub_project_id,
                'qaqc_insp_tmpl_id' => $qaqc_insp_tmpl_id,
                'prod_routing_id' => $prod_routing_id,
            ],
            'dataSource' => [
                'sub_projects' => $subProjects,
                'qaqc_insp_tmpls' => $qaqcInspTmpls,
                'prod_routings' => $prodRoutings,
            ],
        ];

        return view('dashboards.dashboard-external-inspector', $params);
    }
}
